<?php

class Create_Place_Aliases_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('place_aliases', function($table)
		{
			$table->create();
			$table->string('alias', 100);
			$table->integer('place_id')->unsigned();
			$table->timestamps();
			$table->primary('alias');
			$table->index('place_id');
// 			$table->foreign('place_id')->references('id')->on('places');
			$table->engine = 'InnoDB';
		});

		// Insert some stuff
		DB::table('place_aliases')->insert(
			array(
				'alias' => 'SoundGarden',
				'place_id' => 1,
			)
		);

		DB::table('place_aliases')->insert(
			array(
				'alias' => 'The Sound Garden',
				'place_id' => 1,
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
		Schema::drop('places');
	}

}
