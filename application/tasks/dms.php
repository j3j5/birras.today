<?php

class Dms_Task extends Twitter_Tasks {

	public function __construct() {
		parent::__construct();
		$this->twitter_task = 'DMs';
	}


	public function run() {

		$task = $this->get_last_processed();

		$options = array(
			'count' => 200,
			'skip_status' => true,
			'include_entities' => true,
		);

		// Request from the last message processed
		if(!empty($this->last_processed) && is_numeric($this->last_processed)) {
			$options['since_id'] = $this->last_processed;
		}

		$result = $this->api->get('direct_messages.json', $options);
		$first = TRUE;
		if(is_array($result) && !empty($result)) {
			foreach($result AS $message) {
				// Next call will retrieve from this last id on
				if($first) {
					$task->last_id = $message['id_str'];
					$task->save();
					$first = FALSE;
				}

				// If the user is not on the allowd admins, ignore the message
				if(isset($message['sender']['screen_name'])) {
					if(!in_array($message['sender']['id_str'], $this->allowed_people)) {
						cli_print('User wants to add a message but it\'s not allowed: ' . $message['sender']['screen_name']);
						continue;
					}
			    } else {
					cli_print('Weird!! no user owning the message?: ' . print_r($message, TRUE));
					continue;
			    }

			    // Seems to be a request from a valid user, parse it
			    var_dump($message['text']);
			    $matches = array();
			    $add_bar_pattern = "/\#(comingto|time) (.*?) \#(comingto|time) (.*)/i";
				if(mb_strpos($message['text'], '#map') !== FALSE) {
					$map_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";
					if(preg_match($map_pattern, $message['text'], $matches)) {
						$data = $this->extract_data_from_tweet($matches);
					} else {
						///TODO: Add automated reply to these tweets
						cli_print("Unknown tweet w/ map received: " . $message['text']);
						continue;
					}
				} elseif(preg_match($add_bar_pattern, $message['text'], $matches)) {
					$data = $this->extract_data_from_tweet($matches);
				} else {
					///TODO: Add automated reply to these tweets
					cli_print("Unknown tweet received: " . $message['text']);
					continue;
			    }

			    if(isset($data['bar_name'])) {
					// There is an event to be added
					$place = Place::where_name(trim($data['bar_name']))->first();
					if(empty($place)) {
						// Add the place if it couldn't be found
						$place = new Place;

						$place->name = $data['bar_name'];
						if(!empty($data['map'])) {
							foreach($message['entities']['urls'] AS $url) {
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
						$place->added_by = $message['sender']['screen_name'];

						$place->save();
						cli_print('place ' . $data['bar_name'] . ' added.');
					}
					///TODO: Check that there's not already an event today on the same place before adding
					$appoinment = new Appointment;
					$appoinment->a_date_ts = $data['time'];
					$appoinment->place_id = $place->id;
					$appoinment->added_by = $message['sender']['screen_name'];
					$appoinment->tweet= $message['text'];
					$appoinment->tweet_id = $message['id_str'];

					$appoinment->save();

					// Reply to the user
					$text = "Event added on {$data['bar_name']} on " . date("Y-m-d", $data['time']) . " at " . date("H:i", $data['time']) . " by @{$message['sender']['screen_name']}";
					$this->api->post_dm($text, $message['sender']['id_str']);
			    } elseif(isset($data['error'])) {
					$text = "@{$message['sender']['screen_name']} The event couldn't be added. Error: {$data['error']}";
					$this->api->post_dm($text, $message['sender']);
			    }
			}
		}
	}
}
