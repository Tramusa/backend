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
            'C&C',
            'Personal',
        ]);
    }

    /**
     * Listar procedimientos.
     * El departamento es opcional: si no se envía, muestra todos.
     */
    public function index(Request $request)
    {
        $request->validate([
            'departamento' => [
                'nullable',
                'string',
                Rule::in($this->departamentos()),
            ],
            'buscar' => ['nullable', 'string', 'max:180'],
        ]);

        $procedimientos = Procedure::query()
            ->when(
                $request->filled('departamento'),
                fn ($q) => $q->where(
                    'departamento',
                    $request->query('departamento')
                )
            )
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $buscar = trim($request->query('buscar'));

                $q->where(function ($sub) use ($buscar) {
                    $sub->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(15);

        return response()->json($procedimientos);
    }

    /**
     * Registrar procedimiento y PDF.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentos()),
            ],
            'nombre' => ['required', 'string', 'max:180'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'archivo_pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);

        $ruta = $request->file('archivo_pdf')->store(
            'procedures',
            'local'
        );

        try {
            $procedure = Procedure::create([
                'departamento' => $validated['departamento'],
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
            'message' => 'Procedimiento registrado correctamente.',
            'procedure' => $procedure,
        ], 201);
    }

    /**
     * Consultar un procedimiento.
     */
    public function show(string $id)
    {
        return response()->json(
            Procedure::findOrFail($id)
        );
    }

    /**
     * Editar datos, departamento o sustituir el PDF.
     */
    public function update(Request $request, string $id)
    {
        $procedure = Procedure::findOrFail($id);

        $validated = $request->validate([
            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentos()),
            ],
            'nombre' => ['required', 'string', 'max:180'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'archivo_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);

        $rutaAnterior = $procedure->archivo_pdf;
        $rutaNueva = null;

        if ($request->hasFile('archivo_pdf')) {
            $rutaNueva = $request->file('archivo_pdf')->store(
                'procedures',
                'local'
            );
        }

        try {
            $procedure->departamento = $validated['departamento'];
            $procedure->nombre = $validated['nombre'];
            $procedure->descripcion = $validated['descripcion'] ?? null;

            if ($rutaNueva) {
                $procedure->archivo_pdf = $rutaNueva;
            }

            $procedure->save();
        } catch (\Throwable $e) {
            if ($rutaNueva) {
                Storage::disk('local')->delete($rutaNueva);
            }

            throw $e;
        }

        if ($rutaNueva) {
            Storage::disk('local')->delete($rutaAnterior);
        }

        return response()->json([
            'message' => 'Procedimiento actualizado correctamente.',
            'procedure' => $procedure->fresh(),
        ]);
    }

    /**
     * Visualizar PDF.
     */
    public function pdf(string $id)
    {
        $procedure = Procedure::findOrFail($id);

        abort_unless(
            Storage::disk('local')->exists($procedure->archivo_pdf),
            404,
            'El archivo PDF no existe.'
        );

        $nombre = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $procedure->nombre
        );

        return response()->file(
            Storage::disk('local')->path($procedure->archivo_pdf),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombre . '.pdf"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /**
     * Eliminar registro y archivo PDF.
     */
    public function destroy(string $id)
    {
        $procedure = Procedure::findOrFail($id);
        $ruta = $procedure->archivo_pdf;

        $procedure->delete();

        Storage::disk('local')->delete($ruta);

        return response()->json([
            'message' => 'Procedimiento eliminado correctamente.',
        ]);
    }
}
