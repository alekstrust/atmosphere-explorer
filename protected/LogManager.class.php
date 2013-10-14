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

	public static function notifyNewTheater($string) {
		$string = "Data parsing for $string failed. It could be a new Theater, please check.\n";
		self::notify($string, 'Invalid theater data');
	}

	public static function notify($body = null, $subject = null) {
		$mail = new PHPMailer();

		$mail -> From = EMAIL_FROM;
		$mail -> FromName = 'Filmboard App a4';
		$mail -> AddAddress (EMAIL_TO);

		if($subject === null):
			$mail -> Subject = "[Filmboard] Parsing Status";
		else:
			$mail -> Subject = "[Filmboard] " . $subject;
		endif;

		if($body === null):
			$mail -> Body = file_get_contents(LOGS_PATH . self::$fileName);
		else:
			$mail -> Body = $body;
		endif;

		$mail -> IsSMTP();
		$mail -> CharSet = 'UTF-8';
		$mail -> Host = 'smtp.gmail.com';
		$mail -> Port = 587;
		$mail -> SMTPAuth = true;
		$mail -> SMTPSecure = 'tls';
		$mail -> Username = EMAIL_FROM;
		$mail -> Password = EMAIL_PASS;

		if(!$mail->Send()):
			$mail -> ErrorInfo;
		endif;
	}

}

?>