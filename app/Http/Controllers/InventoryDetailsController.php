<?php

namespace App\Http\Controllers;

use App\Models\InventoryDetails;
use Illuminate\Http\Request;

class InventoryDetailsController extends Controller
{
    public function index(Request $request)
    {
        $inventoryId = $request->input('id_inventory');
    
        // Filtrar por id_inventory si se proporciona en la solicitud
        $details = InventoryDetails::where('id_inventory', $inventoryId)->with('product')->get();
    
        return response()->json($details);
    }
}
