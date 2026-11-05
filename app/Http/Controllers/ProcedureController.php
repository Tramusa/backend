<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProcedureController extends Controller
{
    private function departamentos(): array
    {
        return config('procedures.departments', [
            'Mantenimiento',
            'Calidad',
            'Administración',
            'RRHH',
            'Seguridad',
            'Concentrado',
            'Combustible',
            'Personal',
        ]);
    }

    /**
     * LISTAR DOCUMENTOS
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentos()),
            ],
            'tipo_documento' => [
                'required',
                'string',
                'max:80',
            ],
            'buscar' => [
                'nullable',
                'string',
                'max:180',
            ],
        ]);

        $documentos = Procedure::query()
            ->where('departamento', $validated['departamento'])
            ->where('tipo_documento', $validated['tipo_documento'])
            ->when(!empty($validated['buscar']), function ($q) use ($validated) {
                $buscar = trim($validated['buscar']);

                $q->where(function ($sub) use ($buscar) {
                    $sub->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(15);

        return response()->json($documentos);
    }

    /**
     * REGISTRAR DOCUMENTO
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentos()),
            ],
            'tipo_documento' => [
                'required',
                'string',
                'max:80',
            ],
            'nombre' => [
                'required',
                'string',
                'max:180',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'archivo_pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);

        $ruta = $request->file('archivo_pdf')
            ->store('procedures', 'local');

        try {
            $documento = Procedure::create([
                'departamento' => $validated['departamento'],
                'tipo_documento' => $validated['tipo_documento'],
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'archivo_pdf' => $ruta,
                'user_id' => $request->user()->id,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($ruta);
            throw $e;
        }

        return response()->json([
            'message' => 'Documento registrado correctamente.',
            'procedure' => $documento,
        ], 201);
    }

    /**
     * CONSULTAR DOCUMENTO
     */
    public function show(string $id)
    {
        $documento = Procedure::with('user')->findOrFail($id);

        return response()->json($documento);
    }

    /**
     * ACTUALIZAR DOCUMENTO
     */
    public function update(Request $request, string $id)
    {
        $documento = Procedure::findOrFail($id);

        $validated = $request->validate([
            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentos()),
            ],
            'tipo_documento' => [
                'required',
                'string',
                'max:80',
            ],
            'nombre' => [
                'required',
                'string',
                'max:180',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'archivo_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);

        $rutaAnterior = $documento->archivo_pdf;
        $rutaNueva = null;

        if ($request->hasFile('archivo_pdf')) {
            $rutaNueva = $request->file('archivo_pdf')
                ->store('procedures', 'local');
        }

        try {
            $documento->departamento = $validated['departamento'];
            $documento->tipo_documento = $validated['tipo_documento'];
            $documento->nombre = $validated['nombre'];
            $documento->descripcion = $validated['descripcion'] ?? null;

            if ($rutaNueva) {
                $documento->archivo_pdf = $rutaNueva;
            }

            $documento->save();

        } catch (\Throwable $e) {
            if ($rutaNueva) {
                Storage::disk('local')->delete($rutaNueva);
            }

            throw $e;
        }

        if ($rutaNueva && $rutaAnterior) {
            Storage::disk('local')->delete($rutaAnterior);
        }

        return response()->json([
            'message' => 'Documento actualizado correctamente.',
            'procedure' => $documento->fresh(),
        ]);
    }

    /**
     * VISUALIZAR PDF
     */
    public function pdf(string $id)
    {
        $documento = Procedure::findOrFail($id);

        abort_unless(
            Storage::disk('local')->exists($documento->archivo_pdf),
            404,
            'El archivo PDF no existe.'
        );

        $nombre = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $documento->nombre
        );

        return response()->file(
            Storage::disk('local')->path($documento->archivo_pdf),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombre . '.pdf"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /**
     * ELIMINAR DOCUMENTO
     */
    public function destroy(string $id)
    {
        $documento = Procedure::findOrFail($id);

        $ruta = $documento->archivo_pdf;

        $documento->delete();

        if ($ruta) {
            Storage::disk('local')->delete($ruta);
        }

        return response()->json([
            'message' => 'Documento eliminado correctamente.',
        ]);
    }
}
