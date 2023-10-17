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
use Laravel\Sail\Console\PublishCommand;
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
        $rowCount = 0; // Variable para contar las filas procesadas

        foreach ($collection[0] as $row) {  
            $rowCount++; // Incrementar el contador de filas         
    
            if ($rowCount > 2 && !empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]) && !empty($row[4]) && !empty($row[5]) && !empty($row[10]) && !empty($row[11]) && !empty($row[12]) && !empty($row[13])) {
                $customer = Customers::where('name', $row[3])->first();
                $CECO = CECOs::where('description', $row[4])->first();
                $origin = PointsInterest::where('name', $row[10])->first();
                $p_int = PointsInterest::where('name', $row[11])->first();
                $p_aut = PointsInterest::where('name', $row[12])->first();
                $destination = PointsInterest::where('name', $row[13])->first();
                $operator = User::whereRaw("CONCAT(name, ' ', a_paterno, ' ', a_materno) = ?", [$row[16]])->first();
                $status = ($row[22] == 'Cancelado')? 3: 2;

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
                    try {
                        // Crear un nuevo registro en la tabla Trips                        
                        Trips::create([
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
                    } catch (\Exception $e) {
                        $row[23] = 'ERROR:  AL INSERTAR (Algun tipo de dato incorrecto)';
                        $skippedRows[] = $row; // Agrega la fila al array si la creación falla
                    }
                } else {
                    $row[23] = 'ERROR: NO TIENE ALGUNA COLUMNA OBLIGATORA';
                    $skippedRows[] = $row; // Agrega la fila al array si falta información
                }
            }
        }
        //Logger($skippedRows);
        if (count($skippedRows) > 1) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $dataArray = $skippedRows[0]->toArray();

            $headers = array_keys($dataArray);
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }
            // Agregar datos de las filas con errores
            $rowIndex = 2;
            foreach ($skippedRows as $row) {
                $col = 'A';
                foreach ($row as $value) {
                    $sheet->setCellValue($col . $rowIndex, $value);
                    $col++;
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