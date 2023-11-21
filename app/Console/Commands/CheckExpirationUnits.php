<?php

namespace App\Console\Commands;

use App\Models\ExpirationUnits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckExpirationUnits extends Command
{
    protected $signature = 'check:expirationUnits';

    protected $description = 'Verifica la vigencia de los archivos de las unidades';

    public function handle()
    {
        $Today = date('Y-m-d');
        $nextMonth = date('Y-m-d', strtotime('+1 month', strtotime($Today))); // Sumar un mes a la fecha actual
        
        $tables = ['tractocamiones', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios'];
        $type_expiration = [
            'expiration_placas' => 'las placas',
            'expiration_circulation' => 'la tarjeta de circulación',
            'safe_expiration' => 'la póliza de seguro',
            'expiration_receipt' => 'el recibo de la póliza de seguro',
            'physical_expiration' => 'la físico-mecanica',
            'expiration_tenure' => 'la tenencia estatal',
            'contaminant_expiration' => 'el dictamen de baja emisión de contaminantes',
        ];

        foreach ($tables as $table) {//RECORREMOS CADA TIPO DE UNIDAD
            $units = DB::table($table)->get();//TOMAMOS TODAS LAS UNIDADES DEL TIPO EN TURNO
            foreach ($units as $unit) {//RECORREMOS UNA A UNA LA UNIDAD
                foreach ($type_expiration as $field => $description) {//RECORREMOS UNO A UNO EL TIPO DE EXPIRACION VALORES SEPARADOS
                    // Verificar si el campo de expiración está  en la unidad
                    if (property_exists($unit, $field)) {
                        $expirationDate = $unit->$field;//ej:  $unit->expiration_placas
                        $type = $field;// ej: expiration_placas
                        if ($expirationDate != '0000-00-00' && $expirationDate < $nextMonth) {
                            $formattedDate = date('d/m/Y', strtotime($expirationDate));
                            $description = 'Pronto vencerá ' . $description . ' el día: ' . $formattedDate;

                            // Verificar si ya existe un registro con los mismos valores
                            $existingRecord = ExpirationUnits::where([
                                'type_unit' => $table,
                                'description' => $description,
                                'unit' => $unit->id,
                                'type_expiration' => $type,
                                'status' => 1,
                            ])->first();

                            // Si no existe un registro con los mismos valores, crea uno nuevo
                            if (!$existingRecord) {
                                ExpirationUnits::create([
                                    'type_unit' => $table,
                                    'unit' => $unit->id,
                                    'description' => $description,
                                    'type_expiration' => $type, 
                                    'date_expiration' => $expirationDate,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        $this->info('Registros de vencimientos próximos generados exitosamente.');
    }
}