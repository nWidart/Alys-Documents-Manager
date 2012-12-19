<?php

class Language extends Eloquent
{
	public static $table = "languages";
	public function documents()
	{
		return $this->has_many_and_belongs_to('Document', 'document_language');
	}
}
