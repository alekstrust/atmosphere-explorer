#!/usr/bin/php -q
<pre>
<?php

set_time_limit(0);

require_once 'config.php';
require_once 'LogManager.class.php';
require_once 'DB.class.php';
require_once 'SDRParser.class.php';
require_once 'EmailMessage.class.php';
require_once 'EmailFetcher.class.php';
require_once 'RwdConverter.class.php';
require_once 'domain/Logger.class.php';
require_once 'domain/Sensor.class.php';
require_once 'domain/Record.class.php';

$logger = new Logger();

$logger->id = 1;

//SDRParser::startParser( $logger, '453120130910116.txt', "\t" );

// fetch RWD files from  Gmail
$rwdfiles = EmailFetcher::start();

echo "finalizado\n";

foreach( $rwdfiles as $file )
{
  $filePath = FILES_PATH . $file;

  RwdConverter::convert( $filePath );
}

?>
</pre>