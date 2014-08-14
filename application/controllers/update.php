<?php

class Update_Controller extends Base_Controller {

	public $restful = true;

	/**
	 * Registration Page
	 */
	public function get_place() {

		Asset::container('footer')->add('update', 'js/update.js');

		return View::make('update.place');
	}

	public function post_place() {
// 		var_dump(Input::all()); exit;
		$place_id = Input::get('place_id');

		if(empty($place_id)) {
			// Create a new place
			$place = new Place;
			$message = "New place " . $place->name . " stored.";
		} else {
			$place = Place::find($place_id);
			if(empty($place)) {
				return Redirect::to('update/place')->with('flash_error', "The place you're trying to update couldn't be found.");
			}
			$message = "The place " . $place->name . " has been updated.";
		}
		$place->name = Input::get('place_name');
		$place->description = Input::get('description');
		$place->avatar = Input::get('avatar');
		$place->address= Input::get('address');
		$place->has_terrace = Input::get('has_terrace');
		$place->website = Input::get('website');
		$place->map_link = Input::get('map_link');
		$place->added_by = Auth::check() ? Auth::user()->username : 'unknown';

		$result = $place->save();
		if($result) {
			return Redirect::to('update/place')->with('flash_notice', $message);
		} else {
			return Redirect::to('update/place')->with('flash_error', 'There was a server error.');
		}

	}


	/**
	 * View proper error message when authentication failed or cancelled by user
	 *
	 * @param   String      $provider       Provider name, e.g: twitter, facebook, google …
	 * @param   String      $e              Error Message
	 */
	protected function action_error($provider = null, $e = '') {
		return View::make('auth.errors', compact('provider', 'e'));
	}

}
