@extends('admin.parts.master')

@section('content')

    <div class="container">
        @include('admin.parts.messages')

        <div class="form-group" style="float: left; width: 90%;">
            <div class="input-group">
                <div class="input-group-addon">Enter code</div>
                <input type="text" class="form-control" name="code">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="float: right; width: 8%;" id="code">Continue</button>
        <div style="clear: both;"></div>
    </div>

    <script>
        $('#code').click(function() {
            console.log($('input[name="code"]').val());
            document.location = '{{ url('code/')  }}/' + $('input[name="code"]').val();
        });
    </script>

@endsection