<?php

namespace App\Console\Commands;

use App\Models\Autobuses;
use App\Models\Inspections;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tractocamiones;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Utilitarios;
use App\Models\Volteos;
use Illuminate\Support\Facades\DB;

class GenerateRevisions extends Command
{
    protected $signature = 'generate:revisions';

    protected $description = 'Genera las revisiones de documentos para unidades automáticamente';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->generateRevisions(Tractocamiones::class, 1);
        $this->generateRevisions(Volteos::class, 4);
        $this->generateRevisions(Toneles::class, 5);
        $this->generateRevisions(Autobuses::class, 7);
        $this->generateRevisions(Sprinters::class, 8);
        $this->generateRevisions(Utilitarios::class, 9);

        $this->info('Revisiones generadas exitosamente.');
    }

    private function generateRevisions($modelo, $idTipo)
    {
        $unidades = $modelo::all();
        foreach ($unidades as $unidad) {
            try {
                // Determina la tabla correspondiente según el tipo de unidad
                $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

                // Obtiene al responsable con el rol "Auxiliar Mantenimiento"
                $responsable = User::where('rol', 'LIKE', '%Auxiliar Mantenimiento%')->first();

                $data = [
                    'unit' => $unidad->id,
                    'type' => $idTipo,
                    'responsible' => $responsable->id, // Asigna el ID del responsable
                    'is' => 'documentos',
                ];

                $revision = new Inspections($data);
                $revision->save();

                // Actualiza el estado de la unidad a "inspection"
                $unitId = $unidad->id;
                DB::table($tablas[$idTipo])->where('id', $unitId)->update(['status' => 'inspection']);

            } catch (\Exception $e) {
                $this->error('Error al generar la revisión para la unidad ' . $unidad->id . ': ' . $e->getMessage());
            }
        }
    }
}