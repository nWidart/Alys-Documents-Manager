<?php

class Document extends Eloquent
{
	public static $table = "documents";
	public function category()
	{
		return $this->has_one('Category');
	}
	public function languages()
	{
		return $this->has_many_and_belongs_to('Language');
	}

	public static function getParrentCount( $parrent_id, $category_id, $language_id )
	{
		return Document::where_parrent_id( $parrent_id )
			->where_category_id( $category_id )
			->where_language_id( $language_id )
			->count();
	}
}
