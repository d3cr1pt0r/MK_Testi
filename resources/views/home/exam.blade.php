@extends('admin.parts.master')

@section('content')

<div class="container">

	@include('admin.parts.messages')

	<div class="panel panel-default" style="margin-top: 20px;">
		<div class="panel-heading">
			<span style="display: block; float: left; font-size: 22px;">{{ $exam->title }}</span>
			<input type="text" class="form-control" name="name" placeholder="Surname" style="float: right; width: 130px; margin-left: 5px;">
			<input type="text" class="form-control" name="surname" placeholder="Name" style="float: right; width: 130px;">
			<div style="clear: both;"></div>
		</div>
		<div class="panel-body" style="padding: 0px;">
			<?php $i=0 ?>
			@foreach($exam->tasks as $task)
				<?php $i++ ?>
				<div class="task">
					<h4 class="title" tid="{{ $task->id }}">{{ (string)$i.'. '.$task->title }}</h4>

					<div class="row">
					@foreach($task->questions as $question)
						@if($question->image_src != "")
							<div class="col-xs-6 col-md-3 question" qid="{{ $question->id }}">
								<a href="#" class="thumbnail">
									<img src="{{ URL::asset($question->image_src) }}">
								</a>
								<input type="text" style="width: 100%;">
							</div>
						@else
							<div class="question" style="padding-left: 30px;" qid="{{ $question->id }}">
								<h5><strong>{{ $question->title }}</strong></h5>
								@if($question->type == 0)
									@foreach($question->answers as $answer)
										<div class="checkbox" style="padding-left: 5px;">
											<label>
												<input type="checkbox" value="{{ $answer->title }}"> {{ $answer->title }}
											</label>
										</div>
									@endforeach
								@elseif ($question->type == 1)
									<input type="text" value="">
								@endif
							</div>
						@endif
					@endforeach
					</div>
				</div>
			@endforeach
			<button type="submit" id="done" class="btn btn-primary" style="width: 100%;">DONE</button>
		</div>
	</div>
</div>

<script>
	$("#done").click(function() {
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

		$.post( "{{ url('evaluate') }}", { exam_object: JSON.stringify(exam_object), id: "{{ $id }}", _token: "{{ csrf_token() }}" }).done(function( data ) {
			console.log(data);
		});

	});
</script>

@endsection