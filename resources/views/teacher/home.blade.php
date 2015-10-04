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
                <ul class="nav navbar-nav" style="margin-left: 10px;">
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
            V spodnja polja vpišite število učencev, ki jih želite prijaviti na tekmovanje BOOKWORMS - za vsak razred posebej. Ko bodo šifre ustvarjene, boste prejeli seznam na vaš elektronski naslov. S šiframi, ki jih boste prejeli, bodo vaši učenci lahko - v času tekmovanja seveda (ne prej in ne kasneje) - dostopali do testov/vprašalnikov.
        </div>
        <div class="well">
            <h3 style="margin-top: 0; margin-bottom: 25px;">Kategorije</h3>

            <table class="table table-bordered">
                <th>Ime kategorije</th>
                <th>Število testov</th>
                <th style="text-align: right;">Generiranje</th>

                @foreach($categories as $category)
                    <tr>
                        <td><a href="{{ url('teachers/category/'.$category->id) }}">{{ $category->title }}</a></td>
                        <td>{{ count($category->exams) }}</td>
                        @if(!$category->hasUserGenerated() || Auth::user()->generated == 0)
                            <td style="text-align: right;">
                                <input type="hidden" name="category-id" value="{{ $category->id }}">
                                <input type="text" name="num-codes" style="">
                                <button type="submit" class="generate-codes">Generiraj</button>
                            </td>
                        @else
                            <td>
                                <p>Za ta razred ste šifre že zgenerirali</p>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-success full-width generate-codes-all">Generiraj vse</button>
        </div>
    </div>

<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Generiranje</h4>
            </div>
            <div class="modal-body">
                Sistem generira šifre. Prosimo počakajte trenutek...
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(".generate-codes").click(function() {
        var c_id = $(this).parent().find($('[name="category-id"]')).val();
        var num_codes = $(this).parent().find($('[name="num-codes"]')).val();

        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        })

        $.post( "{{ url('teachers/generate-codes-category') }}", { _token: "{{ csrf_token() }}", category_id: c_id, num_codes: num_codes })
                .done(function( data ) {
                    if (data == "OK") {
                        location.reload();
                    }
                    else {
                        alert("Failed to generate codes. Contact developers!");
                    }
                });
    });

    $(".generate-codes-all").click(function() {
        var category_ids = $('[name="category-id"]');
        var num_codes = $('[name="num-codes"]');

        var data = [];

        for(var i=0;i<category_ids.length;i++) {
            data[$(category_ids[i]).val()] = $(num_codes[i]).val();
            console.log($(category_ids[i]).val() + " " + $(num_codes[i]).val());
        }

        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        })

        $.post( "{{ url('teachers/generate-codes-category-all') }}", { _token: "{{ csrf_token() }}", data: data })
                .done(function( data ) {
                    if (data == "OK") {
                        location.reload();
                    }
                    else {
                        alert("Failed to generate codes. Contact developers!");
                    }
                });
    });
</script>
@endsection