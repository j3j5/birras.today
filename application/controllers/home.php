<?php

class Home_Controller extends Base_Controller {

	public function __construct() {
		parent::__construct();
		Asset::container('header')->style('grolsch_font', '/fonts.googleapis.com/css?family=Parisienne');
		$this->filter('before', 'auth')->only(array('profile'));
	}

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

		///TODO: Optimize this
		$top_places = array();
		$db_places = Place::all();
		foreach($db_places AS $key=>$place) {
			$top_places[$key] = array('place' => $place->id, 'count' => $place->count_appointments(), 'place' => $place);
		    $places[$key] = $place->id;
			$count[$key] = $place->count_appointments();
		}
		// Sort the data with volume descending, edition ascending
		// Add $top_places as the last parameter, to sort by the common key
		array_multisort($count, SORT_DESC, $places, SORT_ASC, $top_places);
		unset($count);
		unset($places);

		Asset::container('header')->add('index', 'css/index.css');
		Asset::container('header')->add('piwik', 'js/piwik.js');
		Asset::container('footer')->add('prefix-free', '/leaverou.github.io/prefixfree/prefixfree.min.js');

		return View::make('home.index', array('appointments' => $view_appointments, 'top_places' => $top_places, 'show_logo_glass' => mt_rand(0,1) == 1));
	}

	public function action_profile() {
		return View::make('home.profile');
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
		unset($clean_place['added_by']);
		return $clean_place;
	}

}
