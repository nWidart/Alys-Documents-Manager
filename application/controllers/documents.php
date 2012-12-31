<?php

class Documents_Controller extends Base_Controller {

	public $restful = true;

	public function get_index()
	{
		return View::make('document.index');
	}

	public function post_index() {

	}

	public function get_show() {

	}

	/**
	 * Make a dropdown array for the parrent select box
	 */
	public function makeParrentDropdown( $lang_id, $catId ) {
		$document = Document::where_category_id( $catId )->order_by('position')->get();
		$parrentDropdown = array();
		$parrentDropdown[0] = "None";
		foreach ($document as $docu ) {
			if ( $docu->parrent_id == 0 && $docu->language_id == $lang_id )
			{
				$parrentDropdown[$docu->id] = $docu->name;
			}
		}
		return $parrentDropdown;
	}

	/**
	 * Counts the number of documents by language & category ID
	 * @param  int  $langId Language id
	 * @param  int  $catId  Category id
	 * @param  boolean $last   check if last has to be added ( last removed on edit page)
	 */
	public function countDocuments( $langId, $catId, $last = false, $parrentId = 0 ) {
		$doc_count = Document::where_category_id( $catId )->where_language_id( $langId )->where_parrent_id( $parrentId )->count();
		// Making arrays for all 4 languages
		$position = array();
		$doc_count = $doc_count + 1;
		for ( $i=1; $i < $doc_count ; $i++ ) {
			$position[$i] = $i;
		}
		if ( $last == true )
		{
			if ( count( $position ) != 1 )
				$position[$i] = 'End';
		}
		else {
			$position[$i] = 'End';
		}
		return $position;
	}

	public function get_edit( $id ) {
		$document = Document::where_category_id( $id )->order_by('position')->get();

		$parrentDropdown_fr = $this->makeParrentDropdown( 1, $id);
		$parrentDropdown_nl = $this->makeParrentDropdown( 2, $id);
		$parrentDropdown_en = $this->makeParrentDropdown( 3, $id);
		$parrentDropdown_ge = $this->makeParrentDropdown( 4, $id);

		$category_name = Category::find( $id );

		$position_fr = $this->countDocuments( 1, $id );
		$position_nl = $this->countDocuments( 2, $id );
		$position_en = $this->countDocuments( 3, $id );
		$position_ge = $this->countDocuments( 4, $id );

		$categories = Category::all();

		foreach ($categories as $category) {
			$data[$category->id] = $category->category;
		}


		return View::make('document.edit')
			->with( 'docs', $document )
			->with( 'cat_id', $id )
			->with( 'cat_name', $category_name->category )
			->with( 'doc_count_fr', $position_fr )
			->with( 'doc_count_nl', $position_nl )
			->with( 'doc_count_en', $position_en )
			->with( 'doc_count_ge', $position_ge )
			->with( 'parrentDropdown_fr', $parrentDropdown_fr )
			->with( 'parrentDropdown_nl', $parrentDropdown_nl )
			->with( 'parrentDropdown_en', $parrentDropdown_en )
			->with( 'parrentDropdown_ge', $parrentDropdown_ge )
			->with('categories', $data);
	}

	public function get_edit_doc( $catId, $docId, $parrentId=0 ) {
		$document = Document::where_category_id( $catId )->order_by('position')->get();

		$parrentDropdown_fr = $this->makeParrentDropdown( 1, $catId);
		$parrentDropdown_nl = $this->makeParrentDropdown( 2, $catId);
		$parrentDropdown_en = $this->makeParrentDropdown( 3, $catId);
		$parrentDropdown_ge = $this->makeParrentDropdown( 4, $catId);

		$category_name = Category::find( $catId );

		$position_fr = $this->countDocuments( 1, $catId, true, $parrentId );
		$position_nl = $this->countDocuments( 2, $catId, true, $parrentId );
		$position_en = $this->countDocuments( 3, $catId, true, $parrentId );
		$position_ge = $this->countDocuments( 4, $catId, true, $parrentId );

		$singleDoc = Document::where_id( $docId )->first();
		$categories = Category::all();

		foreach ($categories as $category) {
			$data[$category->id] = $category->category;
		}

		return View::make('document.edit')
			->with( 'docs', $document )
			->with( 'cat_id', $catId )
			->with( 'cat_name', $category_name->category )
			->with( 'doc_count_fr', $position_fr )
			->with( 'doc_count_nl', $position_nl )
			->with( 'doc_count_en', $position_en )
			->with( 'doc_count_ge', $position_ge )
			->with('singleDoc', $singleDoc)
			->with( 'parrentDropdown_fr', $parrentDropdown_fr )
			->with( 'parrentDropdown_nl', $parrentDropdown_nl )
			->with( 'parrentDropdown_en', $parrentDropdown_en )
			->with( 'parrentDropdown_ge', $parrentDropdown_ge )
			->with('categories', $data);
	}

	public function post_create() {
		// Fetching all documents to perform checks (positioning)
		$docs = Document::all();

		// Fetching the input fields
		$input = Input::all();
		$file = Input::file('document');

		// Applying validation Rules
		$rules = array(
			'name' => 'required',
			'document' => 'mimes:doc,docx,pdf,xls,ppt,jpg,png',
			'link' => 'url'
		);
		$v = Validator::make( $input, $rules );
		if ( $v->fails() )
		{
			return Redirect::to_route('edit_doc', $input['category'])->with_errors( $v )->with_input();
		}
		// Passed Validation
		$document = new Document();
		$document->name = $input['name'];
		$document->link = $input['link'];

		// Checking if protection is needed
		if ( isset( $input['protection'] ) ) {
			// What protection is needed
			if( !isset( $input['permissions'] ) )
			{
				Session::flash( 'protectionError', 'You have to select a protection type.' );
				return Redirect::to_route('edit_doc', $input['category'])->with_errors( $v )->with_input();
			}

			$document->permission = $input['permissions'];

			// Protection ON : move file to secure folder
			if ( !empty($file['name']) )
			{
				Input::upload('document', 'public/uploads/private', $file['name'] );
				$document->file_url = 'uploads/private/' . $file['name'];
				$document->file_name = $file['name'];
			}
		} else {
			$document->permission = 0;

			// Protection OFF : Move file to public folder
			if ( !empty($file['name']) )
			{
				Input::upload('document', 'public/uploads/public', $file['name'] );
				$document->file_url = 'uploads/public/' . $file['name'];
				$document->file_name = $file['name'];
			}
		}

		$document->category_id = $input['category'];
		$document->language_id = $input['language'];
		$document->section_id = 1;
		// Add the position after the updates.
		// $finalPosition = $input['position'] + 1;
		$document->parrent_id = $input['parrent'];

		// If parrent id != 0 , position has to be last item in submenu
		if ( $input['parrent'] != 0 )
		{
			$parDocCount = Document::getParrentCount( $input['parrent'], $input['category'], $input['language'] );
			$document->position = $parDocCount + 1;
		}
		else
		{
			$document->position = $input['position'];
		}

		// If the position in the DB already exists , perform query
		// UPDATE tbl_documents SET position = position + 1 WHERE position >=2
		$affected = DB::table('documents')
			->where('position', '>=', $input['position'])
			->where_category_id( $input['category'] )
			->where_language_id( $input['language'] )
			->where_parrent_id( $input['parrent'] )
			->update( array('position' => DB::raw('position + 1') ) );

		$document->save();
		Session::flash( 'successMsg', 'Document correctly added' );
		return Redirect::to_route( 'edit_doc', $input['category'] );
	}

	public function get_new($cat="") {
		$categories = Category::all();
		$data[0] = "Select Product";
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
			'name' => 'required',
			'document' => 'mimes:doc,docx,pdf,xls,ppt,jpg'
		);
		$v = Validator::make( $input, $rules );
		if ( $v->fails() )
		{
			return Redirect::to_route('edit_doc', $input['category'])->with_errors( $v )->with_input();
		}
		$document = Document::find( $id );

		$document->name = $input['name'];

		if ( !empty( $file['name'] ) )
		{
			// Check if file name has changed == new file
			if ( $document->file_name != $file['name'] )
			{
				if ( isset( $input['protection'] ) ) {
					Input::upload('document', 'public/uploads/private', $file['name'] );
					$document->file_url = 'uploads/private/' . $file['name'];
					$document->file_name = $file['name'];
				}
				else
				{
					Input::upload('document', 'public/uploads/public', $file['name'] );
					$document->file_url = 'uploads/public/' . $file['name'];
					$document->file_name = $file['name'];
				}
			}
		}
		else // File name empty, moving file?
		{
			if ( !empty( $document->file_url ) )
			{
				$permission = ( isset( $input['protection'] ) ) ? $input['permissions'] : 0;
				$oldProtection = ( $document->permission > 0 ) ? 1 : 0;
				$newProtection = ( $permission > 0 ) ? 1 : 0;
				// If moving private file to public
				if ( $oldProtection == 1 && $newProtection == 0 )
				{
					$fileUrl = path('public') . 'uploads/public/' . $document->file_name;
					File::move( path('public') . $document->file_url, $fileUrl );

					$document->file_url = 'uploads/public/' . $document->file_name;
				}
				// If moving public to private
				// oldProtection = 0 && newProtection = 1
				else
				{
					$fileUrl = path('public') . 'uploads/private/' . $document->file_name;
					File::move( path('public') . $document->file_url, $fileUrl );
					$document->file_url = 'uploads/private/' . $document->file_name;
				}
			}
		}

		// Checking if protection is needed
		if ( isset( $input['protection'] ) ) {
			// What protection is needed
			$document->permission = $input['permissions'];
		} else {
			$document->permission = 0;
		}

		$document->category_id = $input['category'];
		$document->language_id = $input['language'];
		$document->section_id = 1;

		// Checking the parrents before & after
		$currentParrent = $document->parrent_id;
		$document->parrent_id = $input['parrent'];

		// Checking the positions before & after
		$currentPosition = $document->position;
		$newPosition = $input['position'];

		// If the item becomes a sub item of a parrent
		//  The position has to be changed to the last
		//  item in that submenu
		if ( $currentParrent == 0 && $input['parrent'] != 0 )
		{
			// Count the number of documents inside the submenu
			$parDocCount = Document::where_parrent_id( $input['parrent'] )->count();
			$document->position = $parDocCount + 1;

			$affected = DB::table('documents')
				->where('position', '>=', $currentPosition )
				->where_category_id( $document->category_id )
				->where_language_id( $document->language_id )
				->update( array('position' => DB::raw('position - 1') ) );
		}
		elseif ( $currentParrent != 0 && $input['parrent'] == 0 )
		{
			// Count the number of base menu items (parrent : 0)
			$parDocCount = Document::getParrentCount( $input['parrent'], $document->category_id, $document->language_id );
			$document->position = $parDocCount + 2;
		}
		else
		{
			if ( $currentPosition != $newPosition )
			{
				// Bouche trou
				$affected = DB::table('documents')
					->where('position', '>', $currentPosition )
					->where_category_id( $document->category_id )
					->where_language_id( $document->language_id )
					->where_parrent_id( $document->parrent_id )
					->update( array('position' => DB::raw('position - 1') ) );
				// crée trou
				$affected = DB::table('documents')
					->where('position', '>=', $newPosition )
					->where_category_id( $document->category_id )
					->where_language_id( $document->language_id )
					->where_parrent_id( $document->parrent_id )
					->update( array('position' => DB::raw('position + 1') ) );

				$document->position = $newPosition;
			}
		}

		$document->save();
		Session::flash('successMsg', 'Updated!');
		return Redirect::to_route( 'edit_doc', $input['category'] );
	}

	public function get_destroy( $id ) {
		$deletedDoc = Document::where_id( $id )->first();

		// Delete the database row
		Document::find( $id )->delete();

		// Count how many items there are with parrent 0
		$parDocCount = Document::getParrentCount( 0, $deletedDoc->category_id, $deletedDoc->language_id );

		// If deleted a parent with children
		// Children becomes parents
		$parentChildren = Document::where_parrent_id( $deletedDoc->id )->get();
		$i = 2;
		foreach ($parentChildren as $child) {
			DB::table('documents')
				->where( 'id', '=', $child->id )
				->update( array( 'parrent_id' => 0, 'position' => $parDocCount + $i ) );
			$i++;
		}

		// Delete the related file
		File::delete( path('public') . $deletedDoc->file_url );


		// Update the positions
		$affected = DB::table('documents')
			->where('position', '>', $deletedDoc->position )
			->where_category_id( $deletedDoc->category_id )
			->where_language_id( $deletedDoc->language_id )
			->where_parrent_id( $deletedDoc->parrent_id )
			->update( array('position' => DB::raw('position - 1') ) );

		Session::flash('errorMsg', 'Document destroyed!');
		return Redirect::back();
	}

}
