<?php

class Documents_Controller extends Base_Controller {

	public $restful = true;

	//
	public function get_index()
	{
		// Selecting Documents with the category
		//$test = Category::find(1)->document()->first();
		//dd($test->category->category);

		//$test = Language::find(1)->documents()->first();
		//$test = Document::find(1)->languages()->first();

		return View::make('document.index');
	}

	public function post_index() {

	}

	public function get_show() {

	}

	public function get_edit( $id ) {
		$document = Document::where_category_id( $id )->order_by('position')->get();
		$category_name = Category::find( $id );
		// Counting the document items per category per language
		$doc_count_fr = Document::where_category_id( $id )->where_language_id( 1 )->count();
		$doc_count_nl = Document::where_category_id( $id )->where_language_id( 2 )->count();
		$doc_count_en = Document::where_category_id( $id )->where_language_id( 3 )->count();
		$doc_count_ge = Document::where_category_id( $id )->where_language_id( 4 )->count();

		// Making arrays for all 4 languages
		$position_fr = array();
		$doc_count_fr = $doc_count_fr + 1;
		for ( $i=1; $i < $doc_count_fr ; $i++ ) {
			$position_fr[$i] = $i;
		}
		$position_fr[$i] = 'End';

		$position_nl = array();
		$doc_count_nl = $doc_count_nl + 1;
		for ( $i=1; $i < $doc_count_nl ; $i++ ) {
			$position_nl[$i] = $i;
		}
		$position_nl[$i] = 'End';

		$position_en = array();
		$doc_count_en = $doc_count_en +1;
		for ( $i=1; $i < $doc_count_en ; $i++ ) {
			$position_en[$i] = $i;
		}
		$position_en[$i] = 'End';

		$position_ge = array();
		$doc_count_ge = $doc_count_ge + 1;
		for ( $i=1; $i < $doc_count_ge ; $i++ ) {
			$position_ge[$i] = $i;
		}
		$position_ge[$i] = 'End';

		return View::make('document.edit')
			->with( 'docs', $document )
			->with( 'cat_id', $id )
			->with( 'cat_name', $category_name->category )
			->with( 'doc_count_fr', $position_fr )
			->with( 'doc_count_nl', $position_nl )
			->with( 'doc_count_en', $position_en )
			->with( 'doc_count_ge', $position_ge );
	}

	public function get_edit_doc( $catId, $docId ) {
		$document = Document::where_category_id( $catId )->order_by('position')->get();

		$category_name = Category::find( $catId );
		// Counting the document items per category per language
		$doc_count_fr = Document::where_category_id( $catId )->where_language_id( 1 )->count();
		$doc_count_nl = Document::where_category_id( $catId )->where_language_id( 2 )->count();
		$doc_count_en = Document::where_category_id( $catId )->where_language_id( 3 )->count();
		$doc_count_ge = Document::where_category_id( $catId )->where_language_id( 4 )->count();

		// Making arrays for all 4 languages
		$position_fr = array();
		$doc_count_fr = $doc_count_fr + 1;
		for ( $i=1; $i < $doc_count_fr ; $i++ ) {
			$position_fr[$i] = $i;
		}
		$position_fr[$i] = 'End';

		$position_nl = array();
		$doc_count_nl = $doc_count_nl + 1;
		for ( $i=1; $i < $doc_count_nl ; $i++ ) {
			$position_nl[$i] = $i;
		}
		$position_nl[$i] = 'End';

		$position_en = array();
		$doc_count_en = $doc_count_en +1;
		for ( $i=1; $i < $doc_count_en ; $i++ ) {
			$position_en[$i] = $i;
		}
		$position_en[$i] = 'End';

		$position_ge = array();
		$doc_count_ge = $doc_count_ge + 1;
		for ( $i=1; $i < $doc_count_ge ; $i++ ) {
			$position_ge[$i] = $i;
		}
		$position_ge[$i] = 'End';

		$singleDoc = Document::where_id( $docId )->first();
		return View::make('document.edit')
			->with( 'docs', $document )
			->with( 'cat_id', $catId )
			->with( 'cat_name', $category_name->category )
			->with( 'doc_count_fr', $position_fr )
			->with( 'doc_count_nl', $position_nl )
			->with( 'doc_count_en', $position_en )
			->with( 'doc_count_ge', $position_ge )
			->with('singleDoc', $singleDoc);
	}

	public function post_create() {
		// Fetching all documents to perform checks (positioning)
		$docs = Document::all();

		// Fetching the input fields
		$input = Input::all();
		$file = Input::file('document');

		// Applying validation Rules
		$rules = array(
			'name' => 'required|min:10',
			'document' => 'mimes:doc,docx,pdf,xls,ppt,jpg'
		);
		$v = Validator::make( $input, $rules );
		if ( $v->fails() )
		{
			return Redirect::to_route('edit_doc', $input['category'])->with_errors( $v )->with_input();
		}
		// Passed Validation
		$document = new Document();
		$document->name = $input['name'];

		// Checking if protection is needed
		if ( isset( $input['protection'] ) ) {
			// What protection is needed
			if ( isset( $input['docs_perm'] ) && isset( $input['soft_perm'] ) )
				$document->permission = 3;
			elseif ( isset( $input['docs_perm'] ) )
				$document->permission = 1;
			elseif ( isset( $input['soft_perm'] ) )
				$document->permission = 2;
		} else {
			$document->permission = 0;
		}

		$document->category_id = $input['category'];
		$document->language_id = $input['language'];
		$document->section_id = 1;
		// Add the position after the updates.
		$finalPosition = $input['position'] + 1;
		$document->position = $finalPosition;

		// If the position in the DB already exists , perform query
		// UPDATE tbl_documents SET position = position + 1 WHERE position >=2
		$affected = DB::table('documents')
			->where('position', '>=', $input['position'])
			->update( array('position' => DB::raw('position + 1') ) );

		if ( !empty($file['name']) )
		{
			Input::upload('document', 'public/uploads', $file['name'] );
			$document->file_url = 'uploads/' . $file['name'];
		}
		$document->save();

		return Redirect::to_route( 'edit_doc', $input['category'] )->with( 'successMsg', 'Category Updated!' );
	}

	public function get_new($cat="") {
		$categories = Category::all();
		foreach ($categories as $category) {
			$data[$category->id] = $category->category;
		}

		return View::make('document.new')
			->with('categories', $data);
	}

	public function post_new() {
		$cat_id = Input::get('category');
		return Redirect::to_route('edit_doc', $cat_id);
	}

	public function post_update( $id ) {
		// Fetching all documents to perform checks (positioning)
		$docs = Document::all();

		// Fetching the input fields
		$input = Input::all();
		$file = Input::file('document');

		// Applying validation Rules
		$rules = array(
			'name' => 'required|min:10',
			'document' => 'mimes:doc,docx,pdf,xls,ppt,jpg'
		);
		$v = Validator::make( $input, $rules );
		if ( $v->fails() )
		{
			return Redirect::to_route('edit_doc', $input['category'])->with_errors( $v )->with_input();
		}
		$document = Document::find( $id );
		$document->name = $input['name'];

		// Checking if protection is needed
		if ( isset( $input['protection'] ) ) {
			// What protection is needed
			if ( isset( $input['docs_perm'] ) && isset( $input['soft_perm'] ) )
				$document->permission = 3;
			elseif ( isset( $input['docs_perm'] ) )
				$document->permission = 1;
			elseif ( isset( $input['soft_perm'] ) )
				$document->permission = 2;
		} else {
			$document->permission = 0;
		}

		$document->category_id = $input['category'];
		$document->language_id = $input['language'];
		$document->section_id = 1;
		// Add the position after the updates.
		$finalPosition = $input['position'] + 1;
		$document->position = $finalPosition;

		// If the position in the DB already exists , perform query
		// UPDATE tbl_documents SET position = position + 1 WHERE position >=2
		$affected = DB::table('documents')
			->where('position', '>=', $input['position'])
			->update( array('position' => DB::raw('position + 1') ) );

		if ( !empty($file['name']) )
		{
			Input::upload('document', 'public/uploads', $file['name'] );
			$document->file_url = 'uploads/' . $file['name'];
		}
		$document->save();
		return Redirect::to_route( 'edit_doc', $input['category'] )->with( 'successMsg', 'Category Updated!' );
	}

	public function get_destroy( $id ) {
		Document::find( $id )->delete();
		return Redirect::back()->with('successMsg', 'Document correctly deleted.');
	}

}
