@layout('layouts.main')
@section('title')
Select Category | Alys Doc Manager
@endsection

@section('content')
<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li><a href="{{ URL::to_route('homepage');}}">Home</a> <span class="divider">/</span></li>
			<li><a href="{{ URL::to_route('docIndex'); }}">Library</a> <span class="divider">/</span></li>
			<li class="active">Select Category</li>
		</ul>
		{{ Form::open( 'library/new', '', array('class' => 'form-horizontal') ) }}
			<div class="control-group">
				{{ Form::label('category', 'Select category: ', array('class' => 'control-label') );}}
				<div class="controls">
					{{ Form::select('category',  $categories, ( isset( $current_cat ) ) ? $current_cat : '');}}
					{{ Form::submit('Select', array('class' => 'btn') );}}
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>
@endsection
