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
			$table->tinyint('public')->default(1);
			$table->date('appointment_date');
			$table->timestamps();
			$table->index(array('appointment_date', 'added_by'));
			$table->index(array('updated_at'));
			$table->index('place_id');
			$table->foreign('place_id')->references('id')->on('places');
			$table->engine = 'InnoDB';
		});


		// Insert some stuff
		DB::table('appointments')->insert(
			array(
				'place_id' => 1,
				'a_date_ts' => time(),
				'added_by' => 'birrastoday',
				'tweet' => "@birrastoday #comingto Sound Garden #time " . date("H:i") . " #map http://t.co/VY3l8U3HWh",
				'tweet_id' => '500040015003783170',
				'public' => '1',
				'appointment_date' => date('Y-m-d'),
			)
		);
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
