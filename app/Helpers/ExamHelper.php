<?php

use MKTests\Exam;
use MKTests\Book;
use MKTests\Task;
use MKTests\Question;
use MKTests\Answer;
use MKTests\Result;

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

    static public function createExam($book, $title) {
        $exam = new Exam;
        $exam->title = $title;
        $exam->book()->associate($book);
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

        foreach($result->question_answers as $qa) {
            $question = $qa->question;
            $answers = $question->answers;
            $answers_total = count($answers);
            $answers_submitted_correct = 0;

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

        if ($questions_total == 0)
            return ['results' => $results, 'score' => 0];
        return ['results' => $results, 'score' => ($score_total / $questions_total) * 100];
    }

    static public function addExam($request)
    {
        $book = Book::findOrFail(1);

        $exam_object = $request->input('exam_data');
        $exam_object = json_decode($exam_object);

        $exam_title = $exam_object->exam_title;
        $exam_tasks = $exam_object->exam_tasks;

        $exam = ExamHelper::createExam($book, $exam_title);

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