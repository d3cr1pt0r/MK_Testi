<?php

namespace MKTests;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function exams()
    {
        return $this->hasMany('MKTests\Exam', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo('MKTests\User', 'user_id');
    }

    public function hasUserGenerated()
    {
        $results = Result::where('user_id', Auth::user()->id)->get();
        foreach($results as $result) {
            $exam = Exam::find($result->exam_id);
            if ($exam->category_id == $this->id) {
                return true;
            }
        }
        return false;
    }

}
