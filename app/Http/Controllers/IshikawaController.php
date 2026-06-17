<?php

namespace App\Http\Controllers;

use App\Models\Ishikawa;
use App\Models\IshikawaCause;
use App\Models\NonConformity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IshikawaController extends Controller
{
    public function show($id)
    {
        $ishikawa = Ishikawa::with('causes')
            ->where('non_conformity_id', $id)
            ->first();

        if (!$ishikawa) {

            return response()->json([

                'maquinaria' => [],

                'personas' => [],

                'metodo' => [],

                'materiales' => [],

            ]);

        }

        return response()->json([

            'maquinaria' => $ishikawa->causes
                ->where('category', 'maquinaria')
                ->pluck('description')
                ->values(),

            'personas' => $ishikawa->causes
                ->where('category', 'personas')
                ->pluck('description')
                ->values(),

            'metodo' => $ishikawa->causes
                ->where('category', 'metodo')
                ->pluck('description')
                ->values(),

            'materiales' => $ishikawa->causes
                ->where('category', 'materiales')
                ->pluck('description')
                ->values(),

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'non_conformity_id' => 'required|exists:non_conformities,id',
            'finish' => 'nullable|boolean',
            'maquinaria' => 'nullable|array',
            'personas' => 'nullable|array',
            'metodo' => 'nullable|array',
            'materiales' => 'nullable|array',
        ]);

        $nonConformity = NonConformity::findOrFail($data['non_conformity_id'] );

        DB::transaction(function () use (
            $data,
            $nonConformity
        ) {
            $ishikawa = Ishikawa::firstOrCreate([
                'non_conformity_id' => $nonConformity->id,
            ]);

            /*---------------- Elimina las causas anteriores --------------------*/
            $ishikawa->causes()->delete();

            /*---------------- Guarda las nuevas ------------------------*/
            foreach ( ['maquinaria', 'personas', 'metodo', 'materiales', ] as $category) {
                
                foreach ($data[$category] ?? [] as $cause) {

                    if (trim($cause) == '') {
                        continue;
                    }

                    $ishikawa->causes()->create([
                        'category' => $category,
                        'description' => $cause,
                    ]);

                }

            }

            /* ------------------- Si finaliza cambia el status -------------------*/
            if (!empty($data['finish'])) {
                $nonConformity->update(['status' => 'relation', ]);
            }

        });

        return response()->json([
            'message' => !empty($data['finish'])
                ? 'Diagrama de Ishikawa finalizado correctamente.'
                : 'Diagrama de Ishikawa guardado correctamente.',
        ]);
    }

}