<?php

namespace App\Http\Controllers;

use App\Models\EntryDetails;
use Illuminate\Http\Request;

class EntryDetailsController extends Controller
{
    public function show($id)
    {
        $details = EntryDetails::where('id_entry', $id)
            ->with('product') // Carga la relación del producto
            ->get();
        
        return response()->json($details);
    }
}
