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

// fetch RWD files from  Gmail
//$rwdfiles = EmailFetcher::start();

$rwdfiles = array(
  '365320131022228.RWD',
  '453020131022099.RWD',
  '453120131022158.RWD',
  '453420131022055.RWD',
  '453520131022036.RWD',
  '453620131021431.RWD',
  '453620131022432.RWD'
);

print_r($rwdfiles);

foreach( $rwdfiles as $file )
{
  $idLogger = substr( $file, 0, 4);

  $fileName = FILES_PATH . $file;

  if( RwdConverter::convert( $fileName ) )
  {
    SDRParser::start( $idLogger, str_ireplace( '.rwd', '.txt', $file ) );
  }
}

?>
</pre>