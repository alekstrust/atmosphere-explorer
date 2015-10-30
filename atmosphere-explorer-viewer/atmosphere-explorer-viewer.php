<?php
/**
 * Plugin Name: Atmosphere Explorer Viewer
 * Plugin URI: http://tavara.pe
 * Description: This plugin adds a page template to show the information collected by the Atmospheric Explorer software.
 * Version: 1.0.0
 * Author: Javier Távara
 * Author URI: http://tavara.pe
 * Text Domain: atmosphere-explorer-viewer
 * Domain Path: /languages
 * License: GPL2
 */

/**
 * The plugin directory uri
 */
define( AEVIEWER_URI, plugin_dir_url( __FILE__ ) );
define( AEVIEWER_PATH, plugin_dir_path( __FILE__ ) );

require AEVIEWER_PATH . 'db.php';
require AEVIEWER_PATH . 'shortcodes.php';
require AEVIEWER_PATH . 'filters.php';


function aeviewer_init()
{
	load_plugin_textdomain( 'atmosphere-explorer-viewer',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'aeviewer_init' );


/**
 * Registar the 'logger' query var
 *
 * @return void
 */
function aeviewer_add_query_vars( $vars )
{
	$vars[] = "logger";

	return $vars;
}
add_filter( 'query_vars', 'aeviewer_add_query_vars' );


/**
 * Enqueue scripts and styles for the front end.
 *
 * @return void
 */
function aeviewer_scripts_styles() {
	wp_enqueue_script( 'chartjs-globalize', AEVIEWER_URI . 'js/globalize.min.js', array( 'jquery' ), null, true );

	wp_enqueue_script( 'chartjs-dx', AEVIEWER_URI . 'js/dx.chartjs.js', array( 'chartjs-globalize' ), null, true );
	// wp_enqueue_script( 'chartjs-dx', 'http://cdn3.devexpress.com/jslib/13.2.6/js/dx.chartjs.js', array(), null, true );

	wp_enqueue_script( 'aeviewer-main', AEVIEWER_URI . 'js/main.js', array( 'chartjs-dx' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'aeviewer_scripts_styles', 100 );


/**
 * Output the select control
 */
function aeviewer_get_logger_selector()
{
	$output = '';

	$current_logger_id = (int) get_query_var( 'logger' );

	$loggers = aeviewer_db::get_loggers();

	if ( is_array( $loggers ) )
	{
		foreach ( $loggers as $logger )
		{
			if ( $logger->id == $current_logger_id )
			{
				$output.= '<option value="' . $logger->id . '" selected>' . $logger->location . '</option>';
			}
			else
			{
				$output.= '<option value="' . $logger->id . '">' . $logger->location . '</option>';
			}
		}

		return sprintf( '
			<form id="atmosphere" class="form-inline" action="%s#atmosphere" method="get">
				<div class="form-group">
					<label for="logger">%s</label>
					<select class="form-control" id="logger" name="logger">%s</select>
				</div>
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Ver" />
				</div>
			</form>', get_permalink(), __( 'Select a logger', 'atmosphere-explorer-viewer' ), $output );
	}
}


function aeviewer_get_the_logger( $id = false, $include_sensors = true )
{
  $output = '';

	if ( ! $id )
	{
		$id = get_query_var( 'logger' );
	}

  if ( ! $id )
  {
    return false;
  }

	$logger = aeviewer_db::get_logger_by_id( $id );

	$logger_meta = get_post_meta( get_the_ID(), $logger->location, true );

	$sensors = aeviewer_db::get_sensors( $id );

	$output .= '<h2>' . $logger->location . '</h2>';

	if ( $logger_meta )
	{
		$output .= '<p>' . $logger_meta . '</p>';
	}

	foreach ( $sensors as $sensor )
	{
		$output .= aeviewer_get_the_sensor( $sensor );
	}

	return $output;
}


function aeviewer_get_the_sensor( $sensor )
{
	$sensor = apply_filters( 'aeviewer_get_the_sensor', $sensor );

	ob_start();

	?>

	<h3><?php echo $sensor->description; ?> <small>(<?php echo $sensor->units; ?>)</small></h3>

	<p>
		<strong><?php printf( __( 'Channel #%s', 'atmosphere-explorer-viewer' ), $sensor->channelNumber ); ?></strong>. 

		<?php if ( ! empty( $sensor->height ) ) : ?>
			<?php printf( __( 'Sensor height: %s m.', 'atmosphere-explorer-viewer' ), $sensor->height ); ?>
		<?php endif; ?>
	</p>
	<?php $records = aeviewer_db::get_last_records( $sensor->idSensor ); ?>

	<script type="text/javascript" id="ds-<?php echo $sensor->idSensor; ?>">
		var dataSource<?php echo $sensor->idSensor; ?> = [
			<?php foreach( $records as $record ): ?>
				{
					day:	 "<?php echo date_format( date_create( $record->dateCreated ), 'd-M' ); ?>",
					value: <?php echo apply_filters( 'aeviewer_get_the_sensor_record_value', $record->avg, $sensor ); ?>
				},
			<?php endforeach; ?>
		];
	</script>

	<div id="chart-<?php echo $sensor->idSensor; ?>" class="chart" data-type="<?php echo apply_filters( 'aeviewer_get_the_sensor_chart_type', '', $sensor ); ?>" data-sensor="<?php echo $sensor->idSensor; ?>" data-name="<?php echo $sensor->description; ?> (<?php echo $sensor->units; ?>)" style="height: 300px;"></div>

	<?php

	$output = ob_get_contents();

	ob_end_clean();

	return $output;

}
