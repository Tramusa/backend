<?php

namespace App\Console\Commands;

use App\Models\Earrings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerarPendiente extends Command
{
    protected $signature = 'generate:pending';

    protected $description = 'Genera un registro de la tabla programaciones a la tabla pendientes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $programaciones = DB::table('programs')->where('date', date('Y-m-d'))->get();
        $tables = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($programaciones as $programacion) {
            if ($programacion->type_unit == 3) {
                $unit = DB::table('dollys')->select('id')->where('no_seriously', $programacion->unit)->first();
            } else {
                $unit = DB::table($tables[$programacion->type_unit])->select('id')->where('no_economic', $programacion->unit)->first();
            } 

            // Verificar si ya existe un registro similar con status = 1
            $existingEarring = Earrings::where('unit', $unit->id)
                ->where('type', $programacion->type_unit)
                ->where('description', $programacion->description.' ('.$programacion->type.')')
                ->where('status', 1)
                ->first();

            if (!$existingEarring) {
                Earrings::create([
                    'unit' => $unit->id, 
                    'type' => $programacion->type_unit,
                    'description' => $programacion->description.' ('.$programacion->type.')',
                    'status' => 1, // Agrega el campo status al registro
                ]);
            }
        }

        $this->info('Registros de programaciones generados exitosamente en la tabla pendientes.');
    }
}