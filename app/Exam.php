<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    public function book()
    {
        return $this->belongsTo('MKTests\Book', 'book_id');
    }

    public function tasks()
    {
    	return $this->hasMany('MKTests\Task', 'exam_id');
    }

    public function questions()
    {
    	$questions = [];
    	foreach($this->tasks as $task) {
    		foreach($task->questions as $question) {
    			$questions[] = $question;
    		}
    	}

    	return $questions;
    }

    public function results()
    {
        return $this->hasMany('MKTests\Result', 'exam_id');
    }

}
