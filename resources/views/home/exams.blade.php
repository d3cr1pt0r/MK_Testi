@extends('admin.parts.master')

@section('content')

<div class="container">
    @include('admin.parts.messages')

    <div class="well" style="margin-top: 20px">
        <h2 style="float: left;">Dobrodošli v tekmovanje Bookworms</h2>
        <a href="{{ url('')  }}" class="btn btn-primary" style="float: right;">Odjava</a>
        <div style="clear: both;"></div>
        <p><strong>Šola:</strong> {{ $mentor->school_name }}</p>
        <p><strong>Mentor:</strong> {{ $mentor->name.' '.$mentor->surname }}</p>
        <p><strong>Razred:</strong> {{ $category->title }}</p>
        <p><strong>Učenec:</strong> {{ $result->name.' '.$result->surname }}</p>

        @if ($has_identified)
            <p>
                S klikom na naslov knjige spodaj boste prišli na stran z vprašanji. Pri reševanju testa vam želimo veliko uspeha.
            </p>
        @endif

        @if (!$has_identified)
            <br>
            <p><strong>Preden lahko pričnete z reševanjem testov, najprej vnesite vaše ime in priimek.</strong></p>

            <form action="{{ url('identify') }}" method="POST">
                <div class="input-group" style="float: left; width: 200px;">
                    <div class="input-group-addon" style="min-width: 72px;">Ime</div>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="input-group" style="float: left; width: 200px; margin-left: 15px;">
                    <div class="input-group-addon" style="min-width: 72px;">Priimek</div>
                    <input type="text" class="form-control" name="surname">
                </div>
                <input type="hidden" name="code" value="{{ $code }}">
                {!! csrf_field() !!}
                <button type="submit" class="btn btn-primary" style="float: left; margin-left: 15px;">Potrdi</button>
                <div style="clear: left"></div>
            </form>
        @endif

    </div>

    @if ($has_identified)
        <table class="table table-condensed" style="margin-top: 60px">
            <th>Test</th>
            <th>Št. vprašanj</th>
            <th>Status</th>

            @foreach($exams as $exam)
                <tr>
                    <td><a href="{{ url('/exam/'.base64_encode($exam['exam']->id.':'.$code))  }}">{{ $exam['exam']->book->title }}</a></td>
                    <td>{{ count($exam['exam']->questions()) }}</td>
                    <td style="color: {{ $exam['results']['questions_correct'] < $exam['results']['questions_total'] / 2 ? $exam['exam']->used ? 'red' : 'green' : 'green' }}">{{ $exam['exam']->used ? 'Pravilni/Nepravilni: '.$exam['results']['questions_correct'].'/'.$exam['results']['questions_total'] : "Open" }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>

@endsection