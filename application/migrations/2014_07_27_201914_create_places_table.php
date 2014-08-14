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

		// Insert some stuff
		DB::table('places')->insert(
			array(
				'name' => 'Sound Garden',
				'description' => 'A nice place with a great terrace.',
				'avatar' => 'http://www.cafesoundgarden.nl/wp-content/uploads/2011/02/GardenBar.jpg',
				'address' => 'Marnixstraat 164 – 166, 1016 TG Amsterdam',
				'has_terrace' => 1,
				'website' => 'http://www.cafesoundgarden.nl/',
				'map_link' => 'https://www.google.com/maps/place/Caf%C3%A9+Sound+Garden/@52.3714178,4.8826227,14z/data=!4m2!3m1!1s0x0:0x11799ea3abd32f86',
				'added_by' => 'test',
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
		Schema::drop('places');
	}

}



