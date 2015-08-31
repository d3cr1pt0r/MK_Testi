<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{

    protected $table = 'question_answers';

    public function result()
    {
        return $this->belongsTo('MKTests\Result', 'result_id');
    }

    public function question()
    {
        return $this->belongsTo('MKTests\Question', 'question_id');
    }

    public function getResult()
    {
        # get self.result and compare correct or incorrect answers with answer rows we get back

        # total answers
        # correct answers*
        # wrong answers*

        # calculate percentage of correct answers*
        # return all fields with *
    }1

}
