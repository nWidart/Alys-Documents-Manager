<?php

class Add_Fileurl_To_Documents_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('documents', function($table) {
			$table->string('file_url', 200);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('documents', function($table) {
			$table->drop_column('file_url');
		});
	}

}
