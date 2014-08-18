<?php


class Place extends Eloquent {

	public static $table  = 'places';

	public static $timestamps = true;

	public function aliases() {
		return $this->has_many('Place_Alias');
	}

	public function appointments() {
		return $this->has_many('Appointment');
	}

	public function count_appointments() {
		if(isset($this->id)) {
			$cache_key = 'count_app:' . $this->id;
			if(Cache::has($cache_key)) {
				return Cache::get($cache_key);
			} else {
				$count = DB::table(Appointment::$table)->where_place_id($this->id)->count();
				Cache::put($cache_key, $count, 1440); // Store for one day
				return $count;
			}
		}
		return 'Empty object!';
	}

}
