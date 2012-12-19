@layout('layouts.main')
@section('title')
Edit category | Alys Doc Manager
@endsection

@section('content')

<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li><a href="{{ URL::to_route('homepage');}}">Home</a> <span class="divider">/</span></li>
			<li><a href="{{ URL::to_route('docIndex'); }}">Library</a> <span class="divider">/</span></li>
			<li><a href="{{ URL::to_route('new_doc'); }}">Select Category</a> <span class="divider">/</span></li>
			<li class="active">Manage Category</li>
		</ul>

		<h3>You're editing: <span class="label label-info">{{ $cat_name }}</span></h3>

		@if ( !empty( $successMsg ) )
		{{ $successMsg }}
			<script type="text/javascript">
				$(document).ready( function() {
					alertSuccess( "<?php echo $successMsg; ?>" )
				});
			</script>
		@endif


		<!-- FIRST Tab section (preview section) -->
		<ul id="langShowTabs" class="nav nav-tabs">
			<li class="active">
				<a href="#fr" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">Fr</a>
			</li>
			<li><a href="#nl" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">Nl</a></li>
			<li><a href="#en" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">En</a></li>
			<li><a href="#de" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">De</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="fr">
				<h4>Preview your list</h4>
				@if ( !empty( $docs ) )
				<ol>
				@foreach($docs as $doc)
					@if ($doc->language_id == 1)
						<li><a href="<?php echo constant('SITE_PATH') . '/public/'; ?>{{ $doc->file_url }}">{{ $doc->name }}</a>
							<a href="{{ URL::to_route('edit_doc_info', array($cat_id, $doc->id) ) }}"><i class="icon-pencil"></i></a>
							<a href="{{ URL::to_route('destroy_doc', $doc->id) }}"><i class="icon-trash"></i></a>
						</li>
					@endif
				 @endforeach
				 </ol>
				 @endif
				 <hr>
					<h4>Edit your list</h4>
					@if ( !isset( $singleDoc ) )
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
						{{ Form::hidden('category', $cat_id) }}
						{{ Form::hidden('language', '1') }}
						@if( $errors->has('document') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('document', 'Upload document: ', array('class' => 'control-label') ) }}
							<div class="controls">
								{{ Form::file('document') }}
							</div>
						</div>

						@if( $errors->has('name') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('name', 'Document name: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
									{{ Form::text( 'name', $singleDoc->name ) }}
								@else
									{{ Form::text( 'name', Input::old('name') ) }}
								@endif
								@if( $errors->has('name') )
								<span class="help-inline">{{ $errors->first('name') }}</span>
								@endif
							</div>
						</div>

						<div class="control-group">
							{{ Form::label('protection', 'Protection: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<div class="important-toggle-button">
									@if ( isset( $singleDoc ) )
										@if ( $singleDoc->permission > 0 )
											{{ Form::checkbox('protection', '', 'on') }}
										@else
											{{ Form::checkbox('protection', '', '') }}
										@endif
									@else
										{{ Form::checkbox('protection', '', '') }}
									@endif
								</div>
							</div>
						</div>
						<div class="control-group perm-group">
							{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
							<div class="controls">
									<label class="checkbox">
										@if ( isset( $singleDoc ) )
											<?php
												echo ( $singleDoc->permission == 1 OR $singleDoc->permission == 3 )
													? Form::checkbox('docs_perm','','checked') . " Documentation"
													: Form::checkbox('docs_perm') . "Documentation";
											?>
										@else
											{{ Form::checkbox('docs_perm') }} Documentation
										@endif
									</label>
									<label class="checkbox">
										@if ( isset( $singleDoc ) )
											<?php
												echo ( $singleDoc->permission == 2 OR $singleDoc->permission == 3 )
													? Form::checkbox('soft_perm','','checked') . " Software"
													: Form::checkbox('soft_perm') . "Software";
											?>
										@else
											{{ Form::checkbox('soft_perm') }} Software
										@endif
									</label>
							</div>
						</div>
						<div class="control-group">
							{{ Form::label('position', 'Position: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
								{{ Form::select('position',  $doc_count_fr, $singleDoc->position );}}
								@else
								{{ Form::select('position',  $doc_count_fr, '' );}}
								@endif
							</div>
						</div>
						<div class="control-group">
							<div class="form-actions">
								<div class="btn-group">
								{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
								<a href="{{ URL::to_route( 'edit_doc', $cat_id ) }}" class="btn btn-info">New document</a>
								</div>
							</div>
						</div>
					{{ Form::close() }}
			</div><!-- end FR -->

			<div class="tab-pane" id="nl">
				<h4>Preview your list</h4>
				@if ( !empty( $docs ) )
				<ol>
				@foreach($docs as $doc)
					@if ($doc->language_id == 2)
						<li><a href="<?php echo constant('SITE_PATH') . '/public/'; ?>{{ $doc->file_url }}">{{ $doc->name }}</a>
							<a href="{{ URL::to_route('edit_doc_info', array($cat_id, $doc->id) ) }}"><i class="icon-pencil"></i></a>
							<a href="{{ URL::to_route('destroy_doc', $doc->id) }}"><i class="icon-trash"></i></a>
						</li>
					@endif
				 @endforeach
				 </ol>
				 @endif
				 <hr>
				 <h4>Edit your list</h4>
					@if ( !isset( $singleDoc ) )
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
					{{ Form::hidden('category', $cat_id) }}
					{{ Form::hidden('language', '2') }}
					@if( $errors->has('document') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('document', 'Upload document: ', array('class' => 'control-label') ) }}
						<div class="controls">
							{{ Form::file('document') }}
						</div>
					</div>

					@if( $errors->has('name') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('name', 'Document name: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
								{{ Form::text( 'name', $singleDoc->name ) }}
							@else
								{{ Form::text( 'name', Input::old('name') ) }}
							@endif
							@if( $errors->has('name') )
							<span class="help-inline">{{ $errors->first('name') }}</span>
							@endif
						</div>
					</div>

					<div class="control-group">
						{{ Form::label('protection', 'Protection: ', array('class' => 'control-label') ) }}
						<div class="controls">
							<div class="important-toggle-button">
								@if ( isset( $singleDoc ) )
									@if ( $singleDoc->permission > 0 )
										{{ Form::checkbox('protection', '', 'on') }}
									@else
										{{ Form::checkbox('protection', '', '') }}
									@endif
								@else
									{{ Form::checkbox('protection', '', '') }}
								@endif
							</div>
						</div>
					</div>
					<div class="control-group perm-group">
						{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
						<div class="controls">
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 1 OR $singleDoc->permission == 3 )
												? Form::checkbox('docs_perm','','checked') . " Documentation"
												: Form::checkbox('docs_perm') . "Documentation";
										?>
									@else
										{{ Form::checkbox('docs_perm') }} Documentation
									@endif
								</label>
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 2 OR $singleDoc->permission == 3 )
												? Form::checkbox('soft_perm','','checked') . " Software"
												: Form::checkbox('soft_perm') . "Software";
										?>
									@else
										{{ Form::checkbox('soft_perm') }} Software
									@endif
								</label>
						</div>
					</div>
					<div class="control-group">
						{{ Form::label('position', 'Position: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
							{{ Form::select('position',  $doc_count_nl, $singleDoc->position );}}
							@else
							{{ Form::select('position',  $doc_count_nl, '' );}}
							@endif
						</div>
					</div>
					<div class="control-group">
						<div class="form-actions">
								<div class="btn-group">
								{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
								<a href="{{ URL::to_route( 'edit_doc', $cat_id ) }}" class="btn btn-info">New document</a>
								</div>
							</div>
					</div>
				{{ Form::close() }}
			</div><!-- end NL -->

			<div class="tab-pane" id="en">
				<h4>Preview your list</h4>
				@if ( !empty( $docs ) )
				<ol>
				@foreach($docs as $doc)
					@if ($doc->language_id == 3)
						<li><a href="<?php echo constant('SITE_PATH') . '/public/'; ?>{{ $doc->file_url }}">{{ $doc->name }}</a>
							<a href="{{ URL::to_route('edit_doc_info', array($cat_id, $doc->id) ) }}"><i class="icon-pencil"></i></a>
							<a href="{{ URL::to_route('destroy_doc', $doc->id) }}"><i class="icon-trash"></i></a>
						</li>
					@endif
				 @endforeach
				 </ol>
				 @endif
				 <hr>
				 <h4>Edit your list</h4>
					@if ( !isset( $singleDoc ) )
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
					{{ Form::hidden('category', $cat_id) }}
					{{ Form::hidden('language', '3') }}
					@if( $errors->has('document') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('document', 'Upload document: ', array('class' => 'control-label') ) }}
						<div class="controls">
							{{ Form::file('document') }}
						</div>
					</div>

					@if( $errors->has('name') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('name', 'Document name: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
								{{ Form::text( 'name', $singleDoc->name ) }}
							@else
								{{ Form::text( 'name', Input::old('name') ) }}
							@endif
							@if( $errors->has('name') )
							<span class="help-inline">{{ $errors->first('name') }}</span>
							@endif
						</div>
					</div>

					<div class="control-group">
						{{ Form::label('protection', 'Protection: ', array('class' => 'control-label') ) }}
						<div class="controls">
							<div class="important-toggle-button">
								@if ( isset( $singleDoc ) )
									@if ( $singleDoc->permission > 0 )
										{{ Form::checkbox('protection', '', 'on') }}
									@else
										{{ Form::checkbox('protection', '', '') }}
									@endif
								@else
									{{ Form::checkbox('protection', '', '') }}
								@endif
							</div>
						</div>
					</div>
					<div class="control-group perm-group">
						{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
						<div class="controls">
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 1 OR $singleDoc->permission == 3 )
												? Form::checkbox('docs_perm','','checked') . " Documentation"
												: Form::checkbox('docs_perm') . "Documentation";
										?>
									@else
										{{ Form::checkbox('docs_perm') }} Documentation
									@endif
								</label>
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 2 OR $singleDoc->permission == 3 )
												? Form::checkbox('soft_perm','','checked') . " Software"
												: Form::checkbox('soft_perm') . "Software";
										?>
									@else
										{{ Form::checkbox('soft_perm') }} Software
									@endif
								</label>
						</div>
					</div>
					<div class="control-group">
						{{ Form::label('position', 'Position: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
							{{ Form::select('position',  $doc_count_en, $singleDoc->position );}}
							@else
							{{ Form::select('position',  $doc_count_en, '' );}}
							@endif
						</div>
					</div>
					<div class="control-group">
						<div class="form-actions">
							<div class="btn-group">
							{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
							<a href="{{ URL::to_route( 'edit_doc', $cat_id ) }}" class="btn btn-info">New document</a>
							</div>
						</div>
					</div>
				{{ Form::close() }}
			</div><!-- end EN -->

			<div class="tab-pane" id="de">
				<h4>Preview your list</h4>
				@if ( !empty( $docs ) )
				<ol>
				@foreach($docs as $doc)
					@if ($doc->language_id == 4)
						<li><a href="<?php echo constant('SITE_PATH') . '/public/'; ?>{{ $doc->file_url }}">{{ $doc->name }}</a>
							<a href="{{ URL::to_route('edit_doc_info', array($cat_id, $doc->id) ) }}"><i class="icon-pencil"></i></a>
							<a href="{{ URL::to_route('destroy_doc', $doc->id) }}"><i class="icon-trash"></i></a>
						</li>
					@endif
				 @endforeach
				 </ol>
				 @endif
				 <hr>
				 <h4>Edit your list</h4>
					@if ( !isset( $singleDoc ) )
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
					{{ Form::hidden('category', $cat_id) }}
					{{ Form::hidden('language', '4') }}
					@if( $errors->has('document') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('document', 'Upload document: ', array('class' => 'control-label') ) }}
						<div class="controls">
							{{ Form::file('document') }}
						</div>
					</div>

					@if( $errors->has('name') )
					<div class="control-group error">
					@else
					<div class="control-group">
					@endif
						{{ Form::label('name', 'Document name: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
								{{ Form::text( 'name', $singleDoc->name ) }}
							@else
								{{ Form::text( 'name', Input::old('name') ) }}
							@endif
							@if( $errors->has('name') )
							<span class="help-inline">{{ $errors->first('name') }}</span>
							@endif
						</div>
					</div>

					<div class="control-group">
						{{ Form::label('protection', 'Protection: ', array('class' => 'control-label') ) }}
						<div class="controls">
							<div class="important-toggle-button">
								@if ( isset( $singleDoc ) )
									@if ( $singleDoc->permission > 0 )
										{{ Form::checkbox('protection', '', 'on') }}
									@else
										{{ Form::checkbox('protection', '', '') }}
									@endif
								@else
									{{ Form::checkbox('protection', '', '') }}
								@endif
							</div>
						</div>
					</div>
					<div class="control-group perm-group">
						{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
						<div class="controls">
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 1 OR $singleDoc->permission == 3 )
												? Form::checkbox('docs_perm','','checked') . " Documentation"
												: Form::checkbox('docs_perm') . "Documentation";
										?>
									@else
										{{ Form::checkbox('docs_perm') }} Documentation
									@endif
								</label>
								<label class="checkbox">
									@if ( isset( $singleDoc ) )
										<?php
											echo ( $singleDoc->permission == 2 OR $singleDoc->permission == 3 )
												? Form::checkbox('soft_perm','','checked') . " Software"
												: Form::checkbox('soft_perm') . "Software";
										?>
									@else
										{{ Form::checkbox('soft_perm') }} Software
									@endif
								</label>
						</div>
					</div>
					<div class="control-group">
						{{ Form::label('position', 'Position: ', array('class' => 'control-label') ) }}
						<div class="controls">
							@if ( isset( $singleDoc ) )
							{{ Form::select('position',  $doc_count_ge, $singleDoc->position );}}
							@else
							{{ Form::select('position',  $doc_count_ge, '' );}}
							@endif
						</div>
					</div>
					<div class="control-group">
						<div class="form-actions">
							<div class="btn-group">
							{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
							<a href="{{ URL::to_route( 'edit_doc', $cat_id ) }}" class="btn btn-info">New document</a>
							</div>
						</div>
					</div>
				{{ Form::close() }}
			</div><!-- end EN -->
		</div><!-- END .tab-content -->
		<!-- END First tab section -->


	</div>
</div>
@endsection

@section('scripts')
@if ( isset( $singleDoc ) )
	@if ( $singleDoc->language_id == 1 )
	<script type="text/javascript">
		$('#langShowTabs a[href="#fr"]').tab('show');
	</script>
	@endif
	@if ( $singleDoc->language_id == 2 )
	<script type="text/javascript">
		$('#langShowTabs a[href="#nl"]').tab('show');
	</script>
	@endif
	@if ( $singleDoc->language_id == 3 )
	<script type="text/javascript">
		$('#langShowTabs a[href="#en"]').tab('show');
	</script>
	@endif
	@if ( $singleDoc->language_id == 4 )
	<script type="text/javascript">
		$('#langShowTabs a[href="#de"]').tab('show');
	</script>
	@endif
@endif
<script type="text/javascript">
// $('#langShowTabs a').click(function (e) {
// 	e.preventDefault();
// 	$(this).tab('show');
// });
// $('#langShowTabs a[href="#nl"]').tab('show');

$('.important-toggle-button').toggleButtons({
	style: {
		enabled : 'danger',
		disabled: 'success'
	},
	transitionspeed: "500%",
	onChange: function ($el, status, e) {
		// if ( status == true) {
		// 	$('.perm-group').animate({
		// 		opacity: "show"
		// 	});
		// }
		// if ( status == false) {
		// 	$('.perm-group').animate({
		// 		opacity: "hide"
		// 	});
		// }
	}
});
// $('.perm-group').hide();

</script>
@endsection
