<?php

namespace App\Http\Controllers;

use App\Models\TitleAccount;
use Illuminate\Http\Request;

class TitleAccountController extends Controller
{
    public function index()
    {
        $cuentas = TitleAccount::all(); 
        return response()->json($cuentas); 
    }

    public function store(Request $request)
    {
        $cuenta = new TitleAccount($request->all());

        $cuenta->save();
        return response()->json(['message' => 'Cuenta registrada con exito'], 201);
    }

    public function show($id)
    {
        $cuenta = TitleAccount::find($id);
        return response()->json($cuenta);
    }

    public function update(Request $request, $id)
    {
        // Find the cuenta by ID, or return a 404 error if not found
        $cuenta = TitleAccount::findOrFail($id);

        // Update the cuenta with the provided data
        $cuenta->update($request->all());
        
        return response()->json(['message' => 'Cuenta actualizada exitosamente.'], 201);
    }

    public function destroy($id)
    {
        TitleAccount::destroy($id);

        return response()->json(['message' => 'Cuenta eliminada exitosamente.'], 201);
    }
}
