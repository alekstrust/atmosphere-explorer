<?php

$aevdb = new wpdb( AEVIEWER_DB_USER, AEVIEWER_DB_PASS, AEVIEWER_DB_NAME, AEVIEWER_DB_HOST );

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

  static function get_last_records( $args, $sensor )
  {
    $defaults = array(
      'idSensor'      => '',
      'count'         => 30,
      'sql_function'  => 'AVG', // avg, sum
      'record_value'  => 'avg', // avg, sd, min, max
    );

    $args = wp_parse_args( $args, $defaults );

    $valid_record_values = array( 'avg', 'sd', 'min', 'max' );

    if (
      ! (int) $args['idSensor'] ||
      ! (int) $args['count'] ||
      ! in_array( strtolower( $args['record_value'] ), $valid_record_values ) ||
      empty( $args['sql_function'] )
    )
    {
      return false;
    }

    $transient_id = 'records_' . $args['idSensor'];

    if ( false === ( $rows = get_transient( $transient_id ) ) )
    {
      global $aevdb;

      $query = "SELECT dateCreated, ROUND({$args['sql_function']}({$args['record_value']}),2) as avg
        FROM record
        WHERE idSensor = {$args['idSensor']}
        GROUP BY DATE(dateCreated) 
        ORDER BY dateCreated DESC
        LIMIT {$args['count']}";

      $rows = array_reverse( $aevdb->get_results( $query ) );

      set_transient( $transient_id, $rows, DAY_IN_SECONDS / 2 );
    }

    return $rows;
  }
}
