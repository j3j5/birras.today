<?php


class Place_Alias extends Eloquent {

	public static $table  = 'place_aliases';
	public static $key = 'alias';
	public static $timestamps = true;


	public function place() {
		return $this->belongs_to('Place');
	}

}
