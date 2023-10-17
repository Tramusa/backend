<?php

namespace App\Imports;

use App\Models\Trips;
use Maatwebsite\Excel\Concerns\ToModel;

class TripsImport implements ToModel
{
    public function model(array $row)
    {
        return new Trips([
        ]);
    }
}
