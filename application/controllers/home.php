<?php

class Home_Controller extends Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public function action_index()
	{
		$appointments = Appointment::get_events4today();
		$view_appointments = array();
		foreach($appointments AS $app) {
			$place = Place::find($app->place_id);
			$clean_app = $this->clean_appointment($app);
			$clean_place = $this->clean_place($place->original);
		    $view_appointments[] = array_merge($clean_app, $clean_place);
		}

		return View::make('home.index', array('appointments' => $view_appointments));
	}

	private function clean_appointment($app) {
		$clean_app = (array)$app;
		unset($clean_app['id']);
		unset($clean_app['place_id']);
		unset($clean_app['created_at']);
		$clean_app['appointment_name'] = $clean_app['name'];
		unset($clean_app['name']);
		return $clean_app;
	}

	private function clean_place($place) {
		$clean_place = (array)$place;
		unset($clean_place['id']);
		unset($clean_place['created_at']);
		$clean_place['place_name'] = $clean_place['name'];
		unset($clean_place['name']);
		return $clean_place;
	}

}
