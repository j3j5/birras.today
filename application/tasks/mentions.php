<?php

class Mentions_Task extends Twitter_Tasks {

	public function __construct() {
		parent::__construct();
		$this->twitter_task = 'mentions';
	}


	public function run() {

		$task = $this->get_last_processed();

		$options = array(
			'count' => 200,
			'include_rts' => false,
			'contributor_details' => false,
			'include_entities' => true,
		);

		// Request from the last mention processed
		if(!empty($this->last_processed) && is_numeric($this->last_processed)) {
			$options['since_id'] = $this->last_processed;
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
					cli_print('Weird!! no user owning the mention?: ' . print_r($mention, TRUE));
					continue;
			    }

			    // Seems to be a request from a valid user, parse it
			    cli_print($mention['text']);
			    $matches = array();
			    $add_bar_pattern = "/\#(comingto|time) (.*?) \#(comingto|time) (.*)/i";
				if(mb_strpos($mention['text'], '#map') !== FALSE) {
					$map_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";
					if(preg_match($map_pattern, $mention['text'], $matches)) {
						$data = $this->extract_data_from_tweet($matches);
					} else {
						///TODO: Add automated reply to these tweets
						cli_print("Unknown tweet w/ map received: " . $mention['text']);
						continue;
					}
				} elseif(mb_strpos($mention['text'], '#delete') !== FALSE) {
// 					$delete_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";
// 					if(preg_match($map_pattern, $mention['text'], $matches)) {
// 						$data = $this->extract_data_from_tweet($matches);
// 					}
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
					$appointment = array(
						'time' => $data['time'],
						'place' => $place->id,
						'added_by' => $mention['sender']['screen_name'],
						'tweet' => $mention['text'],
						'tweet_id' => $mention['id_str'],
					);

					$this->store_appointment($appointment);

					// Reply to the user
					$text = "Event added on {$data['bar_name']} on " . date("Y-m-d", $data['time']) . " at " . date("H:i", $data['time']) . " by @{$mention['user']['screen_name']} ";
					$options = array(
						'in_reply_to_status_id' => $mention['id'],
					);
					$this->api->post_tweet($text, $options);

			    } elseif(isset($data['error'])) {
					$text = "@{$mention['user']['screen_name']} The event couldn't be added. Error: {$data['error']}";
					$options = array(
						'in_reply_to_status_id' => $mention['id'],

					);
					$this->api->post_tweet($text, $options);
			    }
			}
		}
	}
}
