<?php

namespace MKTests;

use ExamHelper;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $table = 'results';

    public function exam()
    {
        return $this->belongsTo('MKTests\Exam', 'exam_id');
    }

    public function user()
    {
        return $this->belongsTo('MKTests\User', 'user_id');
    }

    public function question_answers()
    {
        return $this->hasMany('MKTests\QuestionAnswer', 'result_id');
    }

    public function getResults()
    {
        return ExamHelper::getExamResults2($this);
    }
    
}
