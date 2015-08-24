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

    public function getExam($id)
    {
        $exam = Exam::findOrFail($id);

        $view = view('home.exam');
        $view->exam = $exam;

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

    public function getAddExam()
    {
        $book = Book::findOrFail(1);

        $exam = new Exam;
        $exam->title = "hehe";
        $exam->book()->associate($book);
        $exam->save();

        return "A";
    }

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

        return "OK";
    }

}
