<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public function exam()
    {
        return $this->belongsTo('MKTests\Exam', 'exam_id');
    }

    public function questions()
    {
    	return $this->hasMany('MKTests\Question', 'task_id');
    }
}
