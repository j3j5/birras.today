<?php

class Dms_Task extends Twitter_Tasks {

	public function __construct() {
		parent::__construct();
		$this->twitter_task = 'DMs';
		$this->user_key = 'sender';
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
				$this->process_message($message, $first, $task);
			}
		}
	}
}
