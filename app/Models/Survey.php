<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image', 'reward', 'start_date', 'end_date'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
