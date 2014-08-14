<?php

class Create_Users_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('username');
			$table->string('password');
			$table->string('name');
			$table->string('email');
			$table->timestamps();
		});

		// Insert some stuff
		DB::table('users')->insert(
			array(
				'username' => 'test',
				'password' => Hash::make('test'),
				'name' => 'Mr. Test',
				'email' => 'testing@domain.com',
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
		Schema::drop('users');
	}

}
