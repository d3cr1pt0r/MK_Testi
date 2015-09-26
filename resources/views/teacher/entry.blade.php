@extends('admin.parts.master')

@section('content')
    <div class="container">
        @include('admin.parts.messages')

        <div class="page-header">
            <h1>Vstop za učitelje in profesorje</h1>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="">
                    <form class="form-group" action="{{ url('teachers/new-teacher') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Ime</div>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Priimek</div>
                                <input type="text" class="form-control" name="surname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Email</div>
                                <input type="text" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Šola</div>
                                <input type="text" class="form-control" name="school-name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Geslo</div>
                                <input type="text" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Geslo</div>
                                <input type="text" class="form-control" name="password2" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" style="min-width: 72px;">Tip šole</div>
                                <select class="form-control" name="school-type">
                                    <option value="-1">-- IZBERI ŠOLO --</option>
                                    <option value="0">Osnovna</option>
                                    <option value="1">Srednja</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Vstop</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <form class="form-group" action="{{ url('teachers/existing-teacher') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Email</div>
                                <input type="text" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Geslo</div>
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Vstop</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("form").submit(function() {
            if ($('[name="password"]').val() != $('[name="password2"]').val()) {
                alert("Gesli se ne ujemata!");
                return false;
            }
        })
    </script>
@endsection