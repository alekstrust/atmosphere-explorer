<?php

class RwdConverter {
  
  private static $filename = null;

  public static function convert( $filename )
  {
    if( ! empty( $filename ) )
    {
      return false;
    }

    self::$filename = $filename;

    if( is_readable( self::$filename ) )
    {
      $command = 'C:\\NRG\\SymDR\\SDR.exe /s ' . self::$filename;
      exec( $command, $output );

      return self::checkCommandResult();
    }
    else
    {
      LogManager::logThis( "El archio " . self::$filename . " no existe o es inaccesible" );
      return false;
    }
  }

  private static function checkResult() {
    // aqui debe ser NRG
    $logFileName = str_replace( '.rwd', '.txt', strtolower( self::$filename ) );

    if( is_readable( $logFileName ) )
    {
      LogManager::logThis( 'Un error ocurrió al intentar transformar el archivo. El log dice: '
        . file_get_contents( $logFileName ) );

      return false;
    }

    return true;
  }
}