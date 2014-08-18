<?php

class Base_Controller extends Controller {

	/**
	 * Add the general styles and scripts.
	 *
	 */
	public function __construct() {
		Birras::include_basic_scripts();
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		Asset::container('header')->add('404', "css/404.css");
		return Response::error('404');
	}

}
