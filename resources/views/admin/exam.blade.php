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
			<a class="navbar-brand" href="#">MK - Exams</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="{{url('/admin')}}">Home</a></li>
				<li><a href="{{url('/books')}}">Books</a></li>
				<li><a href="{{url('/users')}}">Users</a></li>
				<!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
                -->
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{{url('admin/logout')}}">Logout</a></li>
				<!--<li class="active"><a href="./">Static top <span class="sr-only">(current)</span></a></li> -->
				<!--<li><a href="../navbar-fixed-top/">Fixed top</a></li> -->
			</ul>
		</div><!--/.nav-collapse -->
	</div>
	@include('admin.parts.messages')
</nav>

<div class="container">
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
			<a href="{{ url('code/'.$result->code) }}"><span style="float: left; width: 100px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ $result->code }}</span></a>
			<span style="float: left; width: 70px; color: {{ $result->used ? $result->getResults()['score'] < 50 ? 'red' : 'green' : 'blue' }}">{{ number_format($result->getResults()['score'], 1).'%' }}</span>
			<a href="{{ url('admin/remove-code/'.$result->id)  }}" style="float: left; margin-left: 5px;">Remove</a>
			<a href="{{ url('admin/reset-code/'.$result->id)  }}" style="float: left; margin-left: 15px;">Reset</a>
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