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
            <a class="navbar-brand" href="#" style="padding: 0; margin: 0; padding-top: 5px;"><img height="40" src="{{ URL::asset('assets/img/logo_mk.jpg') }}"></a>
            <a class="navbar-brand" href="#" style="padding: 0; margin: 0; padding-top: 5px; margin-left: 3px;"><img height="40" src="{{ URL::asset('assets/img/logo_co.jpg') }}"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="{{url('teachers')}}">Domov</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a style="color: white;">{{ Auth::user()->name.' '.Auth::user()->surname }}</a></li>
                <li><a href="{{url('teachers/logout')}}">Odjava</a></li>
            </ul>
        </div>
    </div>
    @include('admin.parts.messages')
</nav>

<div class="container">
    <div class="well" style="margin-top: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 25px;">{{ $exam->book->title  }} - {{ $exam->category->title }}</h3>

        @foreach($exam->resultsUser() as $result)
            <span style="float: left; width: 100px; color: {{ $result->used ? $result->getResults()['questions_correct'] < $result->getResults()['questions_total'] / 2 ? 'red' : 'green' : 'blue' }}">{{ $result->code }}</span>
            <span style="float: left; width: 180px; color: {{ $result->used ? $result->getResults()['questions_correct'] < $result->getResults()['questions_total'] / 2 ? 'red' : 'green' : 'blue' }}">{{ 'Pravilni/Nepravilni: '.$result->getResults()['questions_correct'].'/'.$result->getResults()['questions_total'] }}</span>
            <span style="float: left;">{{ $result->name.' '.$result->surname }}</span>
            <!-- <a href="{{ url('admin/remove-code/'.$result->id)  }}" style="float: left; margin-left: 5px;">Remove</a> -->
            <!-- <a href="{{ url('admin/reset-code/'.$result->id)  }}" style="float: left; margin-left: 15px;">Reset</a> -->
            <div style="clear: left;"></div>
        @endforeach
    </div>
</div>

@endsection