<?php

namespace App\Http\Controllers;

use App\Models\ConcentradoResolution;
use App\Models\SecurityInvestigation;
use App\Models\SecurityInvestigationImage;
use App\Models\SecurityFlashAlert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SecurityInvestigationController extends Controller
{
    private array $classificationFields = [
        'agentes',
        'tipos_contacto',
        'actos_inseguros',
        'error_humano',
        'protecciones_factores',
        'condiciones_directas',
        'factor_organizacional',
        'tipo_lesion',
        'parte_cuerpo_afectada',
    ];

    private array $mainFields = [
        'security_flash_alert_id',
        'folio',
        'nombre_incidente',
        'fecha_investigacion',
        'lugar',
        'fecha',
        'hora',
        'departamento',
        'empresa_contratista',
        'clasificacion_actual',
        'unidad',
        'operador',
        'afectado',
        'puesto_afectado',
        'supervisor_monitor',
        'investigacion',
        'descripcion',
        'arbol_causas',
        'naturaleza_incidente',
        'causa_raiz',
        'como_se_pudo_evitar',
        'hallazgos_iniciales',
        'reporte_camara_webfleet',
        'reporte_readi_score',
        'reporte_webfleet',
        'otra_informacion',
        'primeros_auxilios',
        'nombre_aplico_primeros_auxilios',
        'discapacidad',
        'danio_evaluado',
        'especialista',
    ];

    /* ================================================================
     | LISTADO
     * ================================================================ */
    public function index(Request $request)
    {
        $query = SecurityInvestigation::query()
            ->withCount(['actions', 'team', 'images'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                    ->orWhere('nombre_incidente', 'like', "%{$search}%")
                    ->orWhere('operador', 'like', "%{$search}%")
                    ->orWhere('afectado', 'like', "%{$search}%")
                    ->orWhere('lugar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(30));
    }

    /* ================================================================
     | CREAR
     * ================================================================ */
    public function store(Request $request)
    {
        $this->validateMain($request);

        return DB::transaction(function () use ($request) {
            $alert = null;
            $alertId = $request->input('security_flash_alert_id');

            if ($alertId) {
                $alert = SecurityFlashAlert::findOrFail($alertId);

                $existing = SecurityInvestigation::where(
                    'security_flash_alert_id',
                    $alert->id
                )->first();

                if ($existing) {
                    return response()->json([
                        'message' => 'Ya existe una investigación para esta alerta.',
                        'created' => false,
                        'data' => $existing->load([
                            'actions',
                            'team',
                            'images',
                            'finalizedBy',
                        ]),
                    ], 200);
                }
            }

            $data = $this->mainData($request);

            if ($alert) {
                $data = $this->copyAlertData($data, $alert);
                $data['security_flash_alert_id'] = $alert->id;

                // Si viene de una Flash, generar folio automático.
                $data['folio'] = $this->generateFolio();
            } else {
                // Si es una investigación nueva, respetar el folio escrito.
                $data['folio'] = $request->input('folio');
            }

            $data['status'] = 'draft';

            $investigation = SecurityInvestigation::create($data);

            $this->syncActions($investigation, $request);
            $this->syncTeam($investigation, $request);
            $this->saveImages($investigation, $request);

            return response()->json([
                'message' => 'Investigación creada correctamente.',
                'created' => true,
                'data' => $investigation->load([
                    'actions',
                    'team',
                    'images',
                    'finalizedBy',
                ]),
            ], 201);
        });
    }

    /* ================================================================
     | CONSULTAR
     * ================================================================ */
    public function show($id)
    {
        $investigation = SecurityInvestigation::with([
            'actions',
            'team',
            'images',
            'finalizedBy',
            'securityFlashAlert',
        ])->findOrFail($id);

        $data = $investigation->toArray();

        $data['acciones_inmediatas'] = $investigation->actions
            ->where('tipo', 'inmediata')
            ->values();

        $data['acciones_repeticion'] = $investigation->actions
            ->where('tipo', 'repeticion')
            ->values();

        $data['equipo_investigacion'] = $investigation->team
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'puesto' => $member->puesto,
                    'nombre' => $member->nombre,
                    'firma' => $member->firma_ruta,
                    'firma_ruta' => $member->firma_ruta,
                    'orden' => $member->orden,
                ];
            })->values();

        $data['imagenes'] = $investigation->images
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'tipo' => $image->tipo,
                    'nombre_original' => $image->nombre_original,
                    'ruta' => $image->ruta,
                    'url' => asset('storage/' . $image->ruta),
                    'orden' => $image->orden,
                    'mime_type' => $image->mime_type,
                    'tamano' => $image->tamano,
                ];
            })->values();

        return response()->json(['data' => $data, ]);
    }

    /* ================================================================
     | ACTUALIZAR
     * ================================================================ */
    public function update(Request $request, $id)
    {
        $investigation = SecurityInvestigation::findOrFail($id);

        if ($investigation->status === 'finalized') {
            return response()->json(['message' => 'No puedes modificar una investigación finalizada.',], 403);
        }

        $this->validateMain($request, true);

        // Guardamos las rutas que se eliminarán físicamente.
        $filesToDelete = [];

        DB::transaction(function () use ($request, $investigation, &$filesToDelete) {
            $data = $this->mainData($request);

            unset($data['security_flash_alert_id']);

            $investigation->update($data);

            $this->syncActions($investigation, $request);
            $this->syncTeam($investigation, $request);

            // Eliminar únicamente las imágenes que el usuario marcó.
            $filesToDelete = $this->deleteRequestedImages($investigation, $request);

            // Guardar las imágenes nuevas sin tocar las que se conservaron.
            $this->saveImages($investigation, $request);
        });

        // Los archivos se borran después de confirmar la transacción.
        $this->deletePhysicalFiles($filesToDelete);

        return response()->json([
            'message' => 'Investigación actualizada correctamente.',
            'data' => $investigation->fresh()->load([
                'actions',
                'team',
                'images',
                'finalizedBy',
            ]),
        ]);
    }

    /* ================================================================
     | FINALIZAR
     * ================================================================ */
    public function finalize(Request $request, $id)
    {
        $investigation = SecurityInvestigation::findOrFail($id);

        if ($investigation->status === 'finalized') {
            return response()->json(['message' => 'La investigación ya se encuentra finalizada.',], 422);
        }

        $this->validateImageInputs($request);

        $filesToDelete = [];

        DB::transaction(function () use ($request, $investigation, &$filesToDelete) {
            // Permite quitar imágenes o adjuntar las últimas evidencias
            // al momento de finalizar.
            $filesToDelete = $this->deleteRequestedImages($investigation, $request);

            $this->saveImages($investigation, $request);

            $investigation->update([
                'status' => 'finalized',
                'finalized_at' => now(),
                'finalized_by' => auth()->id(),
            ]);
            
            $this->syncConcentrado($investigation);
        });

        $this->deletePhysicalFiles($filesToDelete);

        return response()->json([
            'message' => 'Investigación finalizada correctamente.',
            'data' => $investigation->fresh()->load([
                'actions',
                'team',
                'images',
                'finalizedBy',
            ]),
        ]);
    }

    /* ================================================================
    | SINCRONIZAR INVESTIGACIÓN CON CONCENTRADO DE RESOLUCIONES
    * ================================================================ */
    private function syncConcentrado(SecurityInvestigation $investigation): void
    {
        // Eliminar registros anteriores de esta investigación
        ConcentradoResolution::where('security_investigation_id', $investigation->id)   
            ->where('source', 'INVESTIGACION')->delete();

        // Obtener únicamente las acciones para evitar repetición
        $actions = $investigation->actions()
            ->where('tipo', 'repeticion')
            ->get();

        foreach ($actions as $action) {

            // No registrar acciones sin descripción
            if (empty(trim($action->descripcion ?? ''))) {
                continue;
            }

            ConcentradoResolution::create([
                'source' => 'INVESTIGACION',
                'security_investigation_id' => $investigation->id,

                'folio' => $investigation->folio,
                'area' => $investigation->departamento,

                'resolution' => $action->descripcion,
                'category' => 'Investigación de Seguridad',
                'agreement_date' => now()->toDateString(),

                'responsible_id' => $action->responsible_id,
                'planned_closure' => $action->fecha_compromiso,

                'status' => 'NO INICIADA',
            ]);
        }
    }

    /* ================================================================
     | ELIMINAR INVESTIGACIÓN
     * ================================================================ */
    public function destroy($id)
    {
        $investigation = SecurityInvestigation::with([
            'images',
            'team',
        ])->findOrFail($id);

        if ($investigation->status === 'finalized') {
            return response()->json(['message' => 'No puedes eliminar una investigación finalizada.',], 403);
        }

        $filesToDelete = [];

        DB::transaction(function () use ($investigation, &$filesToDelete) {
            foreach ($investigation->images as $image) {
                if ($image->ruta) {
                    $filesToDelete[] = $image->ruta;
                }

                $image->delete();
            }

            foreach ($investigation->team as $member) {
                if ($member->firma_ruta) {
                    $filesToDelete[] = $member->firma_ruta;
                }

                $member->delete();
            }

            // Las acciones se eliminan explícitamente.
            $investigation->actions()->delete();

            $investigation->delete();
        });

        $this->deletePhysicalFiles($filesToDelete);

        return response()->json(['message' => 'Investigación eliminada correctamente.', ]);
    }

    /* ================================================================
     | MOSTRAR IMAGEN
     * ================================================================ */
    public function showImage($id)
    {
        $image = SecurityInvestigationImage::findOrFail($id);

        $path = $image->ruta;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'El archivo no existe: ' . $path);
        }

        $mimeType = Storage::disk('public')->mimeType($path);

        return response()->file(
            Storage::disk('public')->path($path),
            [
                'Content-Type' => $mimeType ?: 'image/png',
                'Cache-Control' => 'public, max-age=3600',
            ]
        );
    }

    /* ================================================================
     | VALIDACIONES
     * ================================================================ */
    private function validateMain(Request $request, bool $updating = false): void
    {
        $rules = [
            'security_flash_alert_id' => 'nullable|integer|exists:security_flash_alerts,id',
            'folio' => $updating
                ? 'sometimes|required|string|max:50'
                : 'required_without:security_flash_alert_id|string|max:50',
            'nombre_incidente' => 'nullable|string|max:255',
            'fecha_investigacion' => 'nullable|date',
            'lugar' => 'nullable|string|max:500',
            'fecha' => 'nullable|date',
            'hora' => 'nullable|date_format:H:i',
            'departamento' => 'nullable|string|max:150',
            'empresa_contratista' => 'nullable|string|max:255',
            'clasificacion_actual' => 'nullable|string|max:100',
            'unidad' => 'nullable|string|max:255',
            'operador' => 'nullable|string|max:255',
            'afectado' => 'nullable|string|max:255',
            'puesto_afectado' => 'nullable|string|max:255',
            'supervisor_monitor' => 'nullable|string|max:255',
            'investigacion' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
            'arbol_causas' => 'nullable|string',
            'naturaleza_incidente' => 'nullable|string',
            'causa_raiz' => 'nullable|string',
            'como_se_pudo_evitar' => 'nullable|string',
            'hallazgos_iniciales' => 'nullable|string',
            'reporte_camara_webfleet' => 'nullable|string',
            'reporte_readi_score' => 'nullable|string',
            'reporte_webfleet' => 'nullable|string',
            'otra_informacion' => 'nullable|string',
            'primeros_auxilios' => 'nullable|string',
            'nombre_aplico_primeros_auxilios' => 'nullable|string|max:255',
            'discapacidad' => 'nullable|string',
            'danio_evaluado' => 'nullable|string',
            'especialista' => 'nullable|string|max:255',

            'acciones_inmediatas' => 'nullable|array',
            'acciones_inmediatas.*.descripcion' => 'nullable|string',
            'acciones_inmediatas.*.responsable' => 'nullable|string|max:255',

            'acciones_repeticion' => 'nullable|array',
            'acciones_repeticion.*.descripcion' => 'nullable|string',
            'acciones_repeticion.*.responsable' => 'nullable|string|max:255',
            'acciones_repeticion.*.fecha_compromiso' => 'nullable|date',

            'equipo_investigacion' => 'nullable|array',
            'equipo_investigacion.*.puesto' => 'nullable|string|max:255',
            'equipo_investigacion.*.nombre' => 'nullable|string|max:255',

            'arbol_causas_file' => 'nullable|image|max:10240',

            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'image|max:10240',

            'camara_webfleet' => 'nullable|array|max:2',
            'camara_webfleet.*' => 'image|max:10240',

            'reporte_webfleet_file' => 'nullable|image|max:10240',

            'imagenes_eliminar' => 'nullable|array',
            'imagenes_eliminar.*' => 'integer|distinct|exists:security_investigation_images,id',
        ];

        foreach ($this->classificationFields as $field) {
            $rules[$field] = 'nullable|array';
            $rules["{$field}.*.valor"] = 'nullable|string|max:255';
            $rules["{$field}.*.otro"] = 'nullable|string|max:255';
        }

        $request->validate($rules);
    }

    private function validateImageInputs(Request $request): void
    {
        $request->validate([
            'arbol_causas_file' => 'nullable|image|max:10240',

            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'image|max:10240',

            'camara_webfleet' => 'nullable|array|max:2',
            'camara_webfleet.*' => 'image|max:10240',

            'reporte_webfleet_file' => 'nullable|image|max:10240',

            'imagenes_eliminar' => 'nullable|array',
            'imagenes_eliminar.*' => 'integer|distinct|exists:security_investigation_images,id',
        ]);
    }

    /* ================================================================
     | DATOS PRINCIPALES
     * ================================================================ */
    private function mainData(Request $request): array
    {
        $data = $request->only($this->mainFields);

        foreach ($this->classificationFields as $field) {
            $data[$field] = $request->input($field, []);
        }

        $data['investigacion'] = $request->boolean('investigacion', true);

        return $data;
    }

    private function generateFolio(): string
    {
        $year = now()->format('Y');

        $last = SecurityInvestigation::whereYear('created_at', $year)
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $number = $last ? ((int) substr($last->folio, -4)) + 1 : 1;

        return 'F07-38-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function copyAlertData(array $data, $alert): array
    {
        $fields = [
            'nombre_incidente' => 'nombre_incidente',
            'lugar' => 'area_lugar',
            'fecha' => 'fecha',
            'hora' => 'hora',
            'departamento' => 'departamento',
            'empresa_contratista' => 'empresa_contratista',
            'clasificacion_actual' => 'clasificacion_actual',
            'unidad' => 'unidad',
            'operador' => 'operador',
            'afectado' => 'afectado',
            'puesto_afectado' => 'puesto_afectado',
            'supervisor_monitor' => 'supervisor_monitor',
            'descripcion' => 'descripcion',
        ];

        foreach ($fields as $investigationField => $alertField) {
            if (empty($data[$investigationField]) && !empty($alert->{$alertField})) {
                $data[$investigationField] = $alert->{$alertField};
            }
        }

        return $data;
    }

    /* ================================================================
    | SINCRONIZAR ACCIONES
    * ================================================================ */
    private function syncActions(SecurityInvestigation $investigation, Request $request): void
    {
        // Obtener las acciones enviadas
        $groups = [
            'acciones_inmediatas' => 'inmediata',
            'acciones_repeticion' => 'repeticion',
        ];

        foreach ($groups as $field => $tipo) {

            $actions = $request->input($field, []);

            // Si vienen como JSON dentro de FormData, convertirlas a array
            if (is_string($actions)) {
                $actions = json_decode($actions, true) ?? [];
            }

            if (!is_array($actions)) {
                $actions = [];
            }

            // Eliminar las acciones anteriores de este tipo
            $investigation->actions()
                ->where('tipo', $tipo)
                ->delete();

            foreach ($actions as $index => $action) {

                if (!is_array($action)) {
                    continue;
                }

                $descripcion = trim($action['descripcion'] ?? '');
                $responsibleId = $action['responsible_id'] ?? null;
                $fechaCompromiso = $action['fecha_compromiso'] ?? null;

                // Normalizar valores vacíos
                $responsibleId = ($responsibleId !== '' && $responsibleId !== null) ? $responsibleId : null;

                $fechaCompromiso = ($fechaCompromiso !== '' && $fechaCompromiso !== null) ? $fechaCompromiso : null;

                // Ignorar filas completamente vacías
                if ($descripcion === '' && $responsibleId === null && $fechaCompromiso === null) {
                    continue;
                }

                $investigation->actions()->create([
                    'tipo' => $tipo,
                    'numero' => $index + 1,
                    'descripcion' => $descripcion,
                    'responsible_id' => $responsibleId,
                    'fecha_compromiso' => $tipo === 'repeticion' ? $fechaCompromiso : null,
                ]);
            }
        }
    }
    /* ================================================================
     | SINCRONIZAR EQUIPO
     * ================================================================ */
    private function syncTeam(SecurityInvestigation $investigation, Request $request): void {
        $existing = $investigation->team()->get()->keyBy('orden');

        $members = $request->input('equipo_investigacion', []);
        $keepIds = [];

        foreach ($members as $index => $member) {
            if (empty(trim($member['puesto'] ?? '')) && empty(trim($member['nombre'] ?? ''))) {
                continue;
            }

            $orden = $index + 1;

            $team = $investigation->team()->updateOrCreate(
                ['orden' => $orden],
                [
                    'puesto' => $member['puesto'] ?? '',
                    'nombre' => $member['nombre'] ?? '',
                ]
            );

            $keepIds[] = $team->id;
        }

        $investigation->team()
            ->whereNotIn('id', $keepIds ?: [0])
            ->delete();
    }

    /* ================================================================
     | GUARDAR IMÁGENES NUEVAS
     * ================================================================ */
    private function saveImages(SecurityInvestigation $investigation, Request $request): void {
        $groups = [
            'arbol_causas_file' => [
                'tipo' => 'arbol_causas',
                'multiple' => false,
            ],
            'evidencias' => [
                'tipo' => 'evidencia',
                'multiple' => true,
            ],
            'camara_webfleet' => [
                'tipo' => 'camara_webfleet',
                'multiple' => true,
            ],
            'reporte_webfleet_file' => [
                'tipo' => 'reporte_webfleet',
                'multiple' => false,
            ],
        ];

        foreach ($groups as $field => $config) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $files = $config['multiple'] ? $request->file($field, []) : [$request->file($field)];

            foreach ($files as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $path = $file->store("security-investigations/{$investigation->id}", 'public');

                $lastOrder = $investigation->images()
                    ->where('tipo', $config['tipo'])
                    ->max('orden') ?? 0;

                $investigation->images()->create([
                    'tipo' => $config['tipo'],
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'tamano' => $file->getSize(),
                    'orden' => $lastOrder + 1,
                ]);
            }
        }
    }

    /* ================================================================
     | ELIMINAR IMÁGENES SELECCIONADAS
     * ================================================================ */
    private function deleteRequestedImages(SecurityInvestigation $investigation, Request $request): array {
        $ids = $request->input('imagenes_eliminar', []);

        if (!is_array($ids) || empty($ids)) {
            return [];
        }

        $filesToDelete = [];

        // Solo permite eliminar imágenes que pertenecen a esta investigación.
        $images = $investigation->images()
            ->whereIn('id', $ids)
            ->get();

        foreach ($images as $image) {
            if ($image->ruta) {
                $filesToDelete[] = $image->ruta;
            }

            $image->delete();
        }

        return $filesToDelete;
    }

    /* ================================================================
     | ELIMINAR ARCHIVOS FÍSICOS
     * ================================================================ */
    private function deletePhysicalFiles(array $paths): void
    {
        foreach (array_unique(array_filter($paths)) as $path) {
            // Eliminar del disco nuevo.
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // También limpiar archivos antiguos guardados en local.
            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
        }
    }

    /* ================================================================
    | GENERAR PDF DE INVESTIGACIÓN
    * ================================================================ */
    public function pdf($id)
    {
        $investigation = SecurityInvestigation::with([
            'actions',
            'team',
            'images',
            'finalizedBy',
            'securityFlashAlert',
        ])->findOrFail($id);

        /*------------------------------------------------------------------------
        | LOGO
        |------------------------------------------------------------------------*/
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);

        /*-----------------------------------------------------------------------
        | IMÁGENES DE LA INVESTIGACIÓN
        |------------------------------------------------------------------------*/
        $imagenes = $investigation->images
            ->sortBy('orden')
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'tipo' => $image->tipo,
                    'nombre' => $image->nombre_original,
                    'orden' => $image->orden,
                    'ruta' => $image->ruta,
                    'pdf_image' => $this->getStorageImageBase64($image->ruta),
                ];
            })
            ->filter(function ($image) {
                return !empty($image['pdf_image']);
            })
            ->values();

        /*-------------------------------------------------------------------------
        | CLASIFICACIONES
        |--------------------------------------------------------------------------*/
        $clasificaciones = [];

        foreach ($this->classificationFields as $field) {
            $valores = $investigation->{$field} ?? [];
            if (is_string($valores)) {
                $valores = json_decode($valores, true) ?? [];
            }
            $clasificaciones[$field] = is_array($valores) ? $valores : [];
        }

        /*-------------------------------------------------------------------------
        | EQUIPO Y FIRMAS
        |--------------------------------------------------------------------------*/
        $equipo = $investigation->team
            ->sortBy('orden')
            ->map(function ($member) {
                return [
                    'puesto' => $member->puesto,
                    'nombre' => $member->nombre,
                    'firma' => $member->firma_ruta ? $this->getStorageImageBase64($member->firma_ruta) : null,
                    'orden' => $member->orden,
                ];
            })
            ->values();

        /*-----------------------------------------------------------------------
        | ACCIONES INMEDIATAS
        |-------------------------------------------------------------------------*/
        $accionesInmediatas = $investigation->actions
            ->where('tipo', 'inmediata')
            ->sortBy('numero')
            ->values();

        /*------------------------------------------------------------------------
        | ACCIONES PARA EVITAR REPETICIÓN
        |------------------------------------------------------------------------*/
        $accionesRepeticion = $investigation->actions
            ->where('tipo', 'repeticion')
            ->sortBy('numero')
            ->values();

        /*-----------------------------------------------------------------------
        | GENERAR PDF
        |------------------------------------------------------------------------*/
        $pdf = Pdf::loadView(
            'F-07-38 ALERTA DE SEGURIDAD REPORTE DE INVESTIGACION DE ACCIDENTES',
            [
                'investigation' => $investigation,
                'clasificaciones' => $clasificaciones,
                'imagenes' => $imagenes,
                'equipo' => $equipo,
                'accionesInmediatas' => $accionesInmediatas,
                'accionesRepeticion' => $accionesRepeticion,
                'logoImage' => $logoImage,
            ]
        );

        $pdf->setPaper('letter', 'portrait');

        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
        ]);

        $nombre = 'Investigacion_' . ($investigation->folio ?: $investigation->id) . '.pdf';

        return $pdf->stream($nombre);
    }

    private function getImageBase64($path)
    {
        if (!$path || !file_exists($path)) {
            return null;
        }

        $file = file_get_contents($path);

        if ($file === false) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'webp' => 'image/webp',
        ];

        $mime = $mimeTypes[$extension] ?? 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode($file);
    }
    
    private function getStorageImageBase64($path)
    {
        if (empty($path)) {
            return null;
        }

        try {

            // 1. Buscar en los discos de Laravel
            foreach (['public', 'local'] as $disk) {

                $storage = Storage::disk($disk);

                if ($storage->exists($path)) {

                    $file = $storage->get($path);
                    $mime = $storage->mimeType($path);

                    if ($file !== false) {

                        if (!$mime) {
                            $mime = 'image/jpeg';
                        }

                        return 'data:' . $mime . ';base64,' .
                            base64_encode($file);
                    }
                }
            }

            // 2. Buscar directamente en public/storage
            $publicStoragePath = public_path('storage/' . $path);

            if (file_exists($publicStoragePath)) {

                $file = file_get_contents($publicStoragePath);
                $mime = mime_content_type($publicStoragePath);

                if ($file !== false) {

                    return 'data:' . ($mime ?: 'image/jpeg') . ';base64,' .
                        base64_encode($file);
                }
            }

            // 3. Buscar directamente dentro de public
            $publicPath = public_path($path);

            if (file_exists($publicPath)) {

                $file = file_get_contents($publicPath);
                $mime = mime_content_type($publicPath);

                if ($file !== false) {

                    return 'data:' . ($mime ?: 'image/jpeg') . ';base64,' .
                        base64_encode($file);
                }
            }

        } catch (Throwable $e) {

            report($e);
        }

        \Log::warning('No se pudo cargar imagen para PDF', [
            'ruta' => $path,
        ]);

        return null;
    }

}
