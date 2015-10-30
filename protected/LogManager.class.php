<?php

class LogManager {

 	/**
 	 * Path to error file
 	 * @var string
 	 */
 	public static $fileName = 'parsing.txt';

	public static function logThis( $log, $show = SHOW_LOG , $write = WRITE_LOG )
	{
		$log = date('d/m/Y H:i:s') . " " . $log . "\r\n";

		if ( $show )
		{
			echo $log;
		}

		if ( $write )
		{
			file_put_contents( LOGS_PATH . self::$fileName, $log, FILE_APPEND );
		}		
	}

}

?>