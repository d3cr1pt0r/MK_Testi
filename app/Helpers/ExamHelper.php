<?php

use MKTests\Result;

class ExamHelper
{

    static public function getExamResults($result) {
        $results = [];
        $score_total = 0;
        $questions_total = count($result->exam->questions());

        foreach($result->question_answers as $qa) {
            $question = $qa->question;
            $answers = $question->answers;
            $answers_total = count($answers);
            $answers_submitted = 0;
            $answers_submitted_correct = 0;
            $answers_correct = 0;
            $score = 0;

            # get all submitted answers
            $answers_submitted = $result->question_answers()->where('question_id', $question->id)->count();

            # get all submitted correct answers
            $answers_correct = $qa->question->answers()->where('correct', true)->count();

            foreach($answers as $answer) {
                if ($qa->answer == $answer->title) {
                    $answers_submitted_correct++;
                }
            }

            if ($answers_submitted == 0)
                $score = 0;
            else
                $score = ($answers_correct / $answers_submitted) * ($answers_submitted_correct/$answers_submitted);

                $score_total += $score;

            $results[] = ['answers_total' => $answers_total, 'answers_submitted' => $answers_submitted, 'answers_submitted_correct' => $answers_submitted_correct, 'answers_correct' => $answers_correct, 'question' => $question];
        }

        $score_total = ($score_total / $questions_total) * 100;

        return ['results' => $results, 'score' => $score_total];
    }

}