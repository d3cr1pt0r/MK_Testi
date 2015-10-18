@extends('admin.parts.master')

@section('content')
<div class="container">

	@include('admin.parts.messages')

	<div class="panel panel-default" style="margin-top: 20px;">
		<div class="panel-heading">
			<span style="display: block; float: left; font-size: 22px;">{{ $exam->book->title }}</span>
			<div style="clear: both;"></div>
		</div>
		<div class="panel-body" style="padding: 0px;">
			<?php $i=0 ?>
			<?php $q=0 ?>
			@foreach($exam->tasks as $task)
				<?php $i++ ?>
				<div class="task">
					<h4 class="title" tid="{{ $task->id }}">{{ (string)$i.'. '.$task->title }}</h4>

					<div class="row">
					@foreach($task->questions as $question)
						<div class="question" qid="{{ $question->id }}">
							@if ($question->image_src != "")
								<a href="#" class="thumbnail" style="width: 200px; margin-left: 20px;">
									<img src="{{ URL::asset($question->image_src) }}">
								</a>
							@else
								<h5 style="margin-left: 20px;"><strong>{{ $question->title }}</strong></h5>
							@endif
							@if($question->type == 0)
								@if(count($question->answers) == 2)
									@foreach($question->answers as $answer)
										<div class="checkbox" style="padding-left: 5px; margin-left: 20px;">
											<label style="padding-left: 0px;">
												<input type="radio" name="tf-{{ $question->id }}" value="{{ $answer->title }}"> {{ $answer->title }}
											</label>
										</div>
									@endforeach
								@else
									@foreach($question->answers as $answer)
										<div class="checkbox" style="padding-left: 5px; margin-left: 20px;">
											<label>
												<input type="radio" name="tf-{{ $question->id }}" value="{{ $answer->title }}"> {{ $answer->title }}
												<!-- <input type="checkbox" value="{{ $answer->title }}"> {{ $answer->title }} -->
											</label>
										</div>
									@endforeach
								@endif
							@elseif ($question->type == 1)
								<input type="text" style="width: 100%;" value="">
							@endif
						</div>
					@endforeach
					</div>
				</div>
			@endforeach
			<form action="{{ url('evaluate') }}" method="post" id="submit-exam">
				{!! csrf_field() !!}
				<input type="hidden" name="exam_object" value="" id="exam_object_input">
				<input type="hidden" name="id" value="" id="id_input">
				<button id="done" class="btn btn-primary" style="width: 100%;">DONE</button>
			</form>
		</div>
	</div>
</div>

<script>
	$("#done").click(function(e) {
		e.preventDefault();
		var exam_object = {};

		$(".task").each(function(i, e) {
			var task = $($(this).find('.title'));
			var task_id = $(task).attr('tid');
			var questions = $(this).find('.question');
			var task_title = $(task).text();
			task_title = task_title.substring(3, task_title.length);

			exam_object[i] = {'task_id': task_id, 'questions': []}

			$(questions).each(function(j, e2) {
				var question_id = $(this).attr('qid');
				var answers = $(this).find('input');

				exam_object[i]['questions'][j] = ({'question_id': question_id, 'answers': []});

				$(answers).each(function(k, e3) {
					var answer = $(this);
					var answer_val = $(answer).val();
					if ($(answer).is(':checked') || $(answer).attr('type') == 'text') {
						exam_object[i]['questions'][j]['answers'].push(answer_val)
						console.log(answer_val);
					}
				});
			});
		});

		$("#id_input").val("{{ $id }}");
		$("#exam_object_input").val(JSON.stringify(exam_object));
		console.log($("#exam_object_input").val());
		$("#submit-exam").submit();

		{{--$.post( "{{ url('evaluate') }}", { exam_object: JSON.stringify(exam_object), id: "{{ $id }}", _token: "{{ csrf_token() }}" }).done(function( data ) {--}}
			{{--console.log(data);--}}
		{{--});--}}

	});
</script>

@endsection