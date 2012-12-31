@layout('layouts.main')
@section('title')
Edit category | Alys Doc Manager
@endsection

@section('content')

<div class="row-fluid">
	<div class="span12">
		{{ Form::open( 'library/new', '', array('class' => 'form-inline') ) }}
			<div class="control-group">
				<div class="controls">
					{{ Form::select( 'category',  $categories, $cat_id, array('id' => 'categorySelect') ) }}
					<a href="{{ URL::to_route( 'edit_doc', $cat_id ) }}" class="btn btn-info">New Document</a>
				</div>
			</div>
		{{ Form::close() }}
		<h3>You're editing: {{ $cat_name }}</h3>

<!-- <a href="#" rel="tooltip" data-original-title="Default tooltip">?</a> -->

<?php
/**
 * formatTree
 *
 * Creates an array within a parrent & language
 */
function formatTree( $tree, $parent, $lang ) {
	$tree2 = array();
	foreach($tree as $i => $item){
		if ( $item['language_id'] == $lang ) {
			if($item['parrent_id'] == $parent){
				 $tree2[$item['id']] = $item;
				 $tree2[$item['id']]['submenu'] = formatTree($tree, $item['id'], $lang);
			}
		}
	}

	return $tree2;
}

/**
 * getDocumentsList
 *
 * Builds the list of all the documents without a category
 * @param  array $tree2  the generated tree
 * @param  int $cat_id category id
 * @return string         the ordered list
 */
function getDocumentsList( $tree2, $cat_id )
{
	echo "<div class='span6'>";
	echo "<ol>";
	echo "<h4>Preview your list</h4>";
	foreach ($tree2 as $key => $value) {
		echo "<li>
		<a href=" . URL::to_route('edit_doc_info', array($cat_id, $value['id'], $value['parrent_id'] ) ) . " rel='tooltip' data-original-title='Edit Document' ><i class='icon-pencil'></i></a>
		<a href=" . URL::to_route('destroy_doc', $value['id']) . " rel='tooltip' data-original-title='Delete Document. Permanent!' ><i class='icon-trash'></i></a> ";
		// If no file_url, no link
		if ( !empty( $value['file_url'] ) )
			echo "<a href='" . constant('SITE_PATH') . "/public/" . $value['file_url']. "'> " . $value['name'] . "</a> ";
		else
		{
			if ( !empty( $value['link'] ) )
				echo "<a href='" . $value['link'] . "'> " . $value['name'] . "</a> ";
			else
				echo $value['name'];
		}

		if ( $value['permission'] == 1 ) {
			echo "<i class='icon-lock'></i>";
		}
		elseif ( $value['permission'] == 2 )
		{
			echo "<i class='icon-exclamation-sign'></i>";
		}
		if ( !empty( $value['submenu'] ) )
		{
			echo "<ol>";
			foreach ($value['submenu'] as $key) {
				echo "<li>
					<a href=" . URL::to_route('edit_doc_info', array($cat_id, $key['id'], $key['parrent_id'] ) ) . " rel='tooltip' data-original-title='Edit Document' ><i class='icon-pencil'></i></a>
					<a href=" . URL::to_route('destroy_doc', $key['id']) . " rel='tooltip' data-original-title='Delete Document. Permanent!' ><i class='icon-trash'></i></a> ";

				if ( !empty( $key['file_name'] ) )
					echo "<a href='" . constant('SITE_PATH') . "/public/" . $key['file_url']. "'> " . $key['name'] . "</a> ";
				else
					if ( !empty( $key['link'] ) )
						echo "<a href='" . $key['link'] . "'> " . $key['name'] . "</a> ";
					else
						echo $key['name'];

				if ( $key['permission'] == 1 ) {
					echo "<i class='icon-lock'></i>";
				}
				elseif ( $key['permission'] == 2 )
				{
					echo "<i class='icon-exclamation-sign'></i>";
				}
			}
			echo "</ol>";
		}
		echo "</li>";
	}
	echo "</ol>";
	echo "</div>";
}
?>
		<!-- FIRST Tab section (preview section) -->
		<ul id="langShowTabs" class="nav nav-tabs">
		@if ( empty( $docs ) )
			<li class="active">
				<a href="#fr" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">Fr</a>
			</li>
			<li><a href="#nl" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">Nl</a></li>
			<li><a href="#en" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">En</a></li>
			<li><a href="#de" data-toggle="tab" onclick="clear_form_elements('.form-horizontal')">De</a></li>
		@else
			<li class="active">
				<a href="#fr" data-toggle="tab">Fr</a>
			</li>
			<li><a href="#nl" data-toggle="tab">Nl</a></li>
			<li><a href="#en" data-toggle="tab">En</a></li>
			<li><a href="#de" data-toggle="tab">De</a></li>
		@endif
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="fr">

				@if ( !empty( $docs ) )
					<?php
						$tree = array();
						foreach ($docs as $key => $value) {
							$tree[] = $value->attributes;
						}
						$tree2 = formatTree($tree, 0, 1); // tree, parrent, lang
						// Building the list
						getDocumentsList( $tree2, $cat_id );
					?>
				@else
				<p class="lead">No Documents have been added for the category yet. Start by adding a Document below.</p>
				@endif

				 <div class="span6">
					@if ( !isset( $singleDoc ) )
						<h4>Add new document</h4>
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						<h4>Edit document</h4>
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
						{{ Form::hidden('category', $cat_id) }}
						{{ Form::hidden('language', '1') }}

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

						@if( $errors->has('link') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('link', 'Link address: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="If a link is specified, the document link wont be used. Do not upload documents when using links."><i class="icon-question-sign"></i></a>
								@if ( isset( $singleDoc ) )
									{{ Form::text( 'link', $singleDoc->link ) }}
								@else
									{{ Form::text( 'link', Input::old('link') ) }}
								@endif
								@if( $errors->has('link') )
								<span class="help-inline">{{ $errors->first('link') }}</span>
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
										{{ Form::checkbox('protection', '', '' ) }}
									@endif
								</div>
								@if ( Session::has('protectionError') )
									<p class="text-error">{{ Session::get('protectionError') }}</p>
								@endif
							</div>
						</div>
						<div class="control-group perm-group">
							{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
							<div class="controls">
									<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Protection has to be set to ON for permissions to work."><i class="icon-question-sign"></i></a>

									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 1 )
												{{ Form::radio('permissions', '1', '1');}} Documentation
											@else
												{{ Form::radio('permissions', '1', '0');}} Documentation
											@endif
										@else
											{{ Form::radio('permissions', '1', '0');}} Documentation
										@endif
									</label>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 2 )
												{{ Form::radio('permissions', '2', '1');}} Liste de prix
											@else
												{{ Form::radio('permissions', '2', '0');}} Liste de prix
											@endif
										@else
											{{ Form::radio('permissions', '2', '0');}} Liste de prix
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
								<?php $lastPosition_fr = count( $doc_count_fr ); ?>
								{{ Form::select('position',  $doc_count_fr, $lastPosition_fr );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose where the document is placed. If the parrent is changed, position will always be set to last."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							{{ Form::label('parrent', 'Parrent: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
								{{ Form::select('parrent',  $parrentDropdown_fr, $singleDoc->parrent_id );}}
								@else
								{{ Form::select('parrent',  $parrentDropdown_fr, '' );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose the parrent of the document. None means the main list."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							<div class="form-actions">
								<div class="btn-group">
									{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
								</div>
							</div>
						</div>
					{{ Form::close() }}
				</div><!-- End SPAN6  -->
			</div><!-- end FR -->

			<div class="tab-pane" id="nl">
				@if ( !empty( $docs ) )
				<?php
					$tree = array();
					foreach ($docs as $key => $value) {
						$tree[] = $value->attributes;
					}
					$tree2 = formatTree($tree, 0, 2); // tree, parrent, lang
					// Building the list
					getDocumentsList( $tree2, $cat_id );
				?>
				@else
				<p class="lead">No Documents have been added for the category yet. Start by adding a Document below.</p>
				 @endif
				 <div class="span6">
					@if ( !isset( $singleDoc ) )
						<h4>Add new document</h4>
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						<h4>Edit document</h4>
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
						{{ Form::hidden('category', $cat_id) }}
						{{ Form::hidden('language', '2') }}

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

						@if( $errors->has('link') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('link', 'Link address: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="If a link is specified, the document link wont be used. Do not upload documents when using links."><i class="icon-question-sign"></i></a>
								@if ( isset( $singleDoc ) )
									{{ Form::text( 'link', $singleDoc->link ) }}
								@else
									{{ Form::text( 'link', Input::old('link') ) }}
								@endif
								@if( $errors->has('link') )
								<span class="help-inline">{{ $errors->first('link') }}</span>
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
								@if ( Session::has('protectionError') )
									<p class="text-error">{{ Session::get('protectionError') }}</p>
								@endif
							</div>
						</div>
						<div class="control-group perm-group">
							{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Protection has to be set to ON for permissions to work."><i class="icon-question-sign"></i></a>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 1 )
												{{ Form::radio('permissions', '1', '1');}} Documentation
											@else
												{{ Form::radio('permissions', '1', '0');}} Documentation
											@endif
										@else
											{{ Form::radio('permissions', '1', '0');}} Documentation
										@endif
									</label>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 2 )
												{{ Form::radio('permissions', '2', '1');}} Liste de prix
											@else
												{{ Form::radio('permissions', '2', '0');}} Liste de prix
											@endif
										@else
											{{ Form::radio('permissions', '2', '0');}} Liste de prix
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
								<?php $lastPosition_nl = count( $doc_count_nl ); ?>
								{{ Form::select('position',  $doc_count_nl, $lastPosition_nl );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose where the document is placed. If the parrent is changed, position will always be set to last."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							{{ Form::label('parrent', 'Parrent: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
								{{ Form::select('parrent',  $parrentDropdown_nl, $singleDoc->parrent_id );}}
								@else
								{{ Form::select('parrent',  $parrentDropdown_nl, '' );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose the parrent of the document. None means the main list."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							<div class="form-actions">
									<div class="btn-group">
										{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
									</div>
								</div>
						</div>
					{{ Form::close() }}
				</div><!-- end SPAN6 -->
			</div><!-- end NL -->

			<div class="tab-pane" id="en">
				@if ( !empty( $docs ) )
				<?php
					$tree = array();
					foreach ($docs as $key => $value) {
						$tree[] = $value->attributes;
					}
					$tree2 = formatTree($tree, 0, 3); // tree, parrent, lang
					// Building the list
					getDocumentsList( $tree2, $cat_id );
				?>
				@else
				<p class="lead">No Documents have been added for the category yet. Start by adding a Document below.</p>
				 @endif
				 <div class="span6">
					 @if ( !isset( $singleDoc ) )
						<h4>Add new document</h4>
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						<h4>Edit document</h4>
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
						{{ Form::hidden('category', $cat_id) }}
						{{ Form::hidden('language', '3') }}
						@if ( isset( $singleDoc ) )
						{{ Form::hidden('parrent', $singleDoc->parrent_id ) }}
						@else
						@endif

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

						@if( $errors->has('link') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('link', 'Link address: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="If a link is specified, the document link wont be used. Do not upload documents when using links."><i class="icon-question-sign"></i></a>
								@if ( isset( $singleDoc ) )
									{{ Form::text( 'link', $singleDoc->link ) }}
								@else
									{{ Form::text( 'link', Input::old('link') ) }}
								@endif
								@if( $errors->has('link') )
								<span class="help-inline">{{ $errors->first('link') }}</span>
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
								@if ( Session::has('protectionError') )
									<p class="text-error">{{ Session::get('protectionError') }}</p>
								@endif
							</div>
						</div>
						<div class="control-group perm-group">
							{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Protection has to be set to ON for permissions to work."><i class="icon-question-sign"></i></a>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 1 )
												{{ Form::radio('permissions', '1', '1');}} Documentation
											@else
												{{ Form::radio('permissions', '1', '0');}} Documentation
											@endif
										@else
											{{ Form::radio('permissions', '1', '0');}} Documentation
										@endif
									</label>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 2 )
												{{ Form::radio('permissions', '2', '1');}} Liste de prix
											@else
												{{ Form::radio('permissions', '2', '0');}} Liste de prix
											@endif
										@else
											{{ Form::radio('permissions', '2', '0');}} Liste de prix
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
								<?php $lastPosition_en = count( $doc_count_en ); ?>
								{{ Form::select('position',  $doc_count_en, $lastPosition_en );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose where the document is placed. If the parrent is changed, position will always be set to last."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							{{ Form::label('parrent', 'Parrent: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
								{{ Form::select('parrent',  $parrentDropdown_en, $singleDoc->parrent_id );}}
								@else
								{{ Form::select('parrent',  $parrentDropdown_en, '' );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose the parrent of the document. None means the main list."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							<div class="form-actions">
								<div class="btn-group">
									{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
								</div>
							</div>
						</div>
					{{ Form::close() }}
				</div><!-- end SPAN6 -->
			</div><!-- end EN -->

			<div class="tab-pane" id="de">
				@if ( !empty( $docs ) )
				<?php
					$tree = array();
					foreach ($docs as $key => $value) {
						$tree[] = $value->attributes;
					}
					$tree2 = formatTree($tree, 0, 4); // tree, parrent, lang
					// Building the list
					getDocumentsList( $tree2, $cat_id );
				?>
				@else
				<p class="lead">No Documents have been added for the category yet. Start by adding a Document below.</p>
				 @endif
				 <div class="span6">
					@if ( !isset( $singleDoc ) )
						<h4>Add new document</h4>
						{{ Form::open_for_files('library/create', '', array('class' => 'form-horizontal') ) }}
					@else
						<h4>Edit document</h4>
						{{ Form::open_for_files("library/update/" . $singleDoc->id , '', array('class' => 'form-horizontal') ) }}
					@endif
						{{ Form::hidden('category', $cat_id) }}
						{{ Form::hidden('language', '4') }}

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

						@if( $errors->has('link') )
						<div class="control-group error">
						@else
						<div class="control-group">
						@endif
							{{ Form::label('link', 'Link address: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="If a link is specified, the document link wont be used. Do not upload documents when using links."><i class="icon-question-sign"></i></a>
								@if ( isset( $singleDoc ) )
									{{ Form::text( 'link', $singleDoc->link ) }}
								@else
									{{ Form::text( 'link', Input::old('link') ) }}
								@endif
								@if( $errors->has('link') )
								<span class="help-inline">{{ $errors->first('link') }}</span>
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
								@if ( Session::has('protectionError') )
									<p class="text-error">{{ Session::get('protectionError') }}</p>
								@endif
							</div>
						</div>
						<div class="control-group perm-group">
							{{ Form::label('permissions', 'Permissions: ', array('class' => 'control-label') ) }}
							<div class="controls">
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Protection has to be set to ON for permissions to work."><i class="icon-question-sign"></i></a>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 1 )
												{{ Form::radio('permissions', '1', '1');}} Documentation
											@else
												{{ Form::radio('permissions', '1', '0');}} Documentation
											@endif
										@else
											{{ Form::radio('permissions', '1', '0');}} Documentation
										@endif
									</label>
									<label class="radio">
										@if ( isset( $singleDoc ) )
											@if ( $singleDoc->permission == 2 )
												{{ Form::radio('permissions', '2', '1');}} Liste de prix
											@else
												{{ Form::radio('permissions', '2', '0');}} Liste de prix
											@endif
										@else
											{{ Form::radio('permissions', '2', '0');}} Liste de prix
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
								<?php $lastPosition_ge = count( $doc_count_ge ); ?>
								{{ Form::select('position',  $doc_count_ge, $lastPosition_ge );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose where the document is placed. If the parrent is changed, position will always be set to last."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							{{ Form::label('parrent', 'Parrent: ', array('class' => 'control-label') ) }}
							<div class="controls">
								@if ( isset( $singleDoc ) )
								{{ Form::select('parrent',  $parrentDropdown_ge, $singleDoc->parrent_id );}}
								@else
								{{ Form::select('parrent',  $parrentDropdown_ge, '' );}}
								@endif
								<a href="" class="pull-right btn btn-small" rel="popover" data-title="Help" data-content="Choose the parrent of the document. None means the main list."><i class="icon-question-sign"></i></a>
							</div>
						</div>
						<div class="control-group">
							<div class="form-actions">
								<div class="btn-group">
									{{ Form::submit('Save', array('class' => 'btn btn-success') );}}
								</div>
							</div>
						</div>
					{{ Form::close() }}
				</div><!-- end SPAN6 -->
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
$('.important-toggle-button').toggleButtons({
	style: {
		enabled : 'danger',
		disabled: 'success'
	},
	transitionspeed: "500%",
	onChange: function ($el, status, e) {
	}
});

</script>
@endsection
