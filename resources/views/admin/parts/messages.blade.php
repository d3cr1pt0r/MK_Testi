@if (Session::has('response_status'))
    @if (Session::get('response_status')['success'])
        <div class="alert alert-success alert-dismissible fade in alert-box" role="alert" id="success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('response_status')['message']}}
        </div>
    @else
        <div class="alert alert-danger alert-dismissible fade in alert-box" role="alert" id="error">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('response_status')['message']}}
        </div>
    @endif

    <script>
        setTimeout(function(){ $('#success').fadeOut(); $('#error').fadeIn(); }, 2000)
    </script>

@endif