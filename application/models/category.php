<?php

class Category extends Eloquent
{
	public static $table = "categories";
	public function document()
	{
		return $this->belongs_to('Document');
	}
}
