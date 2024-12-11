<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    public function index()
    {
        $applicants = Applicant::all();
        return response()->json($applicants);
    }

    public function store(Request $request)
    {
        $applicant = new Applicant($request->all());
        $applicant->save();
        return response()->json(['message' => 'Solicitante registrado con exito'], 201);
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update($request->all());

        return response()->json(['message' => 'Solicitante modificado con exito'], 201);
    }

    public function destroy($id)
    {
        Applicant::destroy($id);

        return response()->json(['message' => 'Solicitante eliminado exitosamente'], 201);
    }

}
