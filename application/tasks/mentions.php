<?php

class Mentions_Task extends Twitter_Tasks {

	public function __construct() {
		parent::__construct();
		$this->twitter_task = 'mentions';
		$this->user_key = 'user';
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
				$this->process_message($mention, $first, $task);
			}
		}
	}
}
