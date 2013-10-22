<?php

class SDRParser {

  private static $logger = null;

  private static $filesDir = 'C:\xampp\htdocs\wind-pexplorer\files\\';
  private static $filePath = '';
  private static $delimiter = "\t";

  // Texto que indica el inicio de la tabla de canales
  private static $matrixFirstText = 'Date & Time Stamp';

  private static $currentLineNumber = 0;

  // Línea donde comienza la tabla de datos de los canales
  private static $matrixRowNumber = -1;

  private static $recordValues = array( 'avg', 'sd', 'min', 'max' );
  private static $header = null;

  private static $initialized = false;

  private static function initialize()
  {
    if ( self::$initialized )
      return;

    if ( empty( self::$matrixFirstText ) )
    {
      LogManager::logThis( 'ERROR: El texto con el que inicia la tabla de canales no es válido' );
      die();
    }

    self::$initialized = true;
  }

  /**
   * Inicia todo el proceso.
   * TODO: Identificar estación
   */
  public static function startParser( $idLogger, $filename = '' )
  {
    self::initialize();

    if ( (int) $idLogger )
    {
      // FALTA verificar en BD
      self::$logger = new Logger();
      self::$logger->id = $idLogger;
    }
    else
    {
      LogManager::logThis( 'ERROR: No se puede identificar la estación/logger.' );
      return false;
    }
    
    // verificar si el archivo existe
    self::$filePath = self::$filesDir . $filename;

    if ( ! file_exists( self::$filePath ) || ! is_readable( self::$filePath ) )
    {
      LogManager::logThis( 'ERROR: El archivo ' . self::$filePath . ' no existe o no hay permisos de lectura.' );
      return false;  
    }

    // abre el archivo
    if ( ( $handle = fopen( self::$filePath, 'r' ) ) !== FALSE )
    {
      // conteo de líneas
      self::$currentLineNumber = 1;

      // obtiene una línea
      while ( ( $row = fgetcsv( $handle, 1000, self::$delimiter ) ) !== FALSE )
      {
        // si aun no se ha llegado a la matriz
        if ( self::$matrixRowNumber === -1 )
        {
          // esta linea es el inicio de la matriz?
          if ( self::isHereChannelMatrix( $row ) )
          {
            // marcar como encontrada
            self::$matrixRowNumber = self::$currentLineNumber;

            // analizar la cabecera (sensores)
            self::parseHeader( $row );
          }
        }
        else
        {
          // si estamos en la matriz
          if ( self::$currentLineNumber > self::$matrixRowNumber )
          {
            // analizar lineas
            self::parseMatrixRow( $row );
          }
        }

        self::$currentLineNumber++;
      }
      
      fclose($handle);
    }

    return true;
  }

  private static function isHereChannelMatrix( $row ) {
    return strpos( implode( ' ', $row ), self::$matrixFirstText ) === 0;
  }

  private static function parseHeader( $row ) {
    self::$header = array( 'time' );

    // traverse columns
    for ( $i = 1; $i < sizeof( $row ); $i++ )
    {
      $channelNumber = self::extractChannelNumber( $row[$i] );

      if ( ! (int) $channelNumber )
      {
        LogManager::logThis( 'ERROR: El número del canal para la cadena ' . $row[$i] . ' no es válido.' );
        die();
      }

      $recordType = strtolower( str_replace( "CH$channelNumber", '', $row[$i] ) );

      if ( ! in_array( $recordType, self::$recordValues ) )
      {
        LogManager::logThis( 'ERROR: El tipo de registro para la cadena ' . $row[$i] . ' no es válido.' );
        die();
      }

      $sensor = self::getSensorByChannelNumber( $channelNumber, self::$logger );

      if ( empty( $sensor ) )
      {
        LogManager::logThis( 'El canal #' . $channelNumber . ' no existe. Creando nuevo sensor en el Logger ID ' . self::$logger->id );

        $sensor = new Sensor();
        $sensor->channelNumber = $channelNumber;
        $sensor->logger = self::$logger;

        if ( ! self::insertSensor( $sensor ) )
        {
          LogManager::logThis( "ERROR: No se puede insertar el sensor $channelNumber en la fila $i" );
          die();
        }
        else
        {
          $sensor = self::getSensorByChannelNumber( $channelNumber, self::$logger );
        }
      }

      $element = array(
        'sensor'      => $sensor,
        'recordType'  => $recordType
      );

      self::$header[] = $element;
    }
  }

  /**
   * Analiza los datos de una fila de la tabla
   */
  private static function parseMatrixRow( $columns ) {
    $insertsCount = 0;

    $columns[0] = str_replace( 'a.m.', 'am', $columns[0]);
    $columns[0] = str_replace( 'p.m.', 'pm', $columns[0]);

    for ( $i = 1; $i < count( $columns ); $i = $i + 4 )
    {
      if ( ! empty( self::$header[$i]['sensor'] ) )
      {
        $record = new Record();
        $record->sensor = self::$header[$i]['sensor'];
        $record->dateCreated = DateTime::createFromFormat( 'd/m/Y H:i:s', $columns[0] )->format( 'Y-m-d H:i:s' );
        $record->avg = $columns[$i];
        $record->sd = $columns[$i+1];
        $record->min = $columns[$i+2];
        $record->max = $columns[$i+3];

        if ( ! self::insertRecord( $record ) )
        {
          LogManager::logThis( "ERROR: No se puede insertar el registro con fecha $dateCreated" );
          die();
        }
        else
        {
          $insertsCount++;
        }
      }
    }
  }

  /**
   * Extrae el primer número de cadenas como "CH6Avg", "CH15Max"...
   * @param $string La cabecera que representa el canal. Ej. CH15Max
   * @return int Número del canal, representado por el primer número en la cadena
   */ 
  private static function extractChannelNumber( $string ) {
    return preg_match( '/(\d{1,2})/', $string, $matches )
      ? (int) $matches[1]
      : null;
  }

  /**
   * Obtiene un sensor según su número de canal
   */
  private static function getSensorByChannelNumber( $channelNumber, $logger ) {
    try
    {
      $query = "SELECT idSensor, channelNumber FROM sensor
                WHERE idLogger = :idLogger AND channelNumber = :channelNumber LIMIT 1";

      $params = array(
        ':idLogger'       => $logger->id,
        ':channelNumber'  => $channelNumber
      );

      $sth = DB::prepare($query);
      $sth -> execute($params);

      while( $row = $sth->fetch( PDO::FETCH_ASSOC ) )
      {
        $sensor = new Sensor();
        $sensor->id = $row['idSensor'];
        $sensor->channelNumber = $row['channelNumber'];

        return $sensor;
      }
    }
    catch( PDOException $e )
    {
      file_put_contents( LOGS_PATH . 'PDOErrors.txt', date('d/m/Y') . ' ' . $e->getMessage() . "\n", FILE_APPEND );
      return null;
    }
  }

  private static function insertSensor( $sensor ) {
    print_r($sensor);
    try
    {
      $query = "INSERT INTO sensor (idLogger, channelNumber)
                VALUES (:idLogger, :channelNumber)";

      $stmt = DB::prepare( $query );

      return $stmt -> execute( array(
        ':idLogger'       => $sensor->logger->id,
        ':channelNumber'  => $sensor->channelNumber
      ) );
    }
    catch( PDOException $e )
    {
      file_put_contents( LOGS_PATH . 'PDOErrors.txt', date('d/m/Y') . ' ' . $e->getMessage() . "\n", FILE_APPEND );
      return false;
    }
  }

  private static function insertRecord( $record ) {
    try
    {
      $query = "INSERT INTO record (idSensor, dateCreated, avg, sd, min, max)
        VALUES (:idSensor, :dateCreated, :avg, :sd, :min, :max)";

      $stmt = DB::prepare( $query );

      return $stmt -> execute( array(
        ':idSensor'     => $record->sensor->id,
        ':dateCreated'  => $record->dateCreated,
        ':avg'          => $record->avg,
        ':sd'           => $record->sd,
        ':min'          => $record->min,
        ':max'          => $record->max
      ) );
    }
    catch( PDOException $e )
    {
      file_put_contents( LOGS_PATH . 'PDOErrors.txt', date('d/m/Y') . ' ' . $e->getMessage(), FILE_APPEND );
      return false;
    }
  }

}