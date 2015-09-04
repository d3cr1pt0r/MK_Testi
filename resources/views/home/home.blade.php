@extends('admin.parts.master')

@section('content')

<div class="container">
    @include('admin.parts.messages')
    <table class="table table-condensed" style="margin-top: 60px">
        <th>Exam</th>
        <th>Questions</th>
        <th>Time limit</th>
        <th>Status</th>

        @foreach($exams as $exam)
            <tr>
                <td><a href="{{ url('/exam/'.base64_encode($exam['exam']->id.':'.$code))  }}">{{ $exam['exam']->title }}</a></td>
                <td>{{ count($exam['exam']->questions()) }}</td>
                <td>None</td>
                <td>{{ $exam['exam']->used ? "Completed" : "Open" }} {{ $exam['results'] }}</td>
            </tr>
        @endforeach
    </table>
</div>

@endsection