<?php

class Birras {

	private static $add_bar_pattern = "/(\#comingto|\#time) (.*?) (\#comingto|\#time|around) (.*)/i";
	private static $add_bar_w_map_pattern = "/(\#comingto|\#time|\#map) (.*?) (\#comingto|\#time|\#map|around) (.*) (\#comingto|\#time|\#map) (.*)/i";
	private static $delete_event_pattern = "/\#(deleteevent) (.*)/i";

	public static function include_basic_scripts() {
		Asset::container('header')->add('bootstrap_css', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
		Asset::container('header')->style('open_sans', "/fonts.googleapis.com/css?family=Open+Sans:400,800,700,600,600italic,400italic");
		Asset::container('header')->add('global_css', "css/global.css");

		Asset::container('footer')->add('jquery', '/code.jquery.com/jquery-1.11.1.min.js');
		Asset::container('footer')->add('bootstrap_js', "/maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js");
		Asset::container('footer')->add('global_js', "js/global.js");
	}

	/**
	 * Process a mention to extract the data related to the app
	 *
	 * @param Array $message The mention array
	 *
	 * @return Array|Bool The extracted data or FALSE to ignore the tweet
	 *
	 * @author Julio Foulquié <julio@tnwlabs.com>
	 */
	public static function process_message($message, $type ) {
		$matches = array();
		switch ($type) {
			case 'mentions':
				if(mb_strpos($message['text'], '@birrastoday') !== 0) {
					cli_print('Incorrect format, @birrastoday must be in front.');
					$data['error'] = 'Hey!! You MUST mentioned me with my name first in the tweet. Don\'t mess with me!';
					return $data;
				}
			case 'DMs':
				// Add event
				if(mb_strpos($message['text'], '#comingto') !== FALSE) {
					// Does it have map?
					if(mb_strpos($message['text'], '#map') !== FALSE) {
						if(preg_match(self::$add_bar_w_map_pattern, $message['text'], $matches)) {
							$data = self::extract_data_from_tweet($matches);
						} else {
							cli_print("Unknown tweet ($type) w/ map received: " . $message['text']);
							$data['error'] = "WHAT?? I don't understand the format of this tweet with map.";
						}
					} elseif(preg_match(self::$add_bar_pattern, $message['text'], $matches)) {
						// Add event without a map
						$data = self::extract_data_from_tweet($matches);
					} else {
						cli_print("Unknown tweet received: " . $message['text']);
						$data['error'] = "WHAT?? I don't understand the format of this tweet.";
					}
				} elseif(mb_strpos($message['text'], '#deleteevent') !== FALSE) {
					// Delete an event
					if(preg_match(self::$delete_event_pattern, $message['text'], $matches)) {
						$data = self::extract_data_from_tweet($matches);
					}
				} else {
					cli_print("Unknown tweet received: " . $message['text']);
					$data['error'] = "WHAT?? I don't understand the format of this tweet.";
				}
				return $data;
				break;
			default:
				cli_print('default');
				break;
		}
		return $data;
	}

	public static function extract_data_from_tweet($matches) {
		$error = $bar_name = $time = $map_link = '';
		foreach($matches AS $index => $match) {
			$match = str_replace('#', '', $match);
			switch ($match) {
				case 'comingto':
					$matches[$index+1] = self::clean_match($matches[$index+1]);
					if(!empty($matches[$index+1])) {
						$bar_name = trim($matches[$index+1]);
					} else {
						$error = 'Empty location.';
					}
					break;
				case 'around':
				case 'time':
					$matches[$index+1] = self::clean_match($matches[$index+1]);
					$timestamp = strtotime($matches[$index+1]);
					if(!empty($timestamp)) {
						$time = $timestamp;
					} else {
						$error = 'Not a valid datetime format.';
					}
					break;
				case 'map':
					$matches[$index+1] = self::clean_match($matches[$index+1]);
					$map_link = filter_var($matches[$index+1], FILTER_VALIDATE_URL);
					if(!is_string($map_link)) {
						$map_link = '';
					}
					break;
				case 'deleteevent':
					$matches[$index+1] = self::clean_match($matches[$index+1]);
					$place_to_delete = $matches[$index+1];
					$place = Place::where_name(trim($place_to_delete))->first();
					if(empty($place)) {
						$error = 'The place you mentioned could not be found on our old library.';
					}
				default:
					break;
			}
		}
		if(!empty($bar_name) && !empty($time)) {
			return array('bar_name' => $bar_name, 'time' => $time, 'map' => $map_link);
		} elseif (!empty($place)) {
		    return array('place_to_delete' => $place);
		}
		return array('error' => $error);
	}

	public static function clean_match($match) {
		// Clean '#' from the time in case somebody gets confused, ehem, Rafa, ehem...
		$match = str_replace('#', '', $match);
		$match = trim($match);
		return $match;
	}
}
