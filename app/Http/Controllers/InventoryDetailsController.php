<?php

namespace App\Http\Controllers;

use App\Models\InventoryDetails;
use Illuminate\Http\Request;

class InventoryDetailsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $inventoryId = $request->input('id_inventory');

            $details = InventoryDetails::where('id_inventory', $inventoryId)
                        ->with('product')
                        ->get();

            return response()->json($details);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ],500);
        }
    }

    public function show($id)
    {
        try {

            $details = InventoryDetails::where('id_inventory', $id)
                        ->with('product')
                        ->get();

            return response()->json($details);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ],500);
        }
    }
}
