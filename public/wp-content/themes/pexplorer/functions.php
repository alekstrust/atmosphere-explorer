<?php

function windpexplorer_add_query_vars( $vars )
{
  $vars[] = "logger";

  return $vars;
}
add_filter( 'query_vars', 'windpexplorer_add_query_vars' );



/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function windpexplorer_scripts_styles() {
  wp_deregister_script('jquery');

  wp_enqueue_script( 'jquery', get_stylesheet_directory_uri() . '/js/jquery-2.0.3.min.js', array(), null, false );

  wp_enqueue_script( 'chartjs-globalize', get_stylesheet_directory_uri() . '/js/globalize.min.js', array('jquery'), null, true );

  wp_enqueue_script( 'chartjs-dx', get_stylesheet_directory_uri() . '/js/dx.chartjs.js', array(), null, true );
  // wp_enqueue_script( 'chartjs-dx', 'http://cdn3.devexpress.com/jslib/13.2.6/js/dx.chartjs.js', array(), null, true );

  wp_enqueue_script( 'pexplorer-main', get_stylesheet_directory_uri() . '/js/main.js', array(), null, false );
}
add_action( 'wp_enqueue_scripts', 'windpexplorer_scripts_styles' );

/**
 * Muestra el formulario para elegir la estación
 *
 */
function windpexplorer_the_logger_selector()
{
  $output = '';

  $loggers = windpexplorer_get_loggers();

  if( is_array( $loggers ) )
  {
    foreach( $loggers as $logger )
    {
       $output.= '<option value="' . $logger->id . '">' . $logger->location . '</option>';
    }

    printf( '<form action="%s" method="get"><select name="logger">%s</select> <input type="submit" value="Ver" /></form>', get_permalink(), $output );
  }

}

/**
 * ==== DATABASE ACCESS ====
 */

function windpexplorer_get_loggers()
{
  global $wpdb;

  return $wpdb->get_results( "SELECT idLogger as id, location FROM logger" );
}

function windpexplorer_get_logger_by_id( $id )
{
  global $wpdb;

  return $wpdb->get_row( "SELECT * FROM logger WHERE idLogger = $id LIMIT 1" );
}

function windpexplorer_get_sensors( $id )
{
  global $wpdb;

  return $wpdb->get_results( "SELECT * FROM sensor WHERE description <> '' AND idLogger = $id" );
}

function windpexplorer_get_last_records( $id, $count = 30 )
{
  global $wpdb;

  if( ! (int) $id && ! (int) $count )
    return false;

  return $wpdb->get_results( "SELECT dateCreated, ROUND(AVG(avg),2) as avg
    FROM record
    WHERE idSensor = $id
    GROUP BY DATE(dateCreated) 
    ORDER BY dateCreated ASC
    LIMIT $count" );
}

?>