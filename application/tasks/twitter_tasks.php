<?php

class Twitter_Tasks {

	protected $allowed_people;
	protected $access_token;
	protected $access_secret;
	protected $api;
	protected $twitter_task;
	protected $last_processed;

	public function __construct() {
		Bundle::start('oneauth');
		$token = Config::get('application.access_token');
		$this->access_token = $token['token'];
		$this->access_secret = $token['secret'];
		$this->api =  new Twitterapi($this->access_token, $this->access_secret);
		$this->allowed_people= $this->get_allowed_users();
	}

	protected function get_allowed_users() {
		return $this->api->get_friends();
	}

	protected function get_last_processed() {
		$twitter_event = Twitter_Event::find($this->twitter_task);
		if(isset($twitter_event->last_id) && !empty($twitter_event->last_id)) {
			$this->last_processed = $twitter_event->last_id;
		} else {
			$twitter_event = new Twitter_Event;
			$twitter_event->task = $this->twitter_task;
		}
		return $twitter_event;
	}

	protected function extract_data_from_tweet($matches) {
		$bar_name = $time = $map_link = '';
		foreach($matches AS $index => $match) {
			switch ($match) {
				case 'comingto':
					if(!empty($matches[$index+1])) {
						$bar_name = $matches[$index+1];
					} else {
						$error = 'Empty location.';
					}
					break;
				case 'time':
					$timestamp = strtotime($matches[$index+1]);
					if(!empty($timestamp)) {
						$time = $timestamp;
					} else {
						$error = 'Not a valid datetime format.';
					}
					break;
				case 'map':
					///TODO: Check this is actually a valid URL
					$map_link = $matches[$index+1];
					break;
				default:
					break;
			}
		}
		if(!empty($bar_name) && !empty($time)) {
			return array('bar_name' => $bar_name, 'time' => $time, 'map' => $map_link);
		}
		return array('error' => $error);
	}

	protected function 	store_appointment(array $appointment) {
		if(!isset($appointment['time'],
			$appointment['place'],
			$appointment['added_by'],
			$appointment['tweet'],
			$appointment['tweet_id'])
		) {
			return FALSE;
		}

		$appoinment = new Appointment;
		$appoinment->a_date_ts = $appointment['time'];
		$appoinment->place_id = $appointment['place'];
		$appoinment->added_by = $appointment['added_by'];
		$appoinment->tweet= $appointment['tweet'];
		$appoinment->tweet_id = $appointment['tweet_id'];

		$appoinment->save();
		return TRUE;
	}

}
