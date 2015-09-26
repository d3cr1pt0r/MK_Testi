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
    <div class="well">
        <h3 style="margin-top: 0; margin-bottom: 25px;">Kategorije</h3>

        <table class="table table-bordered">
            <th>Naslov</th>
            <th>Status šifer</th>
            <!--<th>#</th>-->
            @foreach($category->exams as $exam)
                <tr>
                    <td><a href="{{ url('teachers/exam/'.$exam->id) }}">{{ $exam->book->title }}</a></td>
                    <td>
                        <p style="margin: 0px; font-size: 12px; color: blue;">Vseh: {{ count($exam->resultsUsed()) + count($exam->resultsUnused()) }}</p>
                        <p style="margin: 0px; font-size: 12px; color: green;">Rešenih: {{ count($exam->resultsUsed()) }}</p>
                        <p style="margin: 0px; font-size: 12px; color: red;">Nerešenih: {{ count($exam->resultsUnused()) }}</p>
                    </td>
                    <!--
                    <td style="width: 200px;">
                        <form action="{{ url('teachers/generate-codes-exam') }}" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="exam-id" value="{{ $exam->id }}">
                            <input type="text" name="num-codes" style="width: 100px;">
                            <button type="submit">Generiraj</button>
                        </form>
                    </td>
                    -->
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection