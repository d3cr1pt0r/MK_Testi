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
        $view = view('home.home');
        return $view;
    }

    public function getCode($uid)
    {
        $uid = substr($uid, 0, 10);

        if (Result::where('code', $uid)->count() == 0)
            return Redirect::back()->with('response_status', ['success' => false, 'message' => 'Šifra ne obstaja!']);

        $results = Result::where('code', $uid)->get();
        $result = Result::where('code', $uid)->first();
        $has_identified = false;
        $exams = [];

        foreach($results as $result) {
            $exam = $result->exam;
            $exam->used = $result->used;
            $exam_results = ExamHelper::getExamResults2($result);
            $exams[] = ['exam' => $exam, 'results' => $exam_results];
            if ($result->name != '' && $result->surname != '')
                $has_identified = true;
        }

        $view = view('home.exams');
        $view->exams = $exams;
        $view->code = $uid;
        $view->mentor = $result->user;
        $view->category = $result->exam->category;
        $view->has_identified = $has_identified;

        return $view;
    }

    public function getExam($id) {
        $code = explode(':', base64_decode($id))[1];
        $exam_id = explode(':', base64_decode($id))[0];

        $result = Result::where('code', substr($code, 0, 10))->where('exam_id', $exam_id)->first();

        if ($result != null) {
            $exam = Exam::findOrFail($exam_id);

            if (!$result->used) {
                $view = view('home.exam');
                $view->exam = $exam;
                $view->id = $result->id;
                $view->review = false;

                //$result->used = true;
                $result->save();

                return $view;
            }
            else {
                $view = view('home.exam-review');
                $view->exam = $exam;
                $view->id = $result->id;
                $view->code = $code;
                $view->review = true;
                $view->results = ExamHelper::getExamResults2($result);

                $submitted_answers = $result->question_answers()->count();

                if ($submitted_answers == 0)
                    return Redirect::back()->with('response_status', ['success' => false, 'message' => 'It looks like the exam was submitted without any questions answered.']);
                return $view->with('response_status', ['success' => false, 'message' => 'You have already completed that exam!']);
            }
        }
    }

    public function postIdentify(Request $request) {
        $name = $request->input('name');
        $surname = $request->input('surname');
        $code = $request->input('code');

        $results = Result::where('code', $code)->get();

        foreach($results as $result) {
            $result->name = $name;
            $result->surname = $surname;
            $result->save();
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Uspešno ste bili identificirani!']);
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
