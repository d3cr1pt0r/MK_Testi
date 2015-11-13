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
    <table class="table table-condensed">
        <thead>
        <th>Ime</th>
        <th>Priimek</th>
        <th>Knjiga</th>
        <th>Razred</th>
        <th>Rezultat</th>
        </thead>

        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result->name }}</td>
                <td>{{ $result->surname }}</td>
                <td>{{ $result->exam->book->title }}</td>
                <td>{{ $result->exam->category->title }}</td>
                <td>{{ $result->score }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection