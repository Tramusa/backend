<?php

namespace App\Console\Commands;

use App\Models\Earrings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerarPendiente extends Command
{
    protected $signature = 'generar:pendiente';

    protected $description = 'Genera un registro de la tabla programaciones a la tabla pendientes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $programaciones = DB::table('programs')->where('date', date('Y-m-d'))->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($programaciones as $programacion) {
            if ($programacion->type_unit == 3) {
                $unit = DB::table('dollys')->select('id')->where('no_seriously', $programacion->unit)->first();
            } else {
                $unit = DB::table($tablas[$programacion->type_unit])->select('id')->where('no_economic', $programacion->unit)->first();
            } 
            Earrings::create([
                'unit' => $unit->id, 
                'type' => $programacion->type_unit,
                'description' => $programacion->description.' ('.$programacion->type.')',
            ]);
        }

        $this->info('Registros de programaciones generados exitosamente en la tabla pendientes.');
    }
}