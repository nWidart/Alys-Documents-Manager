@layout('layouts.main')
@section('title')
Select Category | Alys Doc Manager
@endsection

@section('content')
<div class="row-fluid">
	<div class="span12">
		{{ Form::open( 'library/new', '', array('class' => 'form-inline') ) }}
			<div class="control-group">
				<div class="controls">
					{{ Form::select('category',  $categories, ( isset( $current_cat ) ) ? $current_cat : '', array('id' => 'categorySelect') );}}
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>
@endsection
