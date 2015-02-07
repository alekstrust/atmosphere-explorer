<?php

//require_once LIBS_PATH . 'class.phpmailer.php';

class LogManager {

 	/**
 	 * Path to error file
 	 * @var string
 	 */
 	public static $fileName = 'parsing.txt';

	public static function logThis( $log, $show = true ) {
		$log = date('d/m/Y H:i:s') . " " . $log . "\r\n";

		if ( $show )
		{
			echo $log;
		}

		file_put_contents( LOGS_PATH . self::$fileName, $log, FILE_APPEND );
	}

}

?>