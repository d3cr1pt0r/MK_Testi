<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use URL;
use Auth;
use Redirect;
use MKTests\Exam;
use MKTests\Book;
use MKTests\Task;
use MKTests\Question;
use MKTests\Answer;
use MKTests\Result;
use MKTests\QuestionAnswer;
use MKTests\Http\Requests;
use MKTests\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin', ['except' => ['getLogin', 'postLogin']]);
    }

    public function getIndex()
    {
        $exams = Exam::all();

        $view = view('admin.home');
        $view->exams = $exams;

        return $view;
    }

    public function getTest() {
        $exam = Exam::findOrFail(1);
        $exam_copy = $exam->replicate();
        $exam_copy->push();
    }

    public function getExam($id)
    {
        $exam = Exam::findOrFail($id);
        $results =$exam->results;

        $view = view('admin.exam');
        $view->exam = $exam;
        $view->results = $results;

        return $view;
    }

    public function getLogin()
    {
        $view = view('admin.login');
        return $view;
    }

    public function postLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        Auth::attempt(['name' => $username, 'password' => $password]);

        if (Auth::user())
            return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => 'Logged in']);
        return Redirect::to('admin/login')->with('response_status', ['success' => false, 'message' => 'Login failed']);
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('admin/login')->with('response_status', ['success' => true, 'message' => 'Logged out']);
    }

    public function getRemoveExam($id) {
        $exam = Exam::findOrFail($id);
        $exam_title = $exam->title;
        $exam->delete();

        return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => $exam_title.' deleted!']);
    }

    public function getRemoveCode($id) {
        $result = Result::findOrFail($id);
        $result_code = $result->code;
        $result->delete();

        return Redirect::back()->with('response_status', ['success' => true, 'message' => $result_code.' deleted!']);
    }

    public function postGenerateCodesMulti(Request $request) {
        $exam_ids = $request->input('exam_ids');
        $num_codes = $request->input('num_codes');

        for ($i=0;$i<$num_codes;$i++) {
            $uid = $this->generateUID();

            foreach($exam_ids as $exam_id) {
                $exam = Exam::findOrFail($exam_id);

                $result = new Result;
                $result->code = $uid;
                $result->used = false;
                $result->exam()->associate($exam);
                $result->save();
            }
        }
    }

    public function postGenerateCodes(Request $request) {
        $exam_id = $request->input('exam_id');
        $num_codes = $request->input('num_codes');

        $exam = Exam::findOrFail($exam_id);

        for ($i=0;$i<$num_codes;$i++) {
            $uid = $this->generateUID();

            $result = new Result;
            $result->code = $uid;
            $result->used = false;
            $result->exam()->associate($exam);
            $result->save();
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Generated '.$num_codes.' codes!']);
    }


    private function generateUID() {
        $uid = uniqid();
        $exists = Result::where('code', $uid)->first();

        if ($exists)
            $this->generateUID();

        return $uid;
    }

    private function saveFile($file, $folder)
    {
        try {
            $file_ext = ".".$file->guessExtension();
            $base_path = base_path();
            $sub_path = "/public/".$folder;
            $path = $base_path.$sub_path;
            $destination_name = uniqid().$file_ext;
            $file->move($path, $destination_name);
            return ["relative_path" => $folder, "absolute_path" => $path, "file_name" => $destination_name];
        } catch (Exception $e) {
            return false;
        }
    }

    ////////////////////////
    //// AJAX FUNCTIONS ////
    ////////////////////////

    public function postAddExam(Request $request)
    {
        $book = Book::findOrFail(1);

        $exam_object = $request->input('exam_data');
        $exam_object = json_decode($exam_object);

        $exam_title = $exam_object->exam_title;
        $exam_tasks = $exam_object->exam_tasks;

        $images_found = 0;

        $exam = new Exam;
        $exam->title = $exam_title;
        $exam->book()->associate($book);
        $exam->save();

        foreach ($exam_tasks as $k=>$t) {
            $task_title = $t->task_title;
            $questions = $t->questions;

            $task = new Task;
            $task->title = $task_title;
            $task->exam()->associate($exam);
            $task->save();

            foreach($questions as $k=>$q) {
                $question_title = $q->question_title;
                $question_has_image = $q->question_has_image;
                $question_type = intval($q->question_type);
                $answers = $q->answers;

                $question = new Question;
                $question->title = $question_title;
                $question->type = $question_type;
                $question->task()->associate($task);

                if ($question_has_image) {
                    $image_file = $request->file()['images'][$images_found];
                    $image_src = $this->saveFile($image_file, "/assets/uploads/");
                    if ($image_src != false) {
                        $question->image_src = $image_src["relative_path"].$image_src["file_name"];
                    }
                    $images_found++;
                }

                $question->save();

                foreach ($answers as $k=>$a) {
                    $answer_title = $a->answer_title;
                    $answer_correct = $a->correct;

                    $answer = new Answer;
                    $answer->title = $answer_title;
                    $answer->correct = $answer_correct;
                    $answer->question()->associate($question);
                    $answer->save();
                }
            }
        }

        return json_encode(['status' => true, 'message' => 'Exam saved']);
    }

}
