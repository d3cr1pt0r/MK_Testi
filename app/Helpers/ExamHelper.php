<?php

use MKTests\Exam;
use MKTests\Book;
use MKTests\Task;
use MKTests\Question;
use MKTests\Answer;
use MKTests\Result;
use MKTests\Category;

class ExamHelper
{

    static public function createResult($exam, $code, $used) {
        $result = new Result;
        $result->code = $code;
        $result->used = $used;
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

    static public function createCategory($title) {
        $category = new Category;
        $category->title = $title;
        $category->save();

        return $category;
    }

    static public function createExam($book, $category, $title) {
        $exam = new Exam;
        $exam->title = $title;
        $exam->book()->associate($book);
        $exam->category()->associate($category);
        $exam->save();

        return $exam;
    }

    static public function createTask($exam, $title) {
        $task = new Task;
        $task->title = $title;
        $task->exam()->associate($exam);
        $task->save();

        return $task;
    }

    static public function createQuestion($task, $title, $type, $image_src='') {
        $question = new Question;
        $question->title = $title;
        $question->type = $type;
        $question->image_src = $image_src;
        $question->task()->associate($task);
        $question->save();

        return $question;
    }

    static public function createAnswer($question, $title, $correct) {
        $answer = new Answer;
        $answer->title = $title;
        $answer->correct = $correct;
        $answer->question()->associate($question);
        $answer->save();

        return $answer;
    }

    static public function removeExam($id) {
        $exam = Exam::findOrFail($id);
        $exam_title = $exam->title;
        $exam->delete();

        return $exam_title;
    }

    static public function removeCode($id) {
        $result = Result::findOrFail($id);
        $result_code = $result->code;
        $result->delete();

        return $result_code;
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

    static public function addExam($request)
    {
        $exam_object = $request->input('exam_data');
        $exam_object = json_decode($exam_object);

        $book_id = $exam_object->book_id;
        $category_id = $exam_object->category_id;

        $exam_title = $exam_object->exam_title;
        $exam_tasks = $exam_object->exam_tasks;

        $exam = ExamHelper::createExam(Book::findOrFail($book_id), Category::findOrFail($category_id), $exam_title);

        foreach ($exam_tasks as $k=>$t) {
            $task_title = $t->task_title;
            $questions = $t->questions;
            $images_found = 0;

            $task = ExamHelper::createTask($exam, $task_title);

            foreach($questions as $k=>$q) {
                $question_title = $q->question_title;
                $question_has_image = $q->question_has_image;
                $question_type = intval($q->question_type);
                $answers = $q->answers;

                if ($question_has_image) {
                    $image_file = $request->file()['images'][$images_found];
                    $image_src = ExamHelper::saveFile($image_file, "/assets/uploads/");

                    if ($image_src != false)
                        $question = ExamHelper::createQuestion($task, $question_title, $question_type, $image_src["relative_path"].$image_src["file_name"]);
                    else
                        $question = ExamHelper::createQuestion($task, $question_title, $question_type);
                }
                else
                    $question = ExamHelper::createQuestion($task, $question_title, $question_type);

                $images_found++;

                foreach ($answers as $k=>$a) {
                    $answer_title = $a->answer_title;
                    $answer_correct = $a->correct;

                    $answer = ExamHelper::createAnswer($question, $answer_title, $answer_correct);
                }
            }
        }

        return json_encode(['status' => true, 'message' => 'Exam saved']);
    }

}