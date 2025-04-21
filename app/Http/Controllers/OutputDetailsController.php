<?php

namespace App\Http\Controllers;

use App\Models\OutputDetails;
use Illuminate\Http\Request;

class OutputDetailsController extends Controller
{
    public function show($id)
    {
        $details = OutputDetails::where('id_output', $id)
            ->with('product') // Carga la relación del producto
            ->get();
        
        return response()->json($details);
    }
}
