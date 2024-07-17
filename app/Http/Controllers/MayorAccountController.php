<?php

namespace App\Http\Controllers;

use App\Models\MayorAccount;
use Illuminate\Http\Request;

class MayorAccountController extends Controller
{
    public function index()
    {
        $cuentas = MayorAccount::all(); 
        return response()->json($cuentas); 
    }

    public function store(Request $request)
    {
        $cuenta = new MayorAccount($request->all());

        $cuenta->save();
        return response()->json(['message' => 'Cuenta registrada con exito'], 201);
    }

    public function show($id)
    {
        $cuenta = MayorAccount::find($id);
        return response()->json($cuenta);
    }

    public function update(Request $request, $id)
    {
        // Find the cuenta by ID, or return a 404 error if not found
        $cuenta = MayorAccount::findOrFail($id);

        // Update the cuenta with the provided data
        $cuenta->update($request->all());

        $cuentas = MayorAccount::all(); 
        
        return response()->json(['message' => 'Cuenta actualizada exitosamente.', 'list' => $cuentas], 201);
    
    }

    public function destroy($id)
    {
        MayorAccount::destroy($id);

        return response()->json(['message' => 'Cuenta eliminada exitosamente.'], 201);
    }
}
