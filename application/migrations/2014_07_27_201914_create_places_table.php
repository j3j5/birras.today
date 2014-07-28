<?php

class Create_Places_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('places', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('name', 100);
			$table->string('description', 255)->nullable();
			$table->string('avatar', 255)->nullable();
			$table->string('address', 255)->nullable();
			$table->tinyint('has_terrace')->nullable();
			$table->string('website', 255)->nullable();
			$table->text('map_link')->nullable();
			$table->string('added_by', 50);
			$table->timestamps();
			$table->index('name');
			$table->index('has_terrace');
			$table->index('updated_at');
			$table->engine = 'InnoDB';
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('places');
	}

}



