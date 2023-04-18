<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'a_paterno' => $request->user()->a_paterno,
            'a_materno' => $request->user()->a_materno,
            'alias' => $request->user()->alias,
            'rol' => $request->user()->rol,
            'email' => $request->user()->email,
            'avatar' => $request->user()->avatar ? 'http://localhost:8000'.Storage::url($request->user()->avatar) : null,
        ]);
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'currentPassword' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (Hash::check($request->currentPassword, $user->password)) {
            //Cambiar la contraseña
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Contraseña actualizada exitosamente.'], 201);
        } else {
            return response()->json(['errors' =>  ['currentPassword' => 
            ['La contraseña actual es incorrecta.']]], 401);
        }
    }
}
