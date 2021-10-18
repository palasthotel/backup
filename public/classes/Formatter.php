<?php

namespace Palasthotel\WordPress\Backup;

class Formatter {

	public static function readableTimestamp($time){
		$dateFormat = get_option('date_format');
		$timeFormat = get_option('time_format');
		return date_i18n("$dateFormat $timeFormat", $time);
	}
}