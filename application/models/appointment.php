<?php


class Appointment extends Eloquent {

	public static $table  = 'appointments';

	public static $timestamps = true;

	public static function get_events4today() {
		return DB::table(self::$table)->where_between('a_date_ts', strtotime("today"), strtotime('tomorrow -1 second'))->get();
	}


}
