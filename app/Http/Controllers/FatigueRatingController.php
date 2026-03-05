<?php

namespace App\Http\Controllers;

use App\Models\FatigueRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FatigueRatingController extends Controller
{
    public function index()
    {
        return FatigueRating::with('operator', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'question_1' => 'required|integer|min:0|max:2',
            'question_2' => 'required|integer|min:0|max:2',
            'question_3' => 'required|integer|min:0|max:2',
            'question_4' => 'required|integer|min:0|max:2',
            'question_5' => 'required|integer|min:0|max:2',
            'question_6' => 'required|integer|min:0|max:2',
            'question_7' => 'required|integer|min:0|max:2',
        ]);

        $total =
            $request->question_1 +
            $request->question_2 +
            $request->question_3 +
            $request->question_4 +
            $request->question_5 +
            $request->question_6 +
            $request->question_7;

        if ($total <= 2) {
            $level = 'BAJO';
        } elseif ($total <= 7) {
            $level = 'MEDIO';
        } else {
            $level = 'ALTO';
        }

        $fatigue = FatigueRating::create([
            ...$request->only([
                'operator_id',
                'question_1',
                'question_2',
                'question_3',
                'question_4',
                'question_5',
                'question_6',
                'question_7'
            ]),
            'performed_by' => Auth::id(),
            'total_points' => $total,
            'level' => $level
        ]);

        return response()->json([
            'message' => 'Evaluación registrada correctamente',
            'data' => $fatigue
        ]);
    }
}