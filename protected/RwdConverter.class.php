<?php

class RwdConverter {
  
  private static $fileName = null;
  private static $sdrPath = NRG_PATH;

  public static function convert( $fileName )
  {
    if( empty( $fileName ) )
    {
      return false;
    }

    self::$fileName = $fileName;

    if( is_readable( self::$fileName ) )
    {
      LogManager::logThis( 'Procesando ' . self::$fileName );
      $command = self::$sdrPath . 'SymDR\SDR.exe /s ' . self::$fileName;
      exec( $command, $output );

      return self::checkCommandResult();
    }
    else
    {
      LogManager::logThis( "El archivo " . self::$fileName . " no existe o es inaccesible" );
      return false;
    }
  }

  private static function checkCommandResult() {
    $logFileName = self::getLogFileName();

    if( is_readable( $logFileName ) )
    {
      LogManager::logThis( 'ERROR: Fallo al intentar transformar el archivo. El log SDR dice: '
        . file_get_contents( $logFileName ) );

      return false;
    }

    return true;
  }

  private static function getLogFileName()
  {
    $pathParts = explode( '\\', self::$fileName );

    $newFileName = $pathParts[ count( $pathParts ) - 1 ];
    $logFileName = str_replace( '.rwd', '.log', strtolower( $newFileName ) );
    $logFileName = self::$sdrPath . 'ScaledData\\' . $logFileName;

    return $logFileName;
  }
}