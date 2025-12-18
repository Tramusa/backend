<?php

namespace App\Console\Commands;

use App\Models\Earrings;
use App\Models\ProgramsMttoVehicles;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateFailsProgramsMtto extends Command
{
    protected $signature = 'generate:failsPrograms';

    protected $description = 'Genera las fallas del programa de mantenimiento de vehiculos';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        $today = Carbon::today();
        $programs = ProgramsMttoVehicles::all();

        foreach ($programs as $program) {

            $start = Carbon::parse($program->start_date);

            $next = $start->copy();

            do {
                $next = match ($program->periodicity) {
                    'Semanal'       => $next->addWeek(),
                    'Quincenal'     => $next->addWeeks(2),
                    'Mensual'       => $next->addMonth(),
                    'Bimestral'     => $next->addMonths(2),
                    'Trimestral'    => $next->addMonths(3),
                    'Cuatrimestral' => $next->addMonths(4),
                    'Semestral'     => $next->addMonths(6),
                    'Anual'         => $next->addYear(),
                };
            } while ($next->lessThan($today));

            if ($next->isSameWeek($today)) {
                $this->generateFailure($program);
            }
        }
    }

    // Función para generar la falla para una actividad específica
    private function generateFailure($Program)
    {      
        // Verificar si la descripción ya existe en las FALLAS registrados
        $existingEarring = Earrings::where('description', 'like', '%' . $Program['activity'] . '%')
                            ->where('status', 1)
                            ->where('type', $Program['type'])
                            ->where('unit', $Program['unit'])
                            ->first();

        if (!$existingEarring) {//SI NO EXISTE
            $data = [
                'unit' => $Program['unit'],
                'type' => $Program['type'],
                'description' => $Program['activity'],
                'type_mtto' => 'Preventivo',
            ];
            Earrings::create($data);//CREAMOS LA FALLA
        }
    }
}
