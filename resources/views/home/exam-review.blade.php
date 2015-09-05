@extends('admin.parts.master')

@section('content')
    <div class="container">

        @include('admin.parts.messages')

        <a href="{{ url('code/'.$code) }}" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Back to exams</a>

        <div class="panel panel-default" style="margin-top: 20px;">
            <div class="panel-heading">
                <span style="display: block; float: left; font-size: 22px;">{{ $exam->title }} <span style="font-size: 13px; color: {{ $results['score'] < 50 ? 'red' : 'green' }}">[{{ number_format($results['score'], 1).'%' }}]</span></span>
                <input type="text" class="form-control" name="name" placeholder="Surname" style="float: right; width: 130px; margin-left: 5px;">
                <input type="text" class="form-control" name="surname" placeholder="Name" style="float: right; width: 130px;">
                <div style="clear: both;"></div>
            </div>
            <div class="panel-body" style="padding: 0px;">
                <?php $i=0 ?>
                <?php $q=0 ?>
                <?php $question_results=null ?>
                @foreach($exam->tasks as $task)
                    <?php $i++ ?>
                    <?php $score=0 ?>
                    <div class="task">
                        <h4 class="title" tid="{{ $task->id }}">{{ (string)$i.'. '.$task->title }}</h4>
                        <div class="row">
                            @foreach($task->questions as $question)

                                <?php if (array_key_exists($question->id, $results['results'])) $question_results = $results['results'][$question->id]; else $question_results = null; ?>
                                @if($question->image_src != "")
                                    <div class="col-xs-6 col-md-3 question" qid="{{ $question->id }}">
                                        <a href="#" class="thumbnail">
                                            <img src="{{ URL::asset($question->image_src) }}">
                                        </a>
                                        <input type="text" style="width: 100%; background-color: {{ in_array($question_results['question'][0]->answer, $question->answers_arr()) ? '#DEFFDE' : '#FFDDDD' }}" value="{{ $question_results['question'][0]->answer }}">
                                    </div>
                                @else
                                    <div class="question" style="padding-left: 30px;" qid="{{ $question->id }}">
                                        <h5><strong>{{ $question->title }}</strong></h5>
                                        @if($question->type == 0)
                                            @foreach($question->answers as $answer)
                                                <div class="checkbox" style="padding-left: 5px;">
                                                    <?php $found=false ?>
                                                    <?php $correct=false ?>
                                                    @if ($question_results != null)
                                                        <?php $found=false ?>
                                                        <?php $correct=false ?>
                                                        @foreach ($question_results['question'] as $qa)
                                                            @if ($answer->title == $qa->answer)
                                                                <?php $found=true ?>
                                                                @if ($answer->correct)
                                                                    <?php $correct=true ?>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    <label style="color: {{ $correct ? 'green' : ($found ? 'red' : 'black') }}">
                                                        <input type="checkbox" disabled="disabled" {{ $found ? 'checked="checked"' : '' }} value="{{ $answer->title }}"> {{ $answer->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @elseif ($question->type == 1)
                                            <input type="text" style="background-color: {{ in_array($question_results['question'][0]->answer, $question->answers_arr()) ? '#DEFFDE' : '#FFDDDD' }}" value="{{ $question_results['question'][0]->answer }}">
                                        @endif
                                    </div>
                                @endif
                                <?php $score += $question_results['score'] ?>
                            @endforeach
                        </div>
                        <p style="padding-left: 10px; margin-top: 20px; font-weight: bold;">Total: {{ number_format(($score / count($task->questions)) * 100, 1).'%' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection