#!/usr/bin/php -q
<pre>
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

// obtener archivos RWD desde Gmail
$rwdfiles = EmailFetcher::start();

LogManager::logThis( 'Archivos a analizar: ' );
print_r($rwdfiles);

foreach( $rwdfiles as $file )
{
  $idLogger = substr( $file, 0, 4);

  $filePath = realpath( FILES_PATH . $file );

  // convert RWD file
  if( RwdConverter::convert( $filePath ) )
  {
    $parserResult = SDRParser::start( $idLogger, str_ireplace( '.rwd', '.txt', $file ) );
  }

  LogManager::logThis( 'Procesamiento terminado. Borrando archivos RWD descargados.' );

  // delete downloaded RWD file
  if( defined( 'DELETE_RWD_FILES' ) && DELETE_RWD_FILES )
  {
    if( is_readable( $filePath ) )
    {
      unlink( $filePath );
    }
  }
}

/*
 * TODO: Finalizado el proceso, es mejor vaciar la carpeta ScaledData del NRG
 */

?>
</pre>