#!/usr/bin/php -q
<pre>
<?php

require_once 'config.php';
require_once 'LogManager.class.php';
require_once 'DB.class.php';
require_once 'SDRParser.class.php';
require_once 'domain/Logger.class.php';
require_once 'domain/Sensor.class.php';
require_once 'domain/Record.class.php';

$logger = new Logger();

$logger->id = 1;

SDRParser::startParser( $logger, '453120130910116.txt', "\t" );




// read from stdin
// $fd = fopen( '453120130910116.txt', 'r' );
// $email = "";
// while (!feof($fd))
// {
// 	$email .= fread($fd, 1024);
// }
// fclose($fd);

// echo $email;



//mail('you@yoursite.com','From my email pipe!','"' . $email . '"');





?>
</pre>