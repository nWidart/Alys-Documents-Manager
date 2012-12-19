<?php

class Add_Timestamps_To_Languages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tbl_languages', function($table) {
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tbl_languages', function($table) {
			$table->drop_column('created_at');
			$table->drop_column('updated_at');
		});
	}

}
