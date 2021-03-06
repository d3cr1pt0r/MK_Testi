<?php

namespace MKTests;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    public function book()
    {
        return $this->belongsTo('MKTests\Book', 'book_id');
    }

    public function category()
    {
        return $this->belongsTo('MKTests\Category', 'category_id');
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

    public function resultsUser()
    {
        return $this->results()->where('user_id', Auth::user()->id)->get();
    }

    public function resultsUsed()
    {
        return $this->results()->where('used', true)->where('user_id', Auth::user()->id)->get();
    }

    public function resultsUnused()
    {
        return $this->results()->where('used', false)->where('user_id', Auth::user()->id)->get();
    }

    public function taskIds()
    {
        $ids = [];
        foreach($this->tasks as $task)
            $ids[] = $task->id;
        return $ids;
    }

}
