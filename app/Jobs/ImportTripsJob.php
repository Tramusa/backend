<?php

namespace App\Jobs;

use App\Imports\TripsImport;
use App\Models\CECOs;
use App\Models\Customers;
use App\Models\PointsInterest;
use App\Models\Trips;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportTripsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {   
        $collection = Excel::toCollection(new TripsImport, $this->filePath);
        $skippedRows = [$collection[0][1]];

        foreach ($collection[0] as $index => $row) {            
            if ($index < 2) {
                continue;///SALTA LAS DOS PRIMERAS FILAS
            }
            if ($this->isValidRow($row)) {
                $customer = Customers::where('name', 'like', '%' . $row[3] . '%')->first();
                $CECO = CECOs::where('description', 'like', '%' . $row[4] . '%')->first();
                $origin = PointsInterest::where('name', 'like', '%' . $row[10] . '%')->first();
                $p_int = PointsInterest::where('name', 'like', '%' . $row[11] . '%')->first();
                $p_aut = PointsInterest::where('name', 'like', '%' . $row[12] . '%')->first();
                $destination = PointsInterest::where('name', 'like', '%' . $row[13] . '%')->first();
                $operator = User::whereRaw("CONCAT(name, ' ', a_paterno, ' ', a_materno) = ?", [$row[16]])->first();
                $status = ($row[22] == 'CANCELADO')? 3: 2;

                $CECO_id = $CECO ? $CECO->id : 0;
                $p_int_id = $p_int ? $p_int->id : 0;
                $p_aut_id = $p_aut ? $p_aut->id : 0;

                $totalSeconds = floatval($row[14]) * 24 * 60 * 60; // Convierte la fracción a segundos (24 horas * 60 minutos * 60 segundos)
                $hour = gmdate('H:i:s', $totalSeconds); // Convierte los segundos a un formato de tiempo (HH:MM:SS)
                $totalSecondsEnd = floatval($row[20]) * 24 * 60 * 60;
                $end_hour = gmdate('H:i:s', $totalSecondsEnd);
                // Convertir valores de fecha al formato adecuado (YYYY-MM-DD)
                $excelDate = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays(intval($row[15]) - 2);
                $date = $excelDate->format('Y-m-d');// Obtener el valor de fecha en formato numérico (por ejemplo, 45174)
                $excelDateEnd = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays(intval($row[21]) - 2);
                $end_date = $excelDateEnd->format('Y-m-d');

                if ($customer && $origin && $destination && $operator) {
                    $UnitTypes = ['tractocamiones' => 1, 'volteos' => 4, 'autobuses' => 7, 'sprinters' => 8, 'utilitarios' => 9];
                    
                    $no_economic = (strpos($row[1], '-') !== false) ? explode('-', $row[1])[0] : $row[1];

                    $unitFinded = $this->findUnit($UnitTypes, 'no_economic', $no_economic);
                    $Unit = $unitFinded['unit'];
                    $unitType = $unitFinded['unitType'];

                    if (empty($Unit)) {
                        $row[23] = 'ERROR: No se encontró el número económico de la unidad';
                        $skippedRows[] = $row;
                    } else {  
                        try {
                            // Crear un nuevo registro en la tabla Trips                        
                            $newTrip = Trips::create([
                                'id' => $row[0],
                                'customer' => $customer->id,
                                'ceco' => $CECO_id,
                                'name' => $row[5],
                                'mail' => $row[6],
                                'application_medium' => $row[7],
                                'phone' => $row[8],
                                'position' => $row[9],
                                'origin' => $origin->id,
                                'p_intermediate' => $p_int_id,
                                'p_authorized' => $p_aut_id,
                                'destination' => $destination->id,
                                'hour' => $hour,
                                'date' => $date,
                                'operator' => $operator->id,
                                'type' => $row[17],
                                'product' => $row[18],
                                'detaills' => $row[19],
                                'end_hour' => $end_hour,
                                'end_date' => $end_date,
                                'status' => $status,
                                'user' => 1,
                            ]);          
                            // Insertar en la tabla units__trips
                            $this->insertUnitTrips($newTrip->id, $Unit->id, $unitType, $row, $skippedRows); 

                            if (in_array($customer->prefijo, ['TC', 'TM']) && !empty($row[2])) {
                                $UnitTypes = ['volteos' => 4, 'toneles' => 5];
                                $unitFinded = $this->findUnit($UnitTypes, 'no_placas', $row[2]);
                                $Unit = $unitFinded['unit'];
                                $unitType = $unitFinded['unitType'];
                                if (empty($Unit)) {
                                    $row[23] = 'ERROR: No se encontró la placa de la unidad';
                                    $skippedRows[] = $row;
                                } else { 
                                    // Insertar en la tabla units__trips
                                    $this->insertUnitTrips($newTrip->id, $Unit->id, $unitType, $row, $skippedRows);
                                }
                            }
                        } catch (\Exception $e) {
                            $row[23] = 'ERROR:  AL INSERTAR EN TRIPS(Algun tipo de dato incorrecto)';
                            $skippedRows[] = $row; // Agrega la fila al array si la creación falla
                        }
                    }
                } else {
                    $missingFields = [];

                    if (empty($customer)) {
                        $missingFields[] = 'Cliente';
                    }
                    if (empty($origin)) {
                        $missingFields[] = 'Origen';
                    }
                    if (empty($destination)) {
                        $missingFields[] = 'Destino';
                    }
                    if (empty($operator)) {
                        $missingFields[] = 'Operador';
                    }
                    $row[23] = 'ERROR: NO SE ENCONTRO ALGUNA COLUMNA EN LA BD: (' . implode(', ', $missingFields) . ')';
                    $skippedRows[] = $row; // Agrega la fila al array si falta información
                }               
            }else{
                $row[23] = 'ERROR: ALGUNA COLUMNA OBLIGATORA ESTA VACIA';
                $skippedRows[] = $row; // Agrega la fila al array si falta información  
            }
        }
        $this->handleSkippedRows($skippedRows);  //LLAMAR FUNCION GENERAR EL EXCEL
    }

    private function isValidRow($row)
    {
        return !empty($row[1]) && !empty($row[3]) && !empty($row[10]) &&
               !empty($row[13]) && !empty($row[15]) && !empty($row[16]) && !empty($row[21]);
    }

    private function findUnit($UnitTypes, $column, $find) {
        $unitType = null;
        $unit = null;    
        foreach ($UnitTypes as $type => $typeNumber) {
            $unit = DB::table($type)->where($column, $find)->first();    
            if (!empty($unit)) {
                $unitType = $typeNumber;
                break;
            }
        }    
        return ['unit' => $unit, 'unitType' => $unitType];
    }

    private function insertUnitTrips($tripId, $id_unit, $unitType, &$row, &$skippedRows)
    {
        try {
            DB::table('units__trips')->insert([
                'trip' => $tripId,
                'type_unit' => $unitType,
                'unit' => $id_unit,
            ]);
        } catch (\Exception $e) {
            // Asignar el mensaje de error al índice 23 del arreglo $row
            $row[23] = $e->getMessage();
            $skippedRows[] = $row; // Agregar la fila al arreglo $skippedRows
        }
    }
    
    private function handleSkippedRows($skippedRows)
    {
        if (count($skippedRows) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $dataArray = $skippedRows[0]->toArray();

            $headers = array_keys($dataArray);
            
            // Agregar los encabezados a la primera fila
            foreach ($headers as $index => $header) {
                $col = chr(65 + $index);
                $sheet->setCellValue($col . '1', $header);
            }

            // Agregar datos de las filas con errores a partir de la fila 2
            $rowIndex = 2;
            foreach ($skippedRows as $row) {
                foreach ($row as $index => $value) {
                    $col = chr(65 + $index);
                    $sheet->setCellValue($col . $rowIndex, $value);
                }
                $rowIndex++;
            }

            // Guardar el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $fechaHoy = Carbon::now()->format('Y-m-d');
            $user = auth()->user();
            $fileName = "$user->name-errors-$fechaHoy.xlsx";
            $filePath = public_path("errors/$fileName"); // Ruta al directorio public/errors
            $writer->save($filePath);
        }
    }
}   