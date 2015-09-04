<?php

use MKTests\Result;

class ExamHelper
{

    static public function getExamResults($result) {
        $results = []
        foreach($result->question_answers as $qa) {
            $question = $qa->question;
            $answers = $question->answers;
            $answers_total = count($answers);
            $answers_correct = 0;

            foreach($answers as $answer) {
                if ($qa->answer == $answer->title) {
                    $answers_correct++;
                }
            }

            $results[] = ['answers_total' => $answers_total, 'answers_correct' => $answers_correct, 'question' => $question];
        }
        dd($results);
    }

}