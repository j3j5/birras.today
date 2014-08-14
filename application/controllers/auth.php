<?php

class Auth_Controller extends OneAuth\Auth\Controller {

	public $restful = TRUE;

	public function __construct() {
		parent::__construct();
		$this->filter('before', 'guest')->except(array('logout'));
		$this->filter('before', 'auth')->only(array('logout'));
		$this->filter('before', 'csrf')->on('post');
		Asset::container('header')->add('bootstrap_css', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
		Asset::container('header')->add('global', "css/global.css");

		Asset::container('footer')->add('jquery', '/code.jquery.com/jquery-1.11.1.min.js');
		Asset::container('footer')->add('bootstrap_js', "//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js");
	}

	/**
	 * Registration Page
	 */
	public function get_register() {
		return View::make('auth.register');
	}

	public function post_register() {
		// it a POST Request, you should validate the form

		$username =  strtolower(Input::get('username'));
		if(strcmp(Input::get('password'), Input::get('password2')) !== 0) {
			return Redirect::to('register')
			->with('flash_error', "Your password didn't match.")
			->withInput();
		}

		if(Input::get('password') === Input::get('password2')) {

			User::create(array(
				'username' => $username,
				'password' => Hash::make(Input::get('password')),
				'name' => Input::get('name'),
				'email' => Input::get('email'),
			));
			Event::fire('oneauth.sync', array($user->id));

			return OneAuth\Auth\Core::redirect('registered')->with('flash_notice', "You've been properly registered."); // redirect to /home
		} else {
			return Redirect::to('home/profile')->with('flash_notice', "Your passwords don't match!");
		}
	}

	/**
	 * Login Page
	 */
	public function get_login() {
		return View::make('auth.login');
	}

	public function post_login() {
		// it a POST Request, you should validate the form
		$login = array(
			'username' => Input::get('username'),
			'password' => Input::get('password')
		);

		$result = Auth::attempt($login);
		if ($result) {
			// get logged user id.
			$user_id = Auth::user()->id;

			// Synced it with oneauth, this will create a relationship between
			// `oneauth_clients` table with `users` table.
			return OneAuth\Auth\Core::redirect('logged_in'); // redirect to /home/profile
		}
		return View::make('auth.login');
	}

	public function get_logout() {
		// Log out
		Auth::logout();
		return Redirect::to('auth/login')->with('flash_notice', "You've been logged out!");
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
