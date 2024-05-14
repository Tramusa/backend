<?php

namespace App\Http\Controllers;

use App\Models\HistoryTire;
use Illuminate\Http\Request;

class HistoryTireController extends Controller
{
    public function index($tiresId)
    {
        // Filtrar las actividades por el ID de la llanta
        $activities = HistoryTire::where('tire_ctrl', $tiresId)->get();
        return response()->json($activities);
    }

    public function store(Request $request)
    {
        return HistoryTire::create($request->all());
    }

    public function show($id)
    {
        $activities = HistoryTire::where('tire_ctrl', $id)->get();
        return response()->json($activities);
    }

    public function update(Request $request, $id)
    {
        $historyTire = HistoryTire::findOrFail($id);
        $historyTire->update($request->all());

        return $historyTire;
    }

    public function destroy($id)
    {
        //
    }
}
