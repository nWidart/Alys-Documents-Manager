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
}
