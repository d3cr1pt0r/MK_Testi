<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use URL;
use Auth;
use Redirect;
use ExamHelper;
use MKTests\User;
use MKTests\Exam;
use MKTests\Book;
use MKTests\Category;
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
        $users = User::all();
        $books = Book::all();
        $categories = Category::all();

        $view = view('admin.home');
        $view->exams = $exams;
        $view->users = $users;
        $view->books = $books;
        $view->categories = $categories;

        return $view;
    }

    public function getBooks() {
        $books = Book::all();

        $view = view('admin.books');
        $view->books = $books;

        return $view;
    }

    public function getCategories() {
        $categories = Category::all();
        $users = User::all();

        $view = view('admin.categories');
        $view->categories = $categories;
        $view->users = $users;

        return $view;
    }

    public function getUsers() {
        $categories = Category::all();
        $users = User::all();

        $view = view('admin.users');
        $view->categories = $categories;
        $view->users = $users;

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

        Auth::attempt(['email' => $username, 'password' => $password]);

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

    public function getRemoveUser($id) {
        $user_email = ExamHelper::removeUser($id);
        return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => $user_email.' deleted!']);
    }

    public function getRemoveCategory($id) {
        $category_name = ExamHelper::removeCategory($id);
        return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => $category_name.' deleted!']);
    }

    public function getRemoveBook($id) {
        $book_name = ExamHelper::removeBook($id);
        return Redirect::to('admin')->with('response_status', ['success' => true, 'message' => $book_name.' deleted!']);
    }

    public function getResetCode($id) {
        $code = ExamHelper::resetCode($id);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => $code.' was reset!']);
    }

    public function postAddBook(Request $request) {
        $title = $request->input('book-name');
        $book = ExamHelper::createBook($title);

        if($book)
            return Redirect::back()->with('response_status', ['success' => true, 'message' => $book->title.' added!']);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Failed to add new book!']);
    }

    public function postAddCategory(Request $request) {
        $title = $request->input('category-name');
        $user_id = $request->input('user-id');
        $category = ExamHelper::createCategory($title, $user_id);

        if($category)
            return Redirect::back()->with('response_status', ['success' => true, 'message' => $category->title.' added!']);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Failed to add new book!']);
    }

    public function postAddUser(Request $request) {
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $password = $request->input('password');
        $user_type = $request->input('user-type');
        $user_ids = $request->input('user-id');

        $user = ExamHelper::createUser($name, $surname, $email, $password, $user_type, $user_ids);

        if($user)
            return Redirect::back()->with('response_status', ['success' => true, 'message' => $user->name.' '.$user->surname.' added!']);
        return Redirect::back()->with('response_status', ['success' => true, 'message' => 'Failed to add new user!']);
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
