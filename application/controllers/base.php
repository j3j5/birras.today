<?php

class Base_Controller extends Controller {

	/**
	 * Add the general styles and scripts.
	 *
	 */
	public function __construct() {
		Asset::container('header')->add('bootstrap_css', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
		Asset::container('header')->style('open_sans', "/fonts.googleapis.com/css?family=Open+Sans:400,800,700,600,600italic,400italic");
		Asset::container('header')->add('global', "css/global.css");

		Asset::container('footer')->add('jquery', '/code.jquery.com/jquery-1.11.1.min.js');
		Asset::container('footer')->add('bootstrap_js', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js");
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
		return Response::error('404');
	}

}
