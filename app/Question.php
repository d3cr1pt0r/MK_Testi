<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    public function task()
    {
        return $this->belongsTo('MKTests\Task', 'task_id');
    }

    public function answers()
    {
    	return $this->hasMany('MKTests\Answer', 'question_id');
    }
}
