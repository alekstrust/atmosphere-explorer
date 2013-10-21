#!/usr/bin/php -q
<pre>
<?php

require_once 'config.php';
require_once 'LogManager.class.php';
require_once 'DB.class.php';
require_once 'SDRParser.class.php';
require_once 'EmailMessage.class.php';
require_once 'EmailFetcher.class.php';
require_once 'domain/Logger.class.php';
require_once 'domain/Sensor.class.php';
require_once 'domain/Record.class.php';

$logger = new Logger();

$logger->id = 1;

//SDRParser::startParser( $logger, '453120130910116.txt', "\t" );

$rwdfiles = EmailFetcher::start();
foreach( $rwdfiles as $file )
{
  echo $command = 'C:\\NRG\\SymDR\\SDR.exe /s ' . FILES_PATH . $file;
  exec( $command, $output );
  $output = array();
  print_r($output);
}

?>
</pre>