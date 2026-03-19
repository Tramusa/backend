<?php

namespace App\Http\Controllers;

use App\Models\FatigueRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FatigueRatingController extends Controller
{

    public function index()
    {
        return FatigueRating::with('operator','performedBy')
            ->orderBy('created_at','desc')
            ->get();
    }


    public function store(Request $request)
    {

        $request->validate([
            'operator_id' => 'required|exists:users,id',

            'question_1' => 'required|integer|min:0|max:2',
            'question_2' => 'required|integer|min:0|max:2',
            'question_3' => 'required|integer|min:0|max:2',
            'question_4' => 'required|integer|min:0|max:2',
            'question_5' => 'required|integer|min:0|max:2',
            'question_6' => 'required|integer|min:0|max:2',
            'question_7' => 'required|integer|min:0|max:2',

            'total' => 'required|integer',
            'risk' => 'required|string',

            'actions' => 'nullable|string'
        ]);


        $fatigue = FatigueRating::create([

            'operator_id' => $request->operator_id,
            'performed_by' => Auth::id(),

            'question_1' => $request->question_1,
            'question_2' => $request->question_2,
            'question_3' => $request->question_3,
            'question_4' => $request->question_4,
            'question_5' => $request->question_5,
            'question_6' => $request->question_6,
            'question_7' => $request->question_7,

            'total' => $request->total,
            'risk' => $request->risk,
            'actions' => $request->actions
        ]);


        return response()->json([
            'message' => 'Evaluación registrada correctamente',
            'data' => $fatigue
        ]);
    }


    public function destroy($id)
    {
        FatigueRating::destroy($id);

        return response()->json([
            'message' => 'Evaluación eliminada exitosamente.'
        ], 200);
    }
    
    public function pdf($id)
    {

        $fatigue = FatigueRating::with('operator','performedBy')
            ->findOrFail($id);

        $logoImagePath = public_path('imgPDF/logo.png');
        $logo = $this->getImageBase64($logoImagePath);

        $data = [

            'fatigue' => $fatigue,

            'operator' => $fatigue->operator,
            'evaluator' => $fatigue->performedBy,

            'q1' => $fatigue->question_1,
            'q2' => $fatigue->question_2,
            'q3' => $fatigue->question_3,
            'q4' => $fatigue->question_4,
            'q5' => $fatigue->question_5,
            'q6' => $fatigue->question_6,
            'q7' => $fatigue->question_7,

            'total' => $fatigue->total,
            'risk' => $fatigue->risk,

            'actions' => $fatigue->actions,

            'date' => $fatigue->created_at->format('d/m/Y'),
            'time' => $fatigue->created_at->format('h:i a'),

            'logo' => $logo
        ];

        $pdf = Pdf::loadView('pdf.fatigue-checklist',$data)
            ->setPaper('letter','portrait');

        return $pdf->stream("fatigue-checklist-".$id.".pdf");

    }


    private function getImageBase64($path)
    {

        if (file_exists($path)) {

            $file = file_get_contents($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        if (Storage::exists($path)) {

            $file = Storage::get($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        return null;

    }

}