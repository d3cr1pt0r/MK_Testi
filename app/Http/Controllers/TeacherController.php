<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use ExamHelper;
use MKTests\Category;
use MKTests\Exam;
use MKTests\User;
use Illuminate\Support\Facades\Redirect;
use MKTests\Http\Requests;
use MKTests\Http\Controllers\Controller;

class TeacherController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.teacher', ['except' => ['postNewTeacher', 'postExistingTeacher', 'getCheckpoint']]);
    }

    public function getIndex()
    {
        $categories = Category::all();

        $view = view('teacher.home');
        $view->categories = $categories;
        return $view;
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('teachers')->with('response_status', ['success' => true, 'message' => 'Logged out']);
    }

    public function getCheckpoint()
    {
        $view = view('teacher.entry');
        return $view;
    }

    public function getCategory($id) {
        $category = Category::find($id);

        $view = view('teacher.category');
        $view->category = $category;

        return $view;
    }

    public function postNewTeacher(Request $request)
    {
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $school_name = $request->input('school-name');
        $school_type = $request->input('school-type');
        $password = $request->input('password');

        $status = ExamHelper::createUser($name, $surname, $school_name, $school_type, $email, $password, 1);

        if ($status['success'])
            return Redirect::back()->with('response_status', ['success' => $status['success'], 'message' => $status['message']]);
        return Redirect::back()->with('response_status', ['success' => $status['success'], 'message' => $status['message'].' (Exception code '.$status['exception_code'].')']);
    }

    public function postExistingTeacher(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        Auth::attempt(['email' => $email, 'password' => $password]);

        if (Auth::user())
            return Redirect::to('teachers')->with('response_status', ['success' => true, 'message' => 'Logged in']);
        return Redirect::to('teachers/checkpoint')->with('response_status', ['success' => false, 'message' => 'Login failed']);
    }

    public function postGenerateCodesCategory(Request $request) {
        $category_id = $request->input('category-id');
        $num_codes = $request->input('num-codes');
        $exams = Category::find($category_id)->exams;

        for ($i=0;$i<$num_codes;$i++) {
            $uid = ExamHelper::generateUID();

            foreach($exams as $exam) {
                ExamHelper::createResult($exam, Auth::user(), $uid, false);
            }
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Generated '.$num_codes.' codes for '.count($exams).' exams!']);
    }

    public function postGenerateCodesExam(Request $request) {
        $exam_id = $request->input('exam-id');
        $num_codes = $request->input('num-codes');

        $exam = Exam::findOrFail($exam_id);

        for ($i=0;$i<$num_codes;$i++) {
            $uid = ExamHelper::generateUID();

            ExamHelper::createResult($exam, Auth::user(), $uid, false);
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Generated '.$num_codes.' codes!']);
    }

    public function getExam($id) {
        $exam = Exam::findOrFail($id);

        $view = view('teacher.exam');
        $view->exam = $exam;

        return $view;
    }
}
