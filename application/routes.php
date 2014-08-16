<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/
Route::get('/', 'home@index');

Route::get('test', function() {
	$appointment = Appointment::where_appointment_date('2014-08-07')->where_added_by('javi_negrae')->first();
	var_dump($appointment);
});

Route::controller('home');

Route::controller('auth');
Route::controller('update');

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/

Event::listen('404', function()
{
	Asset::container('header')->add('bootstrap_css', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
	Asset::container('header')->add('global', "css/global.css");
	Asset::container('header')->add('404', "css/404.css");

	Asset::container('footer')->add('jquery', '/code.jquery.com/jquery-1.11.1.min.js');
	Asset::container('footer')->add('bootstrap_js', "//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js");
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('auth', function()
{
	if (Auth::guest()) {
		return Redirect::to('auth/login')->with('flash_error', 'You must be logged in to view this page!');
	}
});

Route::filter('guest', function()
{
	if (Auth::check()) {
		return Redirect::to('home')->with('flash_notice', 'You are already logged in!');
	}
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});
