@extends('admin.parts.master')

@section('content')
    <div class="container">

        @include('admin.parts.messages')

        <a href="{{ url('code/'.$code) }}" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Nazaj</a>

        <div class="panel panel-default" style="margin-top: 20px;">
            <div class="panel-heading">
                <span style="display: block; float: left; font-size: 22px;">{{ $exam->title }} <span style="font-size: 13px">Pravilni/Nepravilni: </span><span style="font-size: 13px; color: {{ $results['questions_correct'] < $results['questions_total'] / 2 ? 'red' : 'green' }}">{{ $results['questions_correct'].'/'.$results['questions_total'] }}</span></span>
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
                                <div class="question" style="padding-left: 30px;" qid="{{ $question->id }}">
                                    @if ($question->image_src != "")
                                        <a href="#" class="thumbnail" style="width: 200px">
                                            <img src="{{ URL::asset($question->image_src) }}">
                                        </a>
                                    @else
                                        <h5><strong>{{ $question->title }}</strong></h5>
                                    @endif
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
                                                <label style="//color: {{ $correct ? 'green' : ($found ? 'red' : 'black') }}">
                                                    <input type="checkbox" disabled="disabled" {{ $found ? 'checked="checked"' : '' }} value="{{ $answer->title }}"> {{ $answer->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif ($question->type == 1)
                                        <input type="text" style="//background-color: {{ in_array($question_results['question'][0]->answer, $question->answers_arr()) ? '#DEFFDE' : '#FFDDDD' }}" value="{{ $question_results['question'][0]->answer }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection