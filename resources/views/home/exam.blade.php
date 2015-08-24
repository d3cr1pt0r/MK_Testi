@extends('admin.parts.master')

@section('content')

<div class="container">
	<div class="panel panel-default" style="margin-top: 20px;">
		<div class="panel-heading">{{ $exam->title }}</div>
		<div class="panel-body">
			<?php $i=0 ?>
			@foreach($exam->tasks as $task)
				<?php $i++ ?>
				<h4>{{ (string)$i.'. '.$task->title }}</h4>

				<div class="row">
				@foreach($task->questions as $question)
					@if($question->image_src != "")
						<div class="col-xs-6 col-md-3">
							<a href="#" class="thumbnail">
								<img src="{{ URL::asset($question->image_src) }}">
							</a>
							<input type="text" style="width: 100%;">
						</div>
					@else
						<div style="padding-left: 30px;">
							<h5><strong>{{ $question->title }}</strong></h5>
							@if($question->type == 0)
								@foreach($question->answers as $answer)
									<div class="checkbox" style="padding-left: 5px;">
										<label>
										<input type="checkbox" value=""> {{ $answer->title }}
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
			@endforeach
		</div>
	</div>
</div>

@endsection