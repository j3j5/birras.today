<?php


class Appointment extends Eloquent {

	public static $key  = 'id';
	public static $table  = 'appointments';

	public static $timestamps = true;

	public static function get_events4today() {
		return DB::table(self::$table)->where_appointment_date(date('Y-m-d'))->order_by('a_date_ts', 'ASC')->get();
	}

	public function place() {
		return $this->belongs_to('Place');
	}


}
