@extends('admin.parts.master')

@section('content')
    <div class="container">
        @include('admin.parts.messages')

        <div class="page-header">
            <h1>Administration <small>- login</small></h1>
        </div>

        <div class="login-container">
            <form class="form-group" action="" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Username</div>
                        <input type="text" class="form-control" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Password</div>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
@endsection