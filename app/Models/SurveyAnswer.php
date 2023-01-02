<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SurveyAnswer extends Model
{
    use HasFactory, BelongsToThrough;

    //unuse timestamps
    public $timestamps = false;

    protected $fillable = [
        'survey_id',
        'start_date',
        'end_date',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsToThrough(User::class, Survey::class);
    }
}
