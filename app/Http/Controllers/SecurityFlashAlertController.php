<?php

namespace App\Http\Controllers;

use App\Models\SecurityFlashAlert;
use App\Models\SecurityFlashAlertImage;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class SecurityFlashAlertController extends Controller
{
    public function index(Request $request)
    {
        $query = SecurityFlashAlert::with('images');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                    ->orWhere('area_lugar', 'like', "%{$search}%")
                    ->orWhere('departamento', 'like', "%{$search}%")
                    ->orWhere('unidad', 'like', "%{$search}%")
                    ->orWhere('operador', 'like', "%{$search}%")
                    ->orWhere('reportado_por', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('clasificacion')) {
            $query->where('clasificacion_actual', $request->clasificacion);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $alerts = $query
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json($alerts);
    }

    public function show($id)
    {
        $alert = SecurityFlashAlert::with('images')->findOrFail($id);

        $alert->images->transform(function ($image) {
            $image->url = Storage::disk('public')->url($image->imagen);
            return $image;
        });

        return response()->json(['data' => $alert,]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'folio' => ['required', 'string', 'max:50', 'unique:security_flash_alerts,folio'],
            'area_lugar' => 'nullable|string|max:500',
            'fecha' => 'nullable|date',
            'hora' => 'nullable|date_format:H:i',
            'departamento' => 'nullable|string|max:255',
            'clasificacion_actual' => ['nullable', 'string',
                Rule::in([
                    'Daño a equipo',
                    'Incidente potencial',
                    'Sin incapacidad',
                    'Incapacidad temporal',
                    'Incapacidad permanente parcial',
                    'Incapacidad permanente total',
                    'Fatalidad',
                ]),
            ],

            'unidad' => 'nullable|string|max:100',
            'operador' => 'nullable|string|max:255',

            'afectado' => 'nullable|string|max:255',
            'puesto_afectado' => 'nullable|string|max:255',
            'supervisor_monitor' => 'nullable|string|max:255',

            'descripcion' => 'nullable|string',

            'anexos' => 'nullable|string',
            'acciones_repeticion' => 'nullable|string',
            'investigacion' => 'nullable|string',

            'reportado_por' => 'nullable|string|max:255',

            /*----------------------  IMÁGENES -------------------- */
            'evidencia' => 'nullable|array|max:3',
            'evidencia.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240',],

            'anexos_imagenes' => 'nullable|array|max:2',
            'anexos_imagenes.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240',],

            'webfleet' => 'nullable|array|max:1',
            'webfleet.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240',],
        ]);

        $alert = DB::transaction(function () use ($validated, $request) 
        {
            // separamos las imágenes de los campos normales
            $alertData = collect($validated)
                ->except([
                    'evidencia',
                    'anexos_imagenes',
                    'webfleet',
                ])
                ->all();


            $alert = new SecurityFlashAlert();
            $alert->fill($alertData);
            $alert->status = 'draft';
            $alert->created_by = Auth::id();
            $alert->save();

            ///  Guardar imágenes 
            $this->saveImages($alert, $request->file('evidencia', []), 'evidencia');
            $this->saveImages($alert, $request->file('anexos_imagenes', []), 'anexo');
            $this->saveImages($alert, $request->file('webfleet', []), 'webfleet');

            return $alert->load('images');
        });


        return response()->json(['message' => 'Alerta FLASH creada correctamente.', 'data' => $alert,], 201);
    }

    public function update(Request $request, $id)
    {
        $alert = SecurityFlashAlert::find($id);

        if (!$alert) {
            return response()->json(['message' => 'Flash no encontrado'], 404);
        }

        if ($alert->status === 'finalized') {
            return response()->json(['message' => 'No se puede editar un flash finalizado'], 403);
        }

        $validated = $request->validate([
            'folio' => ['required', 'string',
                Rule::unique('security_flash_alerts', 'folio')->ignore($alert->id),
            ],

            'area_lugar' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
            'hora' => 'nullable',
            'departamento' => 'nullable|string|max:255',
            'clasificacion_actual' => 'nullable|string|max:255',
            'unidad' => 'nullable|string|max:255',
            'operador' => 'nullable|string|max:255',
            'afectado' => 'nullable|string|max:255',
            'puesto_afectado' => 'nullable|string|max:255',
            'supervisor_monitor' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'anexos' => 'nullable|string',
            'acciones_repeticion' => 'nullable|string',
            'investigacion' => 'nullable|string',
            'reportado_por' => 'nullable|string|max:255',

            // Imágenes nuevas
            'evidencia' => 'nullable|array|max:3',
            'evidencia.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

            'anexos_imagenes' => 'nullable|array|max:2',
            'anexos_imagenes.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

            'webfleet' => 'nullable|array|max:1',
            'webfleet.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',

            // Imágenes existentes que el usuario quitó con X
            'imagenes_eliminar' => 'nullable|array',
            'imagenes_eliminar.*' => ['integer', 'exists:security_flash_alert_images,id',],
        ]);

        /*----------------------------------------------------------------------
        | Datos principales
        |---------------------------------------------------------------------*/
        $alertData = collect($validated)
            ->except([
                'evidencia',
                'anexos_imagenes',
                'webfleet',
                'imagenes_eliminar',
            ])
            ->all();

        $alert->update($alertData);

        /*-----------------------------------------------------------------------
        | ELIMINAR SOLO LAS IMÁGENES MARCADAS CON X
        |-------------------------------------------------------------------------*/
        if (!empty($validated['imagenes_eliminar'])) {
            $imagesToDelete = SecurityFlashAlertImage::where('security_flash_alert_id', $alert->id)
            ->whereIn('id', $validated['imagenes_eliminar'])
            ->get();

            foreach ($imagesToDelete as $image) {
                if ($image->imagen && Storage::disk('public')->exists($image->imagen)) {
                    Storage::disk('public')->delete($image->imagen);
                }

                $image->delete();
            }
        }

        /*----------------------------------------------------------------------
        | AGREGAR NUEVAS IMÁGENES
        |--------------------------------------------------------------------------
        | IMPORTANTE:
        | Aquí NO borramos las imágenes existentes.*/
        if ($request->hasFile('evidencia')) {
            $this->saveImages($alert, $request->file('evidencia'), 'evidencia');
        }

        if ($request->hasFile('anexos_imagenes')) {
            $this->saveImages($alert, $request->file('anexos_imagenes'), 'anexo');
        }

        if ($request->hasFile('webfleet')) {
            $this->saveImages($alert, $request->file('webfleet'), 'webfleet');
        }

        return response()->json(['message' => 'Flash actualizado correctamente', 'data' => $alert->load('images'),]);
    }

    public function finalize($id)
    {
        $alert = SecurityFlashAlert::findOrFail($id);

        if ($alert->status === 'finalized') {
            return response()->json(['message' => 'La alerta ya se encuentra finalizada.'], 422);
        }

        /*---------------------------------------------------------------------
        | Validaciones mínimas
        |-----------------------------------------------------------------------*/
        $errors = [];

        if (!$alert->area_lugar) {
            $errors['area_lugar'] = ['El área/lugar es obligatorio.'];
        }

        if (!$alert->fecha) {
            $errors['fecha'] = ['La fecha es obligatoria.'];
        }

        if (!$alert->clasificacion_actual) {
            $errors['clasificacion_actual'] = ['La clasificación es obligatoria.'];
        }

        if (!$alert->descripcion) {
            $errors['descripcion'] = ['La descripción es obligatoria.'];
        }

        if (count($errors) > 0) {
            return response()->json([
                'message' => 'Completa la información obligatoria antes de finalizar.', 'errors' => $errors,
            ], 422);
        }

        $alert->status = 'finalized';
        $alert->finalized_by = Auth::id();
        $alert->finalized_at = now();
        $alert->save();

        return response()->json([
            'message' =>'Alerta FLASH finalizada correctamente.',
            'data' => $alert->load('images'),
        ]);
    }

    public function destroy($id)
    {
        $alert = SecurityFlashAlert::findOrFail($id);

        if ($alert->status === 'finalized') {
            return response()->json([
                'message' => 'No se puede eliminar una alerta finalizada.'
            ], 422);
        }

        // Eliminar archivos físicos
        foreach ($alert->images as $image) {
            if ( $image->imagen && Storage::disk('public')->exists($image->imagen)) {
                Storage::disk('public')->delete($image->imagen);
            }
        }

        $alert->delete();

        return response()->json(['message' => 'Alerta eliminada correctamente.']);
    }

    /*-------------------------------------------------------------------------
    | GUARDAR IMÁGENES NORMALIZADAS
    |------------------------------------------------------------------------*/
    private function saveImages($alert, array $files, string $tipo)
    {
        $dimensions = [
            'evidencia' => [1000, 1000],
            'anexo'     => [1400, 900],
            'webfleet'  => [1600, 900],
        ];

        [$width, $height] = $dimensions[$tipo];

        $ultimoOrden = SecurityFlashAlertImage::where('security_flash_alert_id', $alert->id)
        ->where('tipo', $tipo)
        ->max('orden') ?? 0;

        foreach ($files as $file) {

            /*---------------------------------------------------------------------
            | CARGAR IMAGEN
            |-----------------------------------------------------------------------*/
            $image = Image::make($file->getRealPath());
            /*----------------------------------------------------------------------
            | REDIMENSIONAR EXACTAMENTE
            |--------------------------------------------------------------------------
            | NO conserva proporción.
            | NO recorta.
            | NO agrega fondo.
            |
            | La imagen queda exactamente:            |
            | evidencia → 1000 x 1000
            | anexo     → 1400 x 900
            | webfleet  → 1600 x 900  */
            $image->resize($width, $height);

            /*----------------------------------------------------------------------
            | COMPRESIÓN
            |----------------------------------------------------------------------*/
            $originalSize = $file->getSize();
            $maxBytes = 2 * 1024 * 1024;
            /* <= 2 MB → máxima calidad
            |  > 2 MB  → comenzar en 85  */

            $quality = $originalSize <= $maxBytes ? 100 : 85;
            $encoded = $image->encode('jpg', $quality);
            /*------------------------------------------------------------------------
            | REDUCIR CALIDAD SI SUPERA 2 MB
            |-------------------------------------------------------------------------*/
            while (strlen($encoded) > $maxBytes && $quality > 40) {
                $quality -= 5;
                $encoded = $image->encode('jpg', $quality);
            }
            /*-----------------------------------------------------------------------
            | GUARDAR ARCHIVO
            |------------------------------------------------------------------------*/
            $filename = uniqid('flash_', true) . '.jpg';
            $path = "security-flash/{$alert->id}/{$tipo}/{$filename}";
            Storage::disk('public')->put($path, $encoded);
            /*----------------------------------------------------------------------
            | GUARDAR REGISTRO
            |----------------------------------------------------------------------*/
            $ultimoOrden++;
            SecurityFlashAlertImage::create([
                'security_flash_alert_id' => $alert->id,
                'tipo' => $tipo,
                'imagen' => $path,
                'orden' => $ultimoOrden,
            ]);
        }
    }
    
    /*---------------------------------------------------------------------
    | ELIMINAR IMÁGENES DE UN TIPO
    |-----------------------------------------------------------------------*/
    private function deleteImagesByType(SecurityFlashAlert $alert, string $tipo) 
    {
        $images = $alert->images()
            ->where('tipo', $tipo)
            ->get();

        foreach ($images as $image) {

            if ($image->imagen && Storage::disk('public')->exists($image->imagen)) {
                Storage::disk('public')->delete($image->imagen);
            }

            $image->delete();
        }
    }

    
    private function PDF($id)
    {
        // ============== OBTENER ALERTA ========================
        $alert = SecurityFlashAlert::with(['images' => function ($query) {
                $query->orderBy('tipo')
                    ->orderBy('orden');
            }
        ])->findOrFail($id);

        // ================== LOGO ==========================
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);

        // ================= PREPARAR IMÁGENES DEL FLASH ===========
        $alert->images->transform(function ($image) {
            if ($image->imagen) {
                $image->pdf_image = $this->getStorageImageBase64($image->imagen);
            } else {
                $image->pdf_image = null;
            }
            return $image;
        });

        // ===================  DATOS PARA EL BLADE =======================
        $data = [
            'logoImage' => $logoImage,
            'alert'     => $alert,
        ];

        // ==========  GENERAR HTML ===============================
        $html = view('F-07-23 ALERTA DE SEGURIDAD FLASH', $data)->render();
        // =============== DOMPDF ==============================
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }


    public function generarPDF($id)
    {
        $pdfContent = $this->PDF($id);

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf');
    }

    private function getImageBase64($path)
    {
        // 1️⃣ Si viene una ruta absoluta (public_path)
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        // 2️⃣ Si viene una ruta de Storage (public/...)
        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        return null;
    }

    private function getStorageImageBase64($path)
    {
        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            return null;
        }

        $file = file_get_contents($fullPath);

        $extension = strtolower(
            pathinfo($fullPath, PATHINFO_EXTENSION)
        );

        $mimeTypes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'webp' => 'image/webp',
        ];

        $mime = $mimeTypes[$extension] ?? 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode($file);
    }
}