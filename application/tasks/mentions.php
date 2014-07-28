<?php

class Mentions_Task {

	private $allowed_people;
	private $access_token;
	private $access_secret;
	private $api;

	public function __construct() {
		Bundle::start('oneauth');

		$this->access_token = "2680204346-aHebV3tcP75TwjSTutk0JNzsScJLRcauQoXptFK";
		$this->access_secret = "1wSw8yMJMqqnwjvcvsQ3zV5WNo2BhFTUYXqw3ZyHpK2Du";
		$this->api =  new Twitterapi($this->access_token, $this->access_secret);
		$friends = $this->get_allowed_users();
		$this->allowed_people = $friends['ids'];

	}

	private function get_allowed_users() {
		return $this->api->get('friends/ids.json', array( 'screen_name' => 'birrastoday', 'count' => 5000 ));
	}

	public function run() {
// 		var_dump(date('Y-m-d H:i:s'));
// 		var_dump(date('Y-m-d H:i:s', strtotime("Today")));
// 		var_dump(date('Y-m-d H:i:s', strtotime("Tomorrow -1 second"))); exit;



		$file_path = '/webservers/birras.today/storage/work/last_mention_processed';
		$task = Twitter_Event::find('mentions');
		if(isset($task->last_id) && !empty($task->last_id)) {
			$last_processed = $task->last_id;
		} else {
			$task = new Twitter_Event;
			$task->task = 'mentions';
		}

		$options = array(
			'count' => 200,
			'include_rts' => false,
			'contributor_details' => false,
			'include_entities' => true,
		);

		// Request from the last mention processed
		if(!empty($last_processed) && is_numeric($last_processed)) {
			$options['since_id'] = $last_processed;
		}

		$result = $this->api->get('statuses/mentions_timeline.json', $options);
		$first = TRUE;
		if(is_array($result) && !empty($result)) {
			foreach($result AS $mention) {
				// Next call will retrieve from this last id on
				if($first) {
					$task->last_id = $mention['id_str'];
					$task->save();
					$first = FALSE;
				}

				// If the user is not on the allowd admins, ignore the mention
				if(isset($mention['user']['screen_name'])) {
					if(!in_array($mention['user']['id_str'], $this->allowed_people)) {
						cli_print('User wants to add a mention but it\'s not allowed: ' . $mention['user']['screen_name']);
						continue;
					}
			    } else {
					cli_print('Weird!! no user owning the mention?: ' . print_r($mention['user'], TRUE));
					continue;
			    }

			    // Seems to be a request from a valid user, parse it
			    var_dump($mention['text']);
			    $matches = array();
			    $add_bar_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*)/i";
				if(mb_strpos($mention['text'], '#map') !== FALSE) {
					$map_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";
					if(preg_match($map_pattern, $mention['text'], $matches)) {
						$data = $this->extract_data_from_tweet($matches);
					} else {
						///TODO: Add automated reply to these tweets
						cli_print("Unknown tweet w/ map received: " . $mention['text']);
						continue;
					}
				} elseif(preg_match($add_bar_pattern, $mention['text'], $matches)) {
					$data = $this->extract_data_from_tweet($matches);
				} else {
					///TODO: Add automated reply to these tweets
					cli_print("Unknown tweet received: " . $mention['text']);
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
							foreach($mention['entities']['urls'] AS $url) {
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
						$place->added_by = $mention['user']['screen_name'];

						$place->save();
						cli_print('place ' . $data['bar_name'] . ' added.');
					}
					///TODO: Check that there's not already an event today on the same place before adding
					$appoinment = new Appointment;
					$appoinment->a_date_ts = $data['time'];
					$appoinment->place_id = $place->id;
					$appoinment->added_by = $mention['user']['screen_name'];
					$appoinment->tweet= $mention['text'];
					$appoinment->tweet_id = $mention['id_str'];

					$appoinment->save();
					///TODO: Add automated reply with the success
					cli_print('Event added on ' . $data['bar_name'] . ' at ' . $data['time'] . ' by @' . $mention['user']['screen_name']);

					// Reply to the user
					$options = array(
						'status' => "Event added on {$data['bar_name']} on " . date("Y-m-d", $data['time']) . " at " . date("H:i", $data['time']) . " by @{$mention['user']['screen_name']} ",
						'in_reply_to_status_id' => $mention['id'],
					);
					$result = $this->api->post('statuses/update.json', $options);
					if(!empty($result)) {
						cli_print('Tweeted: ' . $options['status']);
					} else {
						cli_print('Fail to reply.');
					}
			    } elseif(isset($data['error'])) {
					$options = array(
						'status' => "@{$mention['user']['screen_name']} The event couldn't be added. Error: {$data['error']}",
						'in_reply_to_status_id' => $mention['id'],

					);
					$result = $this->api->post('statuses/update.json', $options);
					if(!empty($result)) {
						cli_print('Tweeted: ' . $options['status']);
					} else {
						cli_print('Fail to reply.');
					}
			    }
			}
		}
	}

	private function extract_data_from_tweet($matches) {
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
						$time = $matches[$index+1];
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
}
