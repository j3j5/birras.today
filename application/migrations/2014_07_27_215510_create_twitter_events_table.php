<?php

class Create_Twitter_Events_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('twitter_events', function($table)
		{
			$table->create();
			$table->string('task');
			$table->string('last_id', 64);
			$table->timestamps();
			$table->primary('task');
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
		Schema::drop('twitter_events');
	}

}
