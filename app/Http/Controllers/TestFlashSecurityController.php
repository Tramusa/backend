<?php

namespace App\Http\Controllers;

use App\Models\TestFlashSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestFlashSecurityController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'responses.question1' => 'required',
            'responses.question2' => 'required',
            'responses.question3' => 'required',
            'responses.question4' => 'required',
            'responses.question5' => 'required',
            'responses.question6' => 'required',
        ]);

        TestFlashSecurity::create([
            'user_id' => Auth::id(),
            'question1' => $data['responses']['question1'],
            'question2' => $data['responses']['question2'],
            'question3' => $data['responses']['question3'],
            'question4' => $data['responses']['question4'],
            'question5' => $data['responses']['question5'],
            'question6' => $data['responses']['question6'],
            'status' => 'Contestado',
        ]);

        return response()->json(['message' => 'Respuestas guardadas correctamente']);
    }
}
