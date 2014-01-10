<?php

class EmailFetcher {

  private static $hostname = '{imap.gmail.com:993/imap/ssl}';
  private static $username = 'fisica.udep@gmail.com';
  private static $password = 'F3s3c177';
  private static $theDate;

  private static $filesDir = FILES_PATH;

  private static $filesCreated = array();

  // solo se analizarán archivos con mas bytes que $minFileSize
  private static $minFileSize = MIN_ATTACHMENT_SIZE;

  private static $imapStream = null;

  private static $initialized = false;

  private static function initialize()
  {
    if ( self::$initialized )
      return;

    // asignar la zona para Perú
    date_default_timezone_set( 'America/Lima' );

    self::$theDate = new DateTime( START_TIME );

    self::$initialized = true;
  }

  public static function start()
  {
    self::initialize();

    LogManager::logThis( 'Obteniendo todos los mensajes (' . START_TIME . '): ' . self::$theDate->format(DateTime::RFC1123) );

    // conectarse a la cuenta
    self::$imapStream = imap_open( self::$hostname, self::$username, self::$password );

    if( ! self::$imapStream )
    {
      LogManager::logThis( 'ERROR: No se puede conectar a la bandeja: ' . imap_last_error() );
      return false;
    }

    // obtener solo las bandejas NRG
    $mailboxes = imap_list( self::$imapStream, self::$hostname, 'NRG%' );

    foreach( $mailboxes as $mailbox )
    {
      self::checkMailbox( $mailbox );
    }

    return self::$filesCreated;

  }

  private static function checkMailbox( $mailbox )
  {
    if ( empty( $mailbox ) )
    {
      LogManager::logThis( "El indicador de la bandeja no es válido" );
      return false;
    }

    $shortname = str_replace( self::$hostname, '', $mailbox );

    LogManager::logThis( "Verificando la bandeja: $shortname" );

    if( ! imap_reopen( self::$imapStream, $mailbox ) )
    {
      LogManager::logThis( "ERROR: No se puede conectar a la bandeja $shortname" );
      return false;
    }

    // buscará los mensajes partiendo del día indicado, sin importar la hora
    $searchCriterion = 'SINCE "' . self::$theDate->format( 'd F Y') . '"';

    $msgNumbers = imap_search( self::$imapStream, $searchCriterion );

    if( is_array( $msgNumbers ) )
    {
      LogManager::logThis( "Mensajes desde " . self::$theDate->format( 'd F Y')
        . " en $shortname: " . count( $msgNumbers ) );

      $parsedMessages = 0;

      // analizar mensajes que coinciden con la fecha
      foreach( $msgNumbers as $msgNumber )
      {
        $headers = imap_headerinfo( self::$imapStream, $msgNumber);

        // analizar solo los que coincidan con fecha y hora correcta
        if( new DateTime( $headers->date ) >= self::$theDate && new DateTime( $headers->date ) <= new DateTime() )
        {
          LogManager::logThis( 'Mensaje recibido ' . $headers->date );

          // fusionar lista de nombres de archivo
          self::$filesCreated = array_merge( self::$filesCreated, self::fetchEmailAttachments( self::$imapStream, $msgNumber ) );
          
          $parsedMessages++;
        }
      }

      LogManager::logThis( "$parsedMessages mensajes analizados" );
    }
    else
    {
      LogManager::logThis( "No hay mensajes o no se entiende el criterio de búsqueda" );
      return false;
    }
  }

  private static function fetchEmailAttachments( $imapStream, $msgNumber )
  {
    $emailMessage = new EmailMessage( $imapStream, $msgNumber );
    $emailMessage->fetch();

    return self::generateFilesFromAttachments( $emailMessage );
  }

  private static function generateFilesFromAttachments( $emailMessage )
  {
    $filesCreated = array();

    if( is_array( $emailMessage->attachments ) )
    {
      foreach( $emailMessage->attachments as $attachment )
      {
        if( $attachment['subtype'] === 'RFC822' )
        {
          $filesCreated = array_merge( $filesCreated, self::generateFilesFromAttachments( $attachment['data'] ) );
        }
        elseif( $attachment['subtype'] === 'OCTET-STREAM' && self::hasRwdExtension( $attachment['filename'] ) )
        {
          // verificar el tamaño del archivo
          if( strlen( $attachment['data']) > self::$minFileSize )
          {
            if( self::createFile( $attachment['filename'], $attachment['data'] ) )
            {
              LogManager::logThis( 'Archivo creado: ' . $attachment['filename'] . ' '
                . strlen($attachment['data']) . ' bytes escritos' );

              $filesCreated[] = $attachment['filename'];
            }
            else
            {
              LogManager::logThis( 'ERROR: No se pudo crear el archivo ' . $attachment['filename'] );
            } 
          }
        }
      }
    }

    return $filesCreated;
  }

  private static function createFile( $filename, $data )
  {
    return file_put_contents( self::$filesDir . $filename, $data ) === false ? false : true;
  }

  private static function hasRwdExtension( $filename )
  {
    return strtolower( substr( $filename, -4 ) ) === '.rwd';
  }
}