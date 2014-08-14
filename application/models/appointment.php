<?php


class Appointment extends Eloquent {

	public static $table  = 'appointments';

	public static $timestamps = true;

	public static function get_events4today() {
		return DB::table(self::$table)->where_appointment_date(date('Y-m-d'))->get();
	}


}
