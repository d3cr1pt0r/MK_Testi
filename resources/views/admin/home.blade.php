@extends('admin.parts.master')

@section('content')
    <!-- Static navbar -->
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">MK - Exams</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{url('/admin')}}">Home</a></li>
                    <li><a href="{{url('/books')}}">Books</a></li>
                    <li><a href="{{url('/users')}}">Users</a></li>
                    <!--
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                    -->
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{url('admin/logout')}}">Logout</a></li>
                    <!--<li class="active"><a href="./">Static top <span class="sr-only">(current)</span></a></li> -->
                    <!--<li><a href="../navbar-fixed-top/">Fixed top</a></li> -->
                </ul>
            </div><!--/.nav-collapse -->
        </div>
        @include('admin.parts.messages')
    </nav>

    <!-- Main content -->
    <div class="container">
        <button type="button" class="btn btn-success full-width" id="add-exam">Create Exam</button>
        <div id="exam-container"></div>

        <table class="table table-condensed">
            <th>Exam</th>
            <th>Questions</th>
            <th>Time limit</th>

            @foreach($exams as $exam)
                <tr>
                    <td><a href="{{ url('admin/exam/'.$exam->id)  }}">{{ $exam->title }}</a></td>
                    <td>{{ count($exam->questions()) }}</td>
                    <td>None</td>
                </tr>
            @endforeach
        </table>
    </div>


    <!-- EXAM TEMPLATES -->

    <!-- exam-panel -->
    <script type="text/html" id="exam-panel">
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="text" class="form-control" id="exam-title" style="width: 300px; float: left;" placeholder="Exam title">
                <span class="glyphicon glyphicon-plus-sign add-question" id="add-task" style="float: right; font-size: 18px; color: royalblue; line-height: 33px; cursor: pointer;"></span>
                <div style="clear: both;"></div>
            </div>
            <form enctype="multipart/form-data">
                <div class="panel-body" id="exam-body">

                </div>
            </form>
            <div class="panel-body" id="exam-body">
                <button type="button" class="btn btn-primary full-width" id="save-exam" style="margin: 10px;">Save</button>
            </div>
        </div>
    </script>

    <!-- exam-task -->
    <script type="text/html" id="exam-task">
        <div class="container task-container" style="width: 100%; margin-bottom: 20px;">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="task-title" style="border-color: #2EA5F7; background-color: #DCEAFF;" placeholder="Task title">
                </div>
                <div class="col-md-2" style="padding-right: 0;">
                    <a href="#!" style="color: #DC5353;"><span class="glyphicon glyphicon-remove remove-task" aria-hidden="true" style="float: right; display: block; padding-left: 14px; line-height: 33px;"></span></a>
                    <span class="glyphicon glyphicon-plus-sign add-question" style="float: right; font-size: 18px; color: purple; line-height: 33px; cursor: pointer;"></span>
                </div>
            </div>
        </div>
    </script>

    <!-- exam-question -->
    <script type="text/html" id="exam-question">
        <div class="question-container" style="padding-left: 10px;">
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="question-title" style="border-color: #B42EF7; background-color: #ECDCFF;" placeholder="Question title">
                </div>
                <div class="col-md-2" style="padding-right: 0;">
                    <a href="#!" style="color: #DC5353;"><span class="glyphicon glyphicon-remove remove-question" aria-hidden="true" style="float: right; display: block; padding-left: 14px; line-height: 33px;"></span></a>
                    <span class="glyphicon glyphicon-plus-sign add-answer" style="float: right; font-size: 18px; color: #BEBF0B; line-height: 33px; cursor: pointer;"></span>
                    <span class="glyphicon glyphicon-picture btn-file" style="cursor: pointer; float: right; padding-right: 14px; font-size: 18px; line-height: 33px;"><input name="images[]" type="file" class="file-input"></span>
                    <span class="label label-primary question-type" question-type="0" style="float: right; margin-right: 14px; margin-top: 9px; padding-top: 3px; cursor: pointer;">Select</span>
                </div>
            </div>
        </div>
    </script>

    <!-- exam-answer -->
    <script type="text/html" id="exam-answer">
        <div class="answer-container" style="padding-left: 10px;">
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="answer-title" style="border-color: #F7C32E; background-color: #FFFFDC;" placeholder="Answer title">
                </div>
                <div class="col-md-2" style="padding-right: 0;">
                    <a href="#!" style="color: #DC5353;"><span class="glyphicon glyphicon-remove remove-answer" aria-hidden="true" style="float: right; display: block; padding-left: 14px; line-height: 33px;"></span></a>
                    <span class="glyphicon glyphicon-question-sign answer-state correct" id="answer_state" style="float: right; font-size: 18px; color: #43B743; line-height: 33px; cursor: pointer;"></span>
                </div>
            </div>
        </div>
    </script>

    <script>

        //////////////////////////////////////////////////////
        // Events for adding/removing questions and answers //
        //////////////////////////////////////////////////////

        $("#add-exam").click(function() {
            $("#exam-container").loadTemplate($("#exam-panel"), {}, {append: false});
        });

        $("body").on("click", "#add-task", function() {
            $("#exam-body").loadTemplate($("#exam-task"), {}, {append: true});
        });

        $("body").on("click", ".add-question", function() {
            $(this).closest(".task-container").loadTemplate($("#exam-question"), {}, {append: true});
        });

        $("body").on("click", ".add-answer", function() {
            $(this).closest(".question-container").loadTemplate($("#exam-answer"), {}, {append: true});
        });

        $("body").on("click", ".answer-state", function() {
            if ($(this).hasClass("correct")) {
                $(this).removeClass("correct");
                $(this).css("color", "indianred");
                $(this).removeAttr("correct");
                $(this).attr("wrong", "");
            }
            else {
                $(this).addClass("correct");
                $(this).css("color", "#43B743");
                $(this).removeAttr("wrong");
                $(this).attr("correct", "");
            }
        });

        $("body").on("click", ".question-type", function() {
            var question_type = $(this).attr("question-type");

            if (question_type == 0) {
                $(this).attr("question-type", 1);
                $(this).text("Enter");
            }
            if (question_type == 1) {
                $(this).attr("question-type", 0);
                $(this).text("Select");
            }
        });

        $("body").on("click", ".remove-task", function() {
            if (confirm("Remove task?")) {
                $(this).closest(".task-container").remove();
            }
        });

        $("body").on("click", ".remove-question", function() {
            if (confirm("Remove question?")) {
                $(this).closest(".question-container").remove();
            }
        });

        $("body").on("click", ".remove-answer", function() {
            if (confirm("Remove answer?")) {
                $(this).closest(".answer-container").remove();
            }
        });

        $("body").on("click", ".add-image", function() {
            console.log($(this).parent().parent().closest(".file-input"));
        });

        $("body").on("change", ".file-input", function() {
            var input = $(this).parent().parent().parent().find("#question-title");
            var file = $(this).val();

            if (file == "") {
                input.prop("disabled", false);
                input.val("");
            }
            else {
                input.prop("disabled", true);
                input.val(file.replace(/^.*[\\\/]/, ''));
            }
        })

        //////////////////////////////////////////////////////
        // Scan questions and answers and pack them in JSON //
        //////////////////////////////////////////////////////

        function getExamTitle() {
            return $("#exam-title").val();
        }

        function getExamQuestionsAndAnswers() {
            var exam = {};

            $.each($(".task-container"), function(i, val) {
                var task_title = $(this).find("#task-title").val();

                exam[i] = {'task_title': task_title, 'questions': []}
                $.each($(this).find(".question-container"), function(j, val2) {
                    var question_title = $(this).find("#question-title").val();
                    var question_image = $(this).find(".file-input").val() != "";
                    var question_type = $(this).find(".question-type").attr("question-type");

                    exam[i]['questions'][j] = {'question_title': question_title, 'question_has_image': question_image, 'question_type': question_type, 'answers': []};
                    $.each($(this).find(".answer-container"), function(k, val3) {
                        var answer_title = $(this).find("#answer-title").val();
                        var answer_correct = $(this).find("#answer_state").attr("correct") !== undefined;

                        exam[i]['questions'][j]['answers'][k] = {'answer_title': answer_title, 'correct': answer_correct};
                    });
                });
            });

            return exam;
        }

        function progressHandlingFunction(e){
            if(e.lengthComputable) {
                console.log(e.loaded / e.total);
            }
        }

        function beforeSendHandler() {

        }

        function completeHandler(data) {
            console.log(data);
        }

        function errorHandler() {

        }

        $("body").on("click", "#save-exam", function() {
            var exam_object = {};

            exam_object['exam_title'] = getExamTitle();
            exam_object['exam_tasks'] = getExamQuestionsAndAnswers();
            exam_object = JSON.stringify(exam_object);

            var form_data = new FormData($('form')[0]);
            form_data.append('exam_data', exam_object);

            $.ajax({
                url: "{{ url('admin/add-exam')  }}",
                type: 'POST',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload) {
                        myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                    }
                    return myXhr;
                },
                beforeSend: beforeSendHandler,
                success: completeHandler,
                error: errorHandler,
                data: form_data,
                cache: false,
                contentType: false,
                processData: false
            });
        });

    </script>

@endsection