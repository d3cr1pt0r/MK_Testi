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
                <li class="active"><a href="{{url('teachers')}}">Domov</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a style="color: white;">{{ Auth::user()->name.' '.Auth::user()->surname }}</a></li>
                <li><a href="{{url('teachers/logout')}}">Logout</a></li>
            </ul>
        </div>
    </div>
    @include('admin.parts.messages')
</nav>

<div class="container">
    <div class="well" style="margin-top: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 25px;">{{ $exam->book->title  }} - {{ $exam->category->title }}</h3>

        @foreach($exam->resultsUser() as $result)
            <a href="{{ url('code/'.$result->code) }}"><span style="float: left; width: 100px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ $result->code }}</span></a>
            <span style="float: left; width: 70px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ number_format($result->getResults()['score'], 1).'%' }}</span>
            <a href="{{ url('admin/remove-code/'.$result->id)  }}" style="float: left; margin-left: 5px;">Remove</a>
            <a href="{{ url('admin/reset-code/'.$result->id)  }}" style="float: left; margin-left: 15px;">Reset</a>
            <div style="clear: left;"></div>
        @endforeach
    </div>
</div>

@endsection