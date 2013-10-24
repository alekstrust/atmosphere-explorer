#!/usr/bin/php -q
<?php

set_time_limit(0);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/LogManager.class.php';
require_once __DIR__ . '/DB.class.php';
require_once __DIR__ . '/SDRParser.class.php';
require_once __DIR__ . '/EmailMessage.class.php';
require_once __DIR__ . '/EmailFetcher.class.php';
require_once __DIR__ . '/RwdConverter.class.php';
require_once __DIR__ . '/domain/Logger.class.php';
require_once __DIR__ . '/domain/Sensor.class.php';
require_once __DIR__ . '/domain/Record.class.php';

// fetch RWD files from  Gmail
$rwdfiles = EmailFetcher::start();

// $rwdfiles = array(
//   '365320131022228.RWD',
//   '453020131022099.RWD',
//   '453120131022158.RWD',
//   '453420131022055.RWD',
//   '453520131022036.RWD',
//   '453620131021431.RWD',
//   '453620131022432.RWD'
// );

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