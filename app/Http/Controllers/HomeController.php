<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use ExamHelper;
use MKTests\Result;
use MKTests\Exam;
use MKTests\Question;
use MKTests\QuestionAnswer;
use MKTests\Http\Requests;
use MKTests\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth', ['except' => 'getLogin']);
    }

    public function getIndex()
    {
        return "Home";
    }

    public function getCode($uid)
    {
        $results = Result::where('code', $uid)->get();
        $exams = [];

        foreach($results as $result) {
            $exam = $result->exam;
            $exam->used = $result->used;
            $exam_results = ExamHelper::getExamResults($result);
            $exams[] = ['exam' => $exam, 'results' => $exam_results];
        }

        $view = view('home.home');
        $view->exams = $exams;
        $view->code = $uid;

        return $view;
    }

    public function getExam($id) {
        $code = explode(':', base64_decode($id))[1];
        $exam_id = explode(':', base64_decode($id))[0];

        $result = Result::where('code', $code)->where('exam_id', $exam_id)->first();

        if ($result != null) {
            if (!$result->used) {
                $exam = Exam::findOrFail($exam_id);

                $view = view('home.exam');
                $view->exam = $exam;
                $view->id = $result->id;
                $view->review = false;

                $result->used = true;
                $result->save();

                return $view;
            }
            else {
                $exam = Exam::findOrFail($exam_id);

                $view = view('home.exam-review');
                $view->exam = $exam;
                $view->id = $result->id;
                $view->review = true;
                $view->results = ExamHelper::getExamResults($result);

                return $view->with('response_status', ['success' => false, 'message' => 'You have already completed that exam!']);
                //return Redirect::to('code/'.$code)->with('response_status', ['success' => false, 'message' => 'You have already completed that exam!']);
            }
        }
    }

    public function postEvaluate(Request $request) {
        $result_id = $request->input('id');
        $exam_objects = $request->input('exam_object');
        $result = null;

        $exam_objects = json_decode($exam_objects);

        foreach($exam_objects as $exam_object) {
            $task_id = $exam_object->task_id;
            $questions = $exam_object->questions;

            $result = Result::findOrFail($result_id);

            foreach($questions as $q) {
                $question_id = $q->question_id;
                $question = Question::findOrFail($question_id);
                $answers = $q->answers;

                foreach($answers as $answer) {
                    $question_answer = new QuestionAnswer;
                    $question_answer->answer = $answer;
                    $question_answer->result()->associate($result);
                    $question_answer->question()->associate($question);
                    $question_answer->save();
                }
            }

            $result->used = true;
            $result->save();
        }

        return Redirect::to('code/'.$result->code)->with('response_status', ['success' => true, 'message' => 'Exam evaluated!']);
    }

}
