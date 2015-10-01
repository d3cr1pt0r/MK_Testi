<?php

use Illuminate\Database\QueryException;
use MKTests\Exam;
use MKTests\Book;
use MKTests\Task;
use MKTests\Question;
use MKTests\Answer;
use MKTests\Result;
use MKTests\Category;
use MKTests\User;

class ExamHelper
{
    static public function createUser($name, $surname, $school_name, $school_type, $email, $password, $user_type) {
        try {
            $user = new User;
            $user->name = $name;
            $user->surname = $surname;
            $user->school_name = $school_name;
            $user->school_type = $school_type;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->user_type = $user_type;
            $user->save();

            return ['success' => true, 'message' => 'Uporabnik '.$user->name.' '.$user->surname.' kreiran!'];
        }
        catch(QueryException $e) {
            return ['success' => false, 'exception_code' => $e->getCode(), 'exception_message' => $e->getMessage(), 'message' => 'Failed to create user'];
        }

        return $user;
    }

    static public function createResult($exam, $user, $code, $used) {
        $result = new Result;
        $result->code = $code;
        $result->used = $used;
        $result->user()->associate($user);
        $result->exam()->associate($exam);
        $result->save();

        return $result;
    }

    static public function createBook($title) {
        $book = new Book;
        $book->title = $title;
        $book->save();

        return $book;
    }

    static public function createCategory($title, $school_type) {
        $category = new Category;
        $category->title = $title;
        $category->school_type = $school_type;
        $category->save();

        return $category;
    }

    static public function createExam($book, $category, $id=null) {
        $exam = $id == null ? new Exam : Exam::findOrFail($id);
        $exam->book()->associate($book);
        $exam->category()->associate($category);
        $exam->save();

        return $exam;
    }

    static public function createTask($exam, $title, $id=null) {
        $task = $id == null ? new Task : Task::findOrFail($id);
        $task->title = $title;
        $task->exam()->associate($exam);
        $task->save();

        return $task;
    }

    static public function createQuestion($task, $title, $type, $image_src='', $id=null) {
        $question = $id == null ? new Question : Question::findOrFail($id);
        $question->title = $title;
        $question->type = $type;
        $question->image_src = $image_src;
        $question->task()->associate($task);
        $question->save();

        return $question;
    }

    static public function createAnswer($question, $title, $correct, $id=null) {
        $answer = $id == null ? new Answer : Answer::findOrFail($id);
        $answer->title = $title;
        $answer->correct = $correct;
        $answer->question()->associate($question);
        $answer->save();

        return $answer;
    }

    static public function removeExam($id) {
        $exam = Exam::findOrFail($id);
        $exam_title = $exam->book->title;
        $exam->delete();

        return $exam_title;
    }

    static public function removeCode($id) {
        $result = Result::findOrFail($id);
        $result_code = $result->code;
        $result->delete();

        return $result_code;
    }

    static public function removeUser($id) {
        $user = User::findOrFail($id);
        $user_email = $user->email;
        $user->delete();

        return $user_email;
    }

    static public function removeCategory($id) {
        $category = Category::findOrFail($id);
        $category_name = $category->title;
        $category->delete();

        return $category_name;
    }

    static public function removeBook($id) {
        $book = Book::findOrFail($id);
        $book_name = $book->title;
        $book->delete();

        return $book_name;
    }

    static public function resetCode($id) {
        $result = Result::findOrFail($id);
        $result->used = false;
        $result->question_answers()->delete();
        $result->save();

        return $result->code;
    }

    static public function generateUID() {
        $uid = uniqid();
        $exists = Result::where('code', $uid)->first();

        if ($exists)
            ExamHelper::generateUID();

        return $uid;
    }

    static public function saveFile($file, $folder)
    {
        $file_ext = ".".$file->guessExtension();
        $base_path = base_path();
        $sub_path = "/public/".$folder;
        $path = $base_path.$sub_path;
        $destination_name = uniqid().$file_ext;

        try {
            $file->move($path, $destination_name);
        } catch (Exception $e) {

        }

        return ["relative_path" => $folder, "absolute_path" => $path, "file_name" => $destination_name];
    }

    static public function getExamResults($result) {
        $results = [];
        $score_total = 0;
        $questions_total = count($result->exam->questions());

        $question_group = [];

        foreach($result->question_answers as $qa) {
            $question_id = $qa->question->id;
            $question_group[$question_id][] = $qa;
        }

        foreach($question_group as $question_id=>$question) {
            $q = Question::find($question_id);
            $answers_total = $q->answers()->count();
            $answers_correct = $q->answers()->where('correct', true)->count();
            $answers_submitted = $result->question_answers()->where('question_id', $question_id)->count();
            $answers_submitted_correct = 0;

            $all_answers = [];
            foreach (Question::find($question_id)->answers as $answer)
                if ($answer->correct)
                    $all_answers[] = $answer->title;

            $answers_submitted_arr = [];
            foreach($question as $answer) {
                $answers_submitted_arr[] = $answer->answer;
                if (in_array($answer->answer, $all_answers))
                    $answers_submitted_correct++;
            }

            $answers_submitted_wrong = $answers_submitted - $answers_submitted_correct;

            if ($q->type == 1)
                $answers_correct = 1;

            $r = $answers_submitted_correct - $answers_submitted_wrong;

            if ($answers_submitted == 0 || ($answers_submitted_correct - $answers_submitted_wrong) <= 0)
                $score = 0;
            else
                $score = $r / $answers_correct;

            $score_total += $score;

            $results[$question_id] = ['answers_total' => $answers_total, 'answers_submitted' => $answers_submitted, 'answers_submitted_correct' => $answers_submitted_correct, 'answers_correct' => $answers_correct, 'score' => $score, 'question' => $question];
        }

        if ($questions_total == 0)
            return ['results' => $results, 'score' => 0];
        return ['results' => $results, 'score' => ($score_total / $questions_total) * 100];
    }

    // TODO: Take care of deleted tasks/questions/answers (check which ids have been modified and which not)
    static public function addExam($exam_object, $images)
    {
        $exam_id = $exam_object->id != -1 ? $exam_object->id : null;
        $book_id = $exam_object->book_id;
        $category_id = $exam_object->category_id;

        $exam_tasks = $exam_object->exam_tasks;

        $exam = ExamHelper::createExam(Book::findOrFail($book_id), Category::findOrFail($category_id), $exam_id);

        foreach ($exam_tasks as $k=>$t) {
            $task_id = property_exists($t, 'id') ? $t->id : null;
            $task_title = $t->task_title;
            $questions = $t->questions;
            $images_found = 0;

            $task = ExamHelper::createTask($exam, $task_title, $task_id);

            foreach($questions as $k=>$q) {
                $question_id = property_exists($q, 'id') ? $q->id : null;
                $question_title = $q->question_title;
                $question_has_image = $q->question_has_image;
                $question_type = intval($q->question_type);
                $answers = $q->answers;

                $image_src = '';
                if ($question_has_image) {
                    $image_file = $images[$images_found];
                    $image_status = ExamHelper::saveFile($image_file, "/assets/uploads/");
                    $image_src = $image_status == false ? '' : $image_status["relative_path"].$image_status["file_name"];
                }

                $question = ExamHelper::createQuestion($task, $question_title, $question_type, $image_src, $question_id);

                $images_found++;

                foreach ($answers as $k=>$a) {
                    $answer_id = property_exists($a, 'id') ? $a->id : null;
                    $answer_title = $a->answer_title;
                    $answer_correct = $a->correct;

                    $answer = ExamHelper::createAnswer($question, $answer_title, $answer_correct, $answer_id);
                }
            }
        }

        return json_encode(['status' => true, 'message' => 'Exam saved']);
    }

}