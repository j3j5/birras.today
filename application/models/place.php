<?php


class Place extends Eloquent {

	public static $table  = 'places';

	public static $timestamps = true;

	public function aliases() {
		return $this->has_many('Place_Alias');
	}

}
