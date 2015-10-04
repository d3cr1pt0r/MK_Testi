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
                <li><a href="{{url('/admin/categories')}}">Kategorije</a></li>
                <li class="active"><a href="{{url('/admin/users')}}">Uporabniki</a></li>
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
        <h3 style="margin-top: 0; margin-bottom: 25px;">Uporabniki</h3>
        <form action="{{ url('admin/add-user') }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group" style="width: 100%; margin-bottom: 5px;">
                    <div class="input-group-addon" style="width: 80px;">Ime</div>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="input-group" style="width: 100%; margin-bottom: 5px;">
                    <div class="input-group-addon" style="width: 80px;">Priimek</div>
                    <input type="text" class="form-control" name="surname">
                </div>
                <div class="input-group" style="width: 100%; margin-bottom: 5px;">
                    <div class="input-group-addon" style="width: 80px;">Email</div>
                    <input type="text" class="form-control" name="email">
                </div>
                <div class="input-group" style="width: 100%; margin-bottom: 5px;">
                    <div class="input-group-addon" style="width: 80px;">Geslo</div>
                    <input type="text" class="form-control" name="password">
                </div>
                <div class="input-group" style="width: 100%; margin-bottom: 5px;">
                    <div class="input-group-addon" style="width: 80px;">Tip uporabnika</div>
                    <select class="form-control" name="user-type">
                        <option value="0">Administrator</option>
                        <option value="1">Profesor</option>
                    </select>
                </div>
                <br>
                <p>Kategorije</p>
                @foreach($categories as $category)
                    <label class="checkbox-inline">
                        <input type="checkbox" name="user-id[]" value="{{ $category->id }}"> {{ $category->title }}
                    </label>
                @endforeach
                <br>
                <button type="submit" class="btn btn-success" id="add-book" style="width: 100%; margin-top: 10px;">Dodaj</button>
                <div style="clear: both;"></div>
            </div>
        </form>
        <table class="table table-condensed" style="margin-top: 20px">
            <th>Ime</th>
            <th>Priimek</th>
            <th>E-mail</th>
            <th>Šola</th>
            <th>Tip šole</th>
            <th>Tip uporabnika</th>
            <th>Datum registracije</th>
            <th>Število šifer</th>
            <th style="text-align: right;">Generiranje</th>
            <th style="text-align: right;">#</th>

            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->school_name }}</td>
                    <td>{{ $user->school_type == 0 ? 'Osnovna' : 'Srednja' }}</td>
                    <td>{{ $user->user_type == 0 ? 'Administrator' : 'Profesor' }}</td>
                    <td>{{ $user->created_at->format('d.m.Y [H:i:s]') }}</td>
                    <td>{{ $user->totalGeneratedCodes() }}</td>
                    <td>
                        @if ($user->generated == 1)
                            <a href="{{ url('admin/toggle-generated/'.$user->id) }}"><span class="label label-danger">Onemogočeno</span></a>
                        @else
                            <a href="{{ url('admin/toggle-generated/'.$user->id) }}"><span class="label label-success">Omogočeno</span></a>
                        @endif
                    </td>
                    <td align="right">
                        <a href="{{ url('admin/remove-user/'.$user->id) }}">Izbriši</a>
                        <a href="{{ url('admin/edit-user'.$user->id) }}">Uredi</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection