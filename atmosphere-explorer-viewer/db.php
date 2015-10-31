<?php

$aevdb = new wpdb( 'windpexplorer', 'wind', 'atmosphereexplorer', 'localhost' );

class aeviewer_db
{
  static function get_loggers()
  {
    global $aevdb;

    return $aevdb->get_results( "SELECT idLogger as id, location FROM logger" );
  }

  static function get_logger_by_id( $id )
  {
    global $aevdb;

    return $aevdb->get_row( "SELECT * FROM logger WHERE idLogger = $id LIMIT 1" );
  }

  static function get_sensors( $id )
  {
    global $aevdb;

    return $aevdb->get_results( "SELECT * FROM sensor WHERE description <> '' AND idLogger = $id" );
  }

  static function get_last_records( $id, $count = 30 )
  {
    $transient_id = 'records_' . $id;

    if ( false === ( $rows = get_transient( $transient_id ) ) )
    {
      global $aevdb;

      if( ! (int) $id && ! (int) $count )
        return false;

      $rows = array_reverse( $aevdb->get_results( "SELECT dateCreated, ROUND(AVG(avg),2) as avg
        FROM record
        WHERE idSensor = $id
        GROUP BY DATE(dateCreated) 
        ORDER BY dateCreated DESC
        LIMIT $count" ) );

      set_transient( $transient_id, $rows, DAY_IN_SECONDS / 2 );
    }

    return $rows;
  }
}
