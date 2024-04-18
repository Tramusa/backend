<?php

namespace App\Console\Commands;

use App\Models\Earrings;
use App\Models\ProgramsMttoVehicles;
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
        //CONSULTAR LAS ACTIVIDADES DE LA PROGRAMACION
        $Programs = ProgramsMttoVehicles::all(); 

        //SEMANA ACTUAL
        $currentWeek = date('W'); // Assuming you want the current week number

        //RECORRER UNA A UNA LAS ACTIVIDADES
        foreach ($Programs as $Program) {            

            // Obtener la periodicidad de la actividad
            $periodicity = $Program->periodicity;

            if ($periodicity === 'Anual') {
                $nextMaintenanceDate = $Program->start; // Return the same start value
            }else{
                $periodicitySums = [
                    'Quincenal' => 2,
                    'Mensual' => 4,
                    'Bimestral' => 8,
                    'Trimestral' => 12,
                    'Cuatrimestral' => 16,
                    'Semestral' => 24,
                ];

                // Calcular la próxima fecha de mantenimiento según la periodicidad
                $sum = $periodicitySums[$periodicity] ?? 0;
                $nextMaintenanceDate = $Program->start + $sum;

                while ($nextMaintenanceDate < $currentWeek) {                
                    $nextMaintenanceDate += $sum;
                }
            }
            // Comparar si la actividad está en la semana actual y generar falla si es así
            if ($nextMaintenanceDate == $currentWeek) {
                $this->generateFailure($Program);
            }
        }
        $this->info("Fallas de la semana $currentWeek generadas exitosamente.");
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
