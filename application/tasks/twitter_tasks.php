<?php

class Twitter_Tasks {

	protected $allowed_people;
	protected $access_token;
	protected $access_secret;
	protected $api;
	protected $twitter_task;
	protected $last_processed;
	protected $user_key;

	public function __construct() {
		Bundle::start('oneauth');
		$token = Config::get('application.access_token');
		$this->access_token = $token['token'];
		$this->access_secret = $token['secret'];
		$this->api =  new Twitterapi($this->access_token, $this->access_secret);
		$this->allowed_people= $this->get_allowed_users();
	}

	protected function get_allowed_users() {
		$cache_key = 'allowed_users';
		$users = Cache::get($cache_key);
		if(empty($users)) {
			$users = $this->api->get_friends();
			Cache::put($cache_key, $users, 15);
		}
		return $users;
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

	protected function process_message(&$mention, &$first, &$task) {
		// Next call will retrieve from this last id on
		if($first) {
			$task->last_id = $mention['id_str'];
			$task->save();
			$first = FALSE;
		}

		// If the user is not on the allowd admins, ignore the mention
		if(isset($mention[$this->user_key]['screen_name'])) {
			if(!in_array($mention[$this->user_key]['id_str'], $this->allowed_people)) {
				cli_print('User wants to add an event but it\'s not allowed: ' . $mention[$this->user_key]['screen_name'] . ' from ' . $this->twitter_task);
				return FALSE;
			}
		} else {
			cli_print('Weird!! no user owning the mention?: ' . print_r($mention, TRUE));
			return FALSE;
		}

		// Seems to be a request from a valid user, parse it
		cli_print('Tweet received: ' . $mention['text']);
		$data = Birras::process_message($mention, $this->twitter_task);

		// Processing was successful
		if(isset($data['bar_name'])) {
			// There is an event to be added
			$data['message'] = $mention;
			$place = Place::where_name($data['bar_name'])->first();
			if(empty($place)) {
				// The place couldn't be found, let's try with the aliases
				$place = FALSE;
				$place_alias = Place_Alias::find($data['bar_name']);
				if(is_object($place_alias)) {
					$place = $place_alias->place()->first();
				} else {
					// If the place still couldn't be found, add it
					$place = $this->store_place($data);
				}
			}
			$data['place_id'] = $place->id;

			// Has the user already added an event?
			$appointment = Appointment::where_appointment_date(date('Y-m-d', $data['time']))->where_added_by($message[$this->user_key]['screen_name'])->first();
			if(is_object($appointment)) {
				$this->update_appointment($appointment, $data);
			} else {
				// Otherwise store a new one
				$appointment = $this->store_appointment($data, $this->twitter_task);
			}

			if($appointment) {
				// Reply to the user
				$this->reply_to_event($data);
			} else {
				$data['error'] = "There seem to be a problem with the server.";
				if($this->twitter_task === "DMs") {
					$data['error'] .= " Ping @julioelpoeta and tell him to check it!";
				} else {
					$data['error'] .= " Hey @julioelpoeta, are you there??";
				}
				$this->reply_event_error($data);
			}
		} elseif(isset($data['place_to_delete'])) {
		    // DELETE appointment linked to the place
			$data['message'] = $mention;

			// Retrieve appointments for today
			$appointment = Appointment::where_place_id($data['place_to_delete']->id)->where_between('a_date_ts', strtotime("today"), strtotime('tomorrow -1 second'))->first();
			if($appointment) {
				if($appointment->added_by !== $mention[$this->user_key]['screen_name']) {
					cli_print('Trying to delete an event @' . $mention[$this->user_key]['screen_name'] . ' didn\'t create.');
					$data['error'] = '@' . $mention[$this->user_key]['screen_name'] . " You didn't add this event so you can't delete it. Talk to @" . $appointment->added_by;
					$this->reply_w_error($data);
				}

				$result = $appointment->delete();
				if($result && !isset($result->error)) {
					cli_print("Event deleted from the DB from tweet: " . $mention['text']);
					$this->reply_to_delete($data);
				} else {
					cli_print("There was a problem while deleting:  " . $mention['text']);
					$data['error'] = "There seem to be a problem with the server on delete. Hey @julioelpoeta, are you there??";
					$this->reply_w_error($data);
				}
			} else {
				cli_print("The event doesn't exist:  " . $mention['text']);
				$data['error'] = "The event you're trying to delete doesn't exist";
				$this->reply_w_error($data);
			}
		} elseif(isset($data['error'])) {
			cli_print('data error');
			$data['message'] = $mention;
			$this->reply_w_error($data);
		}
	}

	protected function update_appointment($appointment, array &$data) {
		$appointment->a_date_ts = $data['time'];
		$appointment->place_id = $data['place_id'];
		$appointment->added_by = $data['message'][$this->user_key]['screen_name'];
		$appointment->tweet= $data['message']['text'];
		$appointment->tweet_id = $data['message']['id_str'];
		$data['update'] = TRUE;
		return $appointment->save();
	}

	protected function store_appointment(array $data) {
		$new_app = array(
			'time' => $data['time'],
			'place' => $data['place_id'],
			'added_by' => $data['message'][$this->user_key]['screen_name'],
			'tweet' => $data['message']['text'],
			'tweet_id' => $data['message']['id_str'],
		);
		if(!isset($new_app['time'],
			$new_app['place'],
			$new_app['added_by'],
			$new_app['tweet'],
			$new_app['tweet_id'])
		) {
			return FALSE;
		}

		$appointment = new Appointment;
		$appointment->a_date_ts = $new_app['time'];
		$appointment->place_id = $new_app['place'];
		$appointment->added_by = $new_app['added_by'];
		$appointment->tweet= $new_app['tweet'];
		$appointment->tweet_id = $new_app['tweet_id'];
		$appointment->date = date('Y-m-d', $new_app['time']);
		if($this->twitter_task === 'DMs') {
			$appointment->public = 0;
		}

		$appointment->save();
		cli_print("Event added to the DB from tweet: " . $new_app['tweet']);
		return $appointment;
	}

	protected function store_place(array $data) {
		// Add the place if it couldn't be found
		$place = new Place;

		$place->name = $data['bar_name'];
		if(!empty($data['map'])) {
			foreach($data['message']['entities']['urls'] AS $url) {
				if(strcmp($data['map'], $url['url']) == 0) {
					$data['map'] = $url['expanded_url'];
					break;
				} else {
					var_dump($data['map']);
					var_dump($url); exit;
				}
			}
			$place->map_link = $data['map'];
		}
		$place->added_by = $data['message'][$this->user_key]['screen_name'];

		$place->save();
		cli_print('place ' . $data['bar_name'] . ' added.');
		return $place;
	}

	protected function reply_w_error($data, $to_user = FALSE) {
		$text = $data['error'];
		if($this->twitter_task == 'DMs') {
			if(!empty($to_user)) {
				return $this->api->post_dm($text, $to_user);
			}
			cli_print('You cannot send a DM w/o providing a $to_user var.');
		} else {
			if(isset($data['message']['id'])) {
				$options = array(
					'in_reply_to_status_id' => $data['message']['id'],
				);
			}
			return $this->api->post_tweet($text, $options);
		}
	}

	protected function reply_to_event($data) {

		if(isset($data['udpate']) && $data['update']) {
			$text = "The event on {$data['bar_name']} has been updated to on " . date("Y-m-d", $data['time']) . " at " . date("H:i", $data['time']);
		} else {
			$text = "Event added on {$data['bar_name']} on " . date("Y-m-d", $data['time']) . " at " . date("H:i", $data['time']);
		}
		if($this->twitter_task == 'DMs') {
			$text .= '. Check http://birras.today';
			$this->api->post_dm($text, $data['message'][$this->user_key]['id_str']);
		} else {
			$text .= " by @{$data['message'][$this->user_key]['screen_name']} ";
			$text .= '. Check http://birras.today';
		}

		$options = array(
			'in_reply_to_status_id' => $data['message']['id'],
		);
// 		cli_print("Tweeting: " . $text);
		return $this->api->post_tweet($text, $options);
	}

	protected function reply_event_error($data) {
		$user = $data['message'][$this->user_key]['screen_name'];
		$text = "The event couldn't be added. Error: {$data['error']}";
		if($this->twitter_task == 'DMs') {
			$this->api->post_dm($text, $data['message'][$this->user_key]['id_str']);
		} else {
			$text = "@$user " . $text;
			$options = array(
				'in_reply_to_status_id' => $data['message']['id'],
			);
			return $this->api->post_tweet($text, $options);
		}
	}

	protected function reply_to_delete($data) {
		$user = $data['message'][$this->user_key]['screen_name'];
		$place = $data['place_to_delete']->name;
		$text = "The event for today on $place has been deleted by @$user from http://birras.today";
		if($this->twitter_task == 'DMs') {
			$this->api->post_dm($text, $data['message'][$this->user_key]['id_str']);
		} else {
			$options = array(
				'in_reply_to_status_id' => $data['message']['id'],
			);
			return $this->api->post_tweet($text, $options);
		}
	}

}
