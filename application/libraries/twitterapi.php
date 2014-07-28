<?php

/**
 * This class allows to make OAuth requests to Twitter using
 * the OneAuth bundle and extending its functionality.
 *
 */

use OneAuth\OAuth\Provider\Twitter AS Twitter,
	OneAuth\OAuth\Request;

class Twitterapi extends Twitter {

	/**
	 * The OAuth consumer to sign the requests
	 */
	private $consumer;

	/**
	 * The OAuth access token to sign the requests
	 */
	private $access_token;

	/**
	 * Contains the last API call.
	 */
	private $last_api_call;

	/**
	 * Set up the API root URL.
	 */
	private $host = "https://api.twitter.com/1.1/";

	/**
	 * Decode returned json data.
	 */
	private $decode_json = TRUE;

	/**
	 * Return json data as array.
	 */
	private $return_array = TRUE;

	/**
	 * Debug helpers
	 */
	function lastAPICall() { return $this->last_api_call; }


	public function __construct($access_token, $access_token_secret) {
		parent::__construct();

		$consumer_key = Config::get("oneauth::api.providers.twitter.key");
		$consumer_secret = Config::get("oneauth::api.providers.twitter.secret");

		$this->consumer = \OneAuth\OAuth\Consumer::make(array('key' => $consumer_key, 'secret' => $consumer_secret));
		$this->access_token = \OneAuth\OAuth\Token::make('access', array('access_token' => $access_token, 'secret' => $access_token_secret) );

		$this->last_api_call = '';
	}

	/**
	 * Magic method to implement the different HTTP methods.
	 * Currently just GET and POST are supported.
	 *
	 * @param String $method The name of the function indicates the method to be used.
	 * @param Array $parameters Array containing the provided parameters for the function as follows:
	 * 							-) [0] -> URL
	 * 							-) [0] -> Array containing extra parameters. ex.- array('count'=> 100)
	 * 							-)
	 *
	 * @return Array|Bool
	 *
	 */
	public function __call($method, $parameters) {
		$valid_methods = array('get', 'post');
		if( !in_array(strtolower($method), $valid_methods) ) {
			return FALSE;
		}
		// Check URL for request
		if(!isset($parameters[0]) OR empty($parameters[0]) OR !is_string($parameters[0])) {
			return FALSE;
		} else {
			$url = $parameters[0];
		}
		// Check parameters
		if(!isset($parameters[1]) OR empty($parameters[1]) OR !is_array($parameters[1])) {
			$params = array();
		} else {
			$params = $parameters[1];
		}
		if(empty($this->access_token)) {
			cli_print('empty access token!');
			return FALSE;
		}
		$this->default_options($params);

		$this->last_api_call = $url;

		return $this->do_request($url, $params, strtoupper($method));
	}

	private function default_options(&$parameters) {
		// If not provided, use default consumer_key and oauth_token
		if(!isset($parameters['oauth_consumer_key']) OR empty($parameters['oauth_consumer_key']) ) {
				$parameters['oauth_consumer_key'] = $this->consumer->key;
				$parameters['oauth_token'] = $this->access_token->access_token;
			}
	}

	public function get_friends() {
		$friends = $this->get('friends/ids.json', array( 'screen_name' => 'birrastoday', 'count' => 5000 ));
		if(isset($friends['ids'])) {
			return $friends['ids'];
		}
		return array();
	}

	public function get_followers() {

	}

	public function post_tweet($text, $extra_options = array()) {
		if(mb_strlen($text) > 140) {
			$text = mb_substr(0, 137) . '...';
		}

		$options = array(
			'status' => $text,
		);
		if(!empty($extra_options) && is_array($extra_options)) {
			$options = array_merge($options, $extra_options);
		}
		$result = $this->post('statuses/update.json', $options);
		if(!empty($result)) {
			cli_print('Tweeted: ' . $options['status']);
			return TRUE;
		} else {
			cli_print('Fail to reply.');
			return FALSE;
		}
	}

	public function post_dm($text, $to_user, $extra_options = array()) {
		if(mb_strlen($text) > 140) {
			$text = mb_substr(0, 137) . '...';
		}

		$options = array(
			'text' => $text,
			'user_id' => $to_user,
		);
		if(!empty($extra_options) && is_array($extra_options)) {
			$options = array_merge($options, $extra_options);
		}
		$result = $this->post('direct_messages/new.json', $options);
		if(!empty($result)) {
			cli_print('DM to ' . $to_user . ': ' . $options['text']);
			return TRUE;
		} else {
			cli_print('Fail to reply the DM.');
			return FALSE;
		}
	}

	/**
	 * Make the actual request to Twitter.
	 *
	 * @param String $url
	 * @param Array $parameters
	 * @param String $method GET or POST are the current supported methods
	 *
	 * @return Array|Object|Bool
	 */
	private function do_request($url, $parameters, $method = 'GET') {
		if(empty($this->access_token)) {
			return FALSE;
		}
		// Create a new $method request with the required parameters
		$request = Request::make('resource', $method, $this->host . $url, $parameters);
		//
		// Sign the request using the consumer and token
		$request->sign($this->signature, $this->consumer, $this->access_token);

		try {
			$result = $request->execute();
		} catch(\Exception $e) {
			$result = FALSE;
			cli_print($e->getMessage());
		}

		if(empty($result)) {
			return FALSE;
		}

		if($this->decode_json) {
			$result = json_decode($result, $this->return_array);
		}

		return $result;
	}
}
