<?php

class Birras {

	private static $add_bar_pattern = "/\#(comingto|time) (.*?) \#(comingto|time) (.*)/i";
	private static $add_bar_w_map_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";

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
				if(mb_strpos($message['text'], '#map') !== FALSE) {
					if(preg_match(self::$add_bar_w_map_pattern, $message['text'], $matches)) {
						$data = self::extract_data_from_tweet($matches);
					} else {
						///TODO: Add automated reply to these tweets
						cli_print("Unknown tweet w/ map received: " . $message['text']);
						return FALSE;
					}
				} elseif(mb_strpos($message['text'], '#delete') !== FALSE) {
					// 					$delete_pattern = "/\#(comingto|time|map) (.*?) \#(comingto|time|map) (.*) \#(comingto|time|map) (.*)/i";
					// 					if(preg_match($map_pattern, $message['text'], $matches)) {
					// 						$data = self::extract_data_from_tweet($matches);
					// 					}
				} elseif(preg_match(self::$add_bar_pattern, $message['text'], $matches)) {
					$data = self::extract_data_from_tweet($matches);
				} else {
					///TODO: Add automated reply to these tweets
					cli_print("Unknown tweet received: " . $message['text']);
					return FALSE;
				}
				break;
			case 'DMs':
				cli_print('dm');
				break;
			default:
				cli_print('default');
				break;
		}
		return $data;
	}

	public static function extract_data_from_tweet($matches) {
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
}
