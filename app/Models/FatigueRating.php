<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FatigueRating extends Model
{
    protected $fillable = [
        'operator_id',
        'performed_by',
        'question_1',
        'question_2',
        'question_3',
        'question_4',
        'question_5',
        'question_6',
        'question_7',
        'total_points',
        'level'
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}