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
                <a class="navbar-brand" href="#">Mladinska Knjiga - Testi</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{url('/admin')}}">Domov</a></li>
                    <li><a href="{{url('/admin/books')}}">Knjige</a></li>
                    <li><a href="{{url('/admin/categories')}}">Kategorije</a></li>
                    <li><a href="{{url('/admin/users')}}">Uporabniki</a></li>
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
                    <li><a style="color: white;">{{ Auth::user()->name.' '.Auth::user()->surname }}</a></li>
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
        <button type="button" class="btn btn-success full-width" id="add-exam">Dodaj test</button>
        <div id="exam-container"></div>

        <form class="form-group" action="{{ url('admin/generate-codes-multi/')  }}" method="post">
            {!! csrf_field() !!}
            <table class="table table-condensed" style="margin-top: 60px">
                <th>Test</th>
                <th>Kategorija</th>
                <th>Št. vprasanj</th>
                <th>#</th>
                <th>#</th>

                @foreach($exams as $exam)
                    <tr>
                        <td><a href="{{ url('admin/exam/'.$exam->id)  }}">{{ $exam->book->title }}</a></td>
                        <td>{{ $exam->category->title }}</td>
                        <td>{{ count($exam->questions()) }}</td>
                        <td>
                            <a href="{{ url('admin/remove-exam/'.$exam->id)  }}">Izbriši</a>
                        </td>
                        <td>
                            <a href="#!" class="edit" id="edit" exam-id="{{ $exam->id }}" book-id="{{ $exam->book->id }}" category-id="{{ $exam->category->id }}">Uredi</a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <div class="well" style="margin-top: 20px;">
                <div class="form-group" style="float: left; width: 90%;">
                    <div class="input-group">
                        <div class="input-group-addon">Vnesi stevilo sifer</div>
                        <input type="text" class="form-control" name="num_codes">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="float: right; width: 8%;">Zgeneriraj</button>
                <div style="clear: both;"></div>
            </div>
        </form>
    </div>


    <!-- EXAM TEMPLATES -->

    <!-- exam-panel -->
    <script type="text/html" id="exam-panel">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p style="float: left; line-height: 33px; padding-left: 10px; padding-right: 4px;">Knjiga: </p>
                <select type="text" class="form-control" id="book-id" style="width: 200px; float: left;">
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                    @endforeach
                </select>
                <p style="float: left; line-height: 33px; padding-left: 10px; padding-right: 4px;">Kategorija: </p>
                <select type="text" class="form-control" id="category-id" style="width: 200px; float: left;" placeholder="Naslov testa">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
                <span class="glyphicon glyphicon-plus-sign add-question" id="add-task" style="float: right; font-size: 18px; color: royalblue; line-height: 33px; cursor: pointer;"></span>
                <div style="clear: both;"></div>
            </div>
            <form enctype="multipart/form-data">
                <div class="panel-body" id="exam-body">

                </div>
            </form>
            <div class="panel-body" id="exam-body">
                <button type="button" class="btn btn-primary full-width" id="save-exam" style="margin: 10px;">Shrani</button>
            </div>
        </div>
    </script>

    <!-- exam-task -->
    <script type="text/html" id="exam-task">
        <div class="container task-container" style="width: 100%; margin-bottom: 20px;">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="task-title" style="border-color: #2EA5F7; background-color: #DCEAFF;" placeholder="Naslov naloge" data-value="title">
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
                    <input type="text" class="form-control" id="question-title" style="border-color: #B42EF7; background-color: #ECDCFF;" placeholder="Vprašanje" data-value="title">
                </div>
                <div class="col-md-2" style="padding-right: 0;">
                    <a href="#!" style="color: #DC5353;"><span class="glyphicon glyphicon-remove remove-question" aria-hidden="true" style="float: right; display: block; padding-left: 14px; line-height: 33px;"></span></a>
                    <span class="glyphicon glyphicon-plus-sign add-answer" style="float: right; font-size: 18px; color: #BEBF0B; line-height: 33px; cursor: pointer;"></span>
                    <span class="glyphicon glyphicon-picture btn-file" style="cursor: pointer; float: right; padding-right: 14px; font-size: 18px; line-height: 33px;"><input name="images[]" type="file" class="file-input"></span>
                    <span class="label label-primary question-type" question-type="0" style="float: right; margin-right: 14px; margin-top: 9px; padding-top: 3px; cursor: pointer;">Izbor</span>
                </div>
            </div>
        </div>
    </script>

    <!-- exam-answer -->
    <script type="text/html" id="exam-answer">
        <div class="answer-container" style="padding-left: 10px;">
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-10">
                    <input type="text" class="form-control" id="answer-title" style="border-color: #F7C32E; background-color: #FFFFDC;" placeholder="Odgovor" data-value="title">
                </div>
                <div class="col-md-2" style="padding-right: 0;">
                    <a href="#!" style="color: #DC5353;"><span class="glyphicon glyphicon-remove remove-answer" aria-hidden="true" style="float: right; display: block; padding-left: 14px; line-height: 33px;"></span></a>
                    <span class="glyphicon glyphicon-question-sign answer-state correct" id="answer_state" style="float: right; font-size: 18px; color: #43B743; line-height: 33px; cursor: pointer;" correct></span>
                </div>
            </div>
        </div>
    </script>

    <script>

        var EDIT_MODE = false;
        var EXAM_ID = -1;

        //////////////////////////////////////////////////////
        //                Exam editing stuff                //
        //////////////////////////////////////////////////////
        function toggleAnswerCorrectState(element) {
            if ($(element).hasClass("correct")) {
                $(element).removeClass("correct");
                $(element).css("color", "indianred");
                $(element).removeAttr("correct");
                $(element).attr("wrong", "");
            }
            else {
                $(element).addClass("correct");
                $(element).css("color", "#43B743");
                $(element).removeAttr("wrong");
                $(element).attr("correct", "");
            }
        }

        function toggleQuestionTypeState(element) {
            var question_type = $(element).attr("question-type");

            if (question_type == 0) {
                $(element).attr("question-type", 1);
                $(element).text("Vnos");
            }
            if (question_type == 1) {
                $(element).attr("question-type", 0);
                $(element).text("Izbor");
            }
        }

        $(".edit").click(function() {
            var exam_id = $(this).attr("exam-id");
            var book_id = $(this).attr("book-id");
            var category_id = $(this).attr("category-id");

            EDIT_MODE = true;
            EXAM_ID = exam_id;

            $("#exam-container").loadTemplate($("#exam-panel"), {}, {append: false});
            $("#book-id").val(book_id);
            $("#category-id").val(category_id);

            $.get( "{{ url('admin/exam-json/') }}/" + exam_id, function( data ) {
                data = JSON.parse(data)['tasks'];

                for(var i=0;i<data.length;i++) {
                    var task_id = data[i]['id'];
                    var task_title = data[i]['title'];

                    $("#exam-body").loadTemplate($("#exam-task"), {
                        id: task_id,
                        title: task_title
                    }, {append: true});

                    var task_container = $(".task-container")[i];
                    $(task_container).find("#task-title").attr("data-id", task_id);

                    for(var j=0;j<data[i]['questions'].length;j++) {
                        var question_id = data[i]['questions'][j]['id'];
                        var question_title = data[i]['questions'][j]['title'];
                        var question_type = data[i]['questions'][j]['type'];
                        var question_image = data[i]['questions'][j]['image_src'];

                        $(task_container).loadTemplate($("#exam-question"), {
                            id: question_id,
                            title: question_title
                        }, {append: true});

                        var question_container = $(task_container).find(".question-container")[j];
                        $(question_container).find("#question-title").attr("data-id", question_id);

                        if(question_type) {
                            toggleQuestionTypeState($(question_container).find(".question-type"));
                        }

                        for(var k=0;k<data[i]['questions'][j]['answers'].length;k++) {
                            var answer_id = data[i]['questions'][j]['answers'][k]['id'];
                            var answer_title = data[i]['questions'][j]['answers'][k]['title'];
                            var answer_correct = data[i]['questions'][j]['answers'][k]['correct'];

                            $(question_container).loadTemplate($("#exam-answer"), {
                                id: answer_id,
                                title: answer_title
                            }, {append: true});

                            var answer_container = $(question_container).find(".answer-container")[k];
                            $(answer_container).find("#answer-title").attr("data-id", answer_id);

                            if(!answer_correct) {
                                toggleAnswerCorrectState($(answer_container).find(".answer-state"));
                            }
                        }
                    }
                }
            });
        });


        //////////////////////////////////////////////////////
        // Events for adding/removing questions and answers //
        //////////////////////////////////////////////////////

        $("#add-exam").click(function() {
            EDIT_MODE = false;
            EXAM_ID = -1;
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
            toggleAnswerCorrectState($(this));
        });

        $("body").on("click", ".question-type", function() {
            toggleQuestionTypeState($(this));
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
            //console.log($(this).parent().parent().closest(".file-input"));
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

        function getBookId() {
            return $("#book-id").val();
        }

        function getCategoryId() {
            return $("#category-id").val();
        }

        function getExamQuestionsAndAnswers() {
            var exam = {};

            $.each($(".task-container"), function(i, val) {
                var task_id = $(this).find("#task-title").attr("data-id");
                var task_title = $(this).find("#task-title").val();

                exam[i] = {'id': task_id, 'task_title': task_title, 'questions': []}
                $.each($(this).find(".question-container"), function(j, val2) {
                    var question_id = $(this).find("#question-title").attr("data-id");
                    var question_title = $(this).find("#question-title").val();
                    var question_image = $(this).find(".file-input").val() != "";
                    var question_type = $(this).find(".question-type").attr("question-type");

                    exam[i]['questions'][j] = {'id': question_id, 'question_title': question_title, 'question_has_image': question_image, 'question_type': question_type, 'answers': []};
                    $.each($(this).find(".answer-container"), function(k, val3) {
                        var answer_id = $(this).find("#answer-title").attr("data-id");
                        var answer_title = $(this).find("#answer-title").val();
                        var answer_correct = $(this).find("#answer_state").attr("correct") !== undefined;

                        exam[i]['questions'][j]['answers'][k] = {'id': answer_id, 'answer_title': answer_title, 'correct': answer_correct};
                    });
                });
            });

            return exam;
        }

        function progressHandlingFunction(e){
            if(e.lengthComputable) {
                //console.log(e.loaded / e.total);
            }
        }

        function beforeSendHandler() {

        }

        function completeHandler(data) {
            status = JSON.parse(data)['status'];
            if (status) {
                location.reload();
            }
            else {
                alert(JSON.parse(data)['message']);
            }
        }

        function errorHandler() {

        }

        $("body").on("click", "#save-exam", function() {
            var exam_object =
            {
                edit: EDIT_MODE,
                id: EXAM_ID,
                book_id: getBookId(),
                category_id: getCategoryId(),
                exam_tasks: getExamQuestionsAndAnswers()
            };

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