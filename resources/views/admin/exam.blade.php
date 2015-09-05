@extends('admin.parts.master')

@section('content')

<div class="container">

	@include('admin.parts.messages')

	<div class="well" style="margin-top: 20px;">
		<form class="form-group" action="{{ url('admin/generate-codes/')  }}" method="post">
			{!! csrf_field() !!}
			<div class="form-group" style="float: left; width: 90%;">
				<div class="input-group">
					<div class="input-group-addon">Number of codes</div>
					<input type="hidden" name="exam_id" value="{{ $exam->id  }}">
					<input type="text" class="form-control" name="num_codes">
				</div>
			</div>
			<button type="submit" class="btn btn-primary" style="float: right; width: 8%;">Generate</button>
			<div style="clear: both;"></div>
		</form>

		@foreach($exam->results as $result)
			<span style="float: left; width: 100px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ $result->code }}</span>
			<span style="float: left; width: 100px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ number_format($result->getResults()['score'], 1).'%' }}</span>
			<a href="{{ url('admin/remove-code/'.$result->id)  }}" style="float: left; margin-left: 5px;">Remove</a>
			<div style="clear: left;"></div>
		@endforeach
	</div>

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