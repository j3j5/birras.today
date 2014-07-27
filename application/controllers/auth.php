<?php

class Auth_Controller extends OneAuth\Auth\Controller {

	/**
	 * Registration Page
	 */
	public function action_register() {
		if ($_POST) {
			// it a POST Request, you should validate the form

			if(Input::get('password') === Input::get('password2')) {
				$user           = new User;
				$user->username = Input::get('username');
				$user->password = Hash::make(Input::get('password'));
				$user->email    = Input::get('email');

				try {
					$user->save();
				} catch(Exception $e) {
				    var_dump($e->getMessage()); exit;
				}

				Event::fire('oneauth.sync', array($user->id));

				return OneAuth\Auth\Core::redirect('registered')->with('flash_notice', "You've been properly registered."); // redirect to /home
			} else {
				return Redirect::to('/')->with('flash_notice', "Your passwords don't match!");
			}
		}

		return View::make('auth.register');
	}

	/**
	 * Login Page
	 */
	public function action_login() {
		if ($_POST) {
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
				Event::fire('oneauth.sync', array($user_id));
				return OneAuth\Auth\Core::redirect('logged_in'); // redirect to /home
			}
		}
		return View::make('auth.login');
	}

	protected function action_logout() {
		// Log out
		Auth::logout();
		return Redirect::to('/')->with('flash_notice', "You've been logged out!");
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
