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
                <li><a href="{{url('/admin')}}">Domov</a></li>
                <li><a href="{{url('/admin/books')}}">Knjige</a></li>
                <li class="active"><a href="{{url('/admin/categories')}}">Kategorije</a></li>
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

<div class="container">
    <div class="well">
        <h3 style="margin-top: 0; margin-bottom: 25px;">Kategorije</h3>
        <form action="{{ url('admin/add-category') }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group" style="float: left; width: 60%;">
                    <div class="input-group-addon" style="max-width: 130px;">Naslov kategorije</div>
                    <input type="text" class="form-control" name="category-name">
                </div>
                <div class="input-group" style="float: left; width: 10%; margin-left: 10px;">
                    <div class="input-group-addon" style="max-width: 130px;">Profesor</div>
                    <select type="text" class="form-control" name="user-id" style="width: 200px; float: left;">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name.' '.$user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success" id="add-category" style="float: right;">Dodaj</button>
                <div style="clear: both;"></div>
            </div>
        </form>
        <table class="table table-condensed" style="margin-top: 20px">
            <th>Ime kategorije</th>
            <th>Število testov</th>
            <th>Profesor</th>
            <th style="text-align: right;">#</th>

            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->title }}</td>
                    <td>{{ count($category->exams) }}</td>
                    <td>{{ $category->user != null ? $category->user->name.' '.$category->user->surname : 'Null' }}</td>
                    <td align="right">
                        <a href="{{ url('admin/remove-category/'.$category->id) }}">Izbriši</a>
                        <a href="{{ url('admin/edit-category'.$category->id) }}">Uredi</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection