<?php

namespace App\Console\Commands;

use App\Models\ExpirationUnits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
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

        foreach ($tables as $table) {
            $units = DB::table($table)->get();
            foreach ($units as $unit){
                if (in_array($table, ['tractocamiones', 'tortons', 'sprinters', 'utilitarios'])) {
                    // Código para las tablas 'tractocamiones', 'tortons', 'sprinters' y 'utilitarios'
                    if ($unit->expiration_placas < $nextMonth AND $unit->expiration_placas != '0000-00-00') {
                        $description = 'Pronto vencerá las placas el día: ' . date('d/m/Y', strtotime($unit->expiration_placas));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_placas,
                            ]);
                        }
                    } elseif ( $unit->expiration_circulation < $nextMonth AND $unit->expiration_circulation != '0000-00-00') {
                        $description = 'Pronto vencerá la tarjeta de circulación el día: ' . date('d/m/Y', strtotime($unit->expiration_circulation));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_circulation,
                            ]);
                        }
                    } elseif ( $unit->expiration_tenure < $nextMonth AND $unit->expiration_tenure != '0000-00-00') {
                        $description = 'Pronto vencerá la tenencia estatal el día: ' . date('d/m/Y', strtotime($unit->expiration_tenure));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_tenure,
                            ]);
                        }
                    } elseif ( $unit->safe_expiration < $nextMonth AND $unit->safe_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->safe_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->safe_expiration,
                            ]);
                        }
                    } elseif ( $unit->expiration_receipt < $nextMonth AND $unit->expiration_receipt != '0000-00-00') {
                        $description = 'Pronto vencerá el recibo poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->expiration_receipt));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_receipt,
                            ]);
                        }
                    } elseif ( $unit->physical_expiration < $nextMonth AND $unit->physical_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la fisico-mecanica el día: ' . date('d/m/Y', strtotime($unit->physical_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->physical_expiration,
                            ]);
                        }
                    } elseif ( $unit->contaminant_expiration < $nextMonth AND $unit->contaminant_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá el dictamen baja emisión contaminantes el día: ' . date('d/m/Y', strtotime($unit->contaminant_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->contaminant_expiration,
                            ]);
                        }
                    }
                } elseif (in_array($table, ['volteos', 'toneles'])) {
                    // Código para las tablas 'volteos' y 'toneles'
                    if ($unit->expiration_placas < $nextMonth AND $unit->expiration_placas != '0000-00-00') {
                        $description = 'Pronto vencerá las placas el día: ' . date('d/m/Y', strtotime($unit->expiration_placas));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_placas,
                            ]);
                        }
                    } elseif ( $unit->expiration_circulation < $nextMonth AND $unit->expiration_circulation != '0000-00-00') {
                        $description = 'Pronto vencerá la tarjeta de circulación el día: ' . date('d/m/Y', strtotime($unit->expiration_circulation));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_circulation,
                            ]);
                        }
                    } elseif ( $unit->safe_expiration < $nextMonth AND $unit->safe_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->safe_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->safe_expiration,
                            ]);
                        }
                    } elseif ( $unit->expiration_receipt < $nextMonth AND $unit->expiration_receipt != '0000-00-00') {
                        $description = 'Pronto vencerá el recibo poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->expiration_receipt));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_receipt,
                            ]);
                        }
                    } elseif ( $unit->physical_expiration < $nextMonth AND $unit->physical_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la fisico-mecanica el día: ' . date('d/m/Y', strtotime($unit->physical_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->physical_expiration,
                            ]);
                        }
                    } elseif ( $unit->contaminant_expiration < $nextMonth AND $unit->contaminant_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá el dictamen baja emisión contaminantes el día: ' . date('d/m/Y', strtotime($unit->contaminant_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->contaminant_expiration,
                            ]);
                        }
                    }
                } else {
                    // Código para otras tablas no mencionadas en los casos anteriores
                    if ($unit->expiration_placas < $nextMonth AND $unit->expiration_placas != '0000-00-00') {
                        $description = 'Pronto vencerá las placas el día: ' . date('d/m/Y', strtotime($unit->expiration_placas));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_placas,
                            ]);
                        }
                    } elseif ( $unit->expiration_circulation < $nextMonth AND $unit->expiration_circulation != '0000-00-00') {
                        $description = 'Pronto vencerá la tarjeta de circulación el día: ' . date('d/m/Y', strtotime($unit->expiration_circulation));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_circulation,
                            ]);
                        }
                    } elseif ( $unit->expiration_tenure < $nextMonth AND $unit->expiration_placas != '0000-00-00') {
                        $description = 'Pronto vencerá la tenencia estatal el día: ' . date('d/m/Y', strtotime($unit->expiration_tenure));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_tenure,
                            ]);
                        }
                    } elseif ( $unit->safe_expiration < $nextMonth AND $unit->safe_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->safe_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->safe_expiration,
                            ]);
                        }
                    } elseif ( $unit->expiration_receipt < $nextMonth AND $unit->expiration_receipt != '0000-00-00') {
                        $description = 'Pronto vencerá el recibo poliza de seguro el día: ' . date('d/m/Y', strtotime($unit->expiration_receipt));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->expiration_receipt,
                            ]);
                        }
                    } elseif ( $unit->physical_expiration < $nextMonth AND $unit->physical_expiration != '0000-00-00') {
                        $description = 'Pronto vencerá la fisico-mecanica el día: ' . date('d/m/Y', strtotime($unit->physical_expiration));
                        // Verificar si ya existe un registro con los mismos valores
                        $existingRecord = ExpirationUnits::where([
                            'type_unit' => $table,
                            'description' => $description,
                            'unit' => $unit->id,
                            'status' => 1,
                        ])->first();
                        // Si no existe un registro con los mismos valores, crea uno nuevo
                        if (!$existingRecord) {
                            ExpirationUnits::create([
                                'type_unit' => $table,
                                'unit' => $unit->id, 
                                'description' => $description,
                                'date_expiration' => $unit->physical_expiration,
                            ]);
                        }
                    }
                }
            }
        }
        $this->info('Registros de vencimientos proximos generados exitosamente.');
    }
}
