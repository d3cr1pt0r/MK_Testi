@extends('admin.parts.master')

@section('content')

    <div class="container">
        @include('admin.parts.messages')

        <div class="well" style="margin-top: 20px;">
            <h2>Spletno tekmovanje Bookworms</h2>
            V okno vpiši šifro za vstop na tekmovanje.
        </div>

        <div class="form-group" style="float: left; width: 90%;">
            <div class="input-group">
                <div class="input-group-addon">Šifra</div>
                <input type="text" class="form-control" name="code">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="float: right; width: 8%;" id="code">Nadaljuj</button>
        <div style="clear: both;"></div>
    </div>

    <script>
        $('#code').click(function() {
            console.log($('input[name="code"]').val());
            document.location = '{{ url('code/')  }}/' + $('input[name="code"]').val();
        });
    </script>

@endsection