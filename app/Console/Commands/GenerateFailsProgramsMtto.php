<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Earrings;
use App\Models\ProgramsMttoVehicleSchedule;

class GenerateFailsProgramsMtto extends Command
{
    protected $signature = 'generate:failsPrograms';
    protected $description = 'Genera fallas preventivas según cronograma semanal';

    public function handle()
    {
        $this->info('⏳ Generando fallas preventivas por cronograma...');

        $today = Carbon::today();
        $currentWeek = $today->isoWeek();
        $currentYear = $today->isoWeekYear();

        $this->info("📅 Semana {$currentWeek} | Año {$currentYear}");

        // 1️⃣ Buscar cronogramas que coincidan con la semana actual
        $schedules = ProgramsMttoVehicleSchedule::where('year', $currentYear)
            ->where('week', $currentWeek)
            ->with('program')
            ->get();

        foreach ($schedules as $schedule) {

            $program = $schedule->program;

            if (!$program) {
                continue;
            }

            // 2️⃣ Validar actividad activa
            if ($program->active != 1) {
                continue;
            }

            //3️⃣ Validar unidad activa
            $unitStatus = DB::table('units_all')
                ->where('unit_id', $program->unit)
                ->where('type', $program->type)
                ->value('status');

            if ($unitStatus === 'disable') {
                continue;
            }

            //4️⃣ Evitar duplicados
            $description = trim(mb_strtolower($program->activity));

            $exists = Earrings::where('unit', $program->unit)
                ->where('type', $program->type)
                ->where('schedule_id', $schedule->id)
                ->whereRaw('LOWER(TRIM(description)) = ?', [$description])
                ->exists();

            if ($exists) {
                continue;
            }

            //5️⃣ Crear falla preventiva
            Earrings::create([
                'unit'        => $program->unit,
                'type'        => $program->type,
                'description' => $program->activity,
                'type_mtto'   => 'Preventivo',
                'status'      => 1,
                'schedule_id' => $schedule->id,
            ]);

            $this->info("✅ Falla creada → {$program->unit} | {$program->activity}");
        }

        $this->info('🏁 Proceso finalizado');
        return Command::SUCCESS;
    }
}
