<?php

namespace App\Http\Controllers;

use App\Models\AddressGroup;
use Illuminate\Http\Request;

class AddressGroupController extends Controller
{
    public function index()
    { 
        $groups = AddressGroup::all(); 
        return response()->json($groups);
    }

    public function create(Request $request)
    {
        $peaje = new AddressGroup($request->all());
        $peaje->save();
        return response()->json(['message' => 'Grupo registrado con exito'], 201);
    }


    public function show($id)
    {
        $group = AddressGroup::find($id);
        return response()->json($group);
    }

    public function update(Request $request, $id)
    {
        AddressGroup::find($id)->update($request->all()); 
    }

    public function destroy($id)
    {
        AddressGroup::find($id)->delete();
        return response()->json(['message' => 'Grupo eliminado exitosamente.']);
    }
}
