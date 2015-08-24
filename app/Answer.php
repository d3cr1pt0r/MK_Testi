<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answers';

    public function question()
    {
        return $this->belongsTo('MKTests\Question', 'question_id');
    }
}
