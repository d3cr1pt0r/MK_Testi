<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use URL;
use Auth;
use Redirect;
use ExamHelper;
use MKTests\Exam;
use MKTests\Result;
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
        $exam_title = ExamHelper::removeExam($id);
        return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => $exam_title.' deleted!']);
    }

    public function getRemoveCode($id) {
        $code = ExamHelper::removeCode($id);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => $code.' deleted!']);
    }

    public function getResetCode($id) {
        $code = ExamHelper::resetCode($id);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => $code.' was reset!']);
    }

    public function postGenerateCodesMulti(Request $request) {
        $exam_ids = $request->input('exam_ids');
        $num_codes = $request->input('num_codes');

        for ($i=0;$i<$num_codes;$i++) {
            $uid = ExamHelper::generateUID();

            foreach($exam_ids as $exam_id) {
                $exam = Exam::findOrFail($exam_id);
                ExamHelper::createResult($exam, $uid, false);
            }
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Generated '.$num_codes.' codes for '.count($exam_ids).' exams!']);
    }

    public function postGenerateCodes(Request $request) {
        $exam_id = $request->input('exam_id');
        $num_codes = $request->input('num_codes');

        $exam = Exam::findOrFail($exam_id);

        for ($i=0;$i<$num_codes;$i++) {
            $uid = ExamHelper::generateUID();
            ExamHelper::createResult($exam, $uid, false);
        }

        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Generated '.$num_codes.' codes!']);
    }

    ////////////////////////
    //// AJAX FUNCTIONS ////
    ////////////////////////

    public function postAddExam(Request $request)
    {
        ExamHelper::addExam($request);
        return json_encode(['status' => true, 'message' => 'Exam saved']);
    }

}
