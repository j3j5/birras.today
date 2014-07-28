<?php

class Create_Appointments_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('appointments', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('name')->nullable();
			$table->integer('place_id')->unsigned()->nullable();
			$table->integer('a_date_ts')->unsigned();
			$table->string('added_by', 50);
			$table->string('tweet', 140);
			$table->string('tweet_id', 64);
			$table->text('link')->nullable();
			$table->blob('votes')->nullable();
			$table->tinyint('enabled')->default(1);
			$table->timestamps();
			$table->index(array('a_date_ts', 'enabled'));
			$table->index(array('updated_at', 'enabled'));
			$table->index('place_id');
			$table->foreign('place_id')->references('id')->on('places');
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
		Schema::drop('appointments');
	}

}
