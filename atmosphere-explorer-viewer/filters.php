<?php

/**
 * This filter runs before showing sensor data.
 * When sensor description is "radiacion", change some values.
 */
function aeviewer_radiacion( $sensor )
{
	$description = strtolower( $sensor->description );

	if ( $description === 'radiacion' || $description === 'radiación' )
	{
		$sensor->units = 'kW-h/m^2';
		$sensor->description = 'Radiación solar acumulada';
	}

	return $sensor;
}
add_filter( 'aeviewer_get_the_sensor', 'aeviewer_radiacion' );


/**
 * This filter runs before printing a record value.
 * When sensor description is "radiacion", calculate new value.
 */
function aeviewer_radiacion_value( $value, $sensor )
{
	$description = strtolower( $sensor->description );

	if ( $description === 'radiacion solar acumulada' || $description === 'radiación solar acumulada' )
	{
		$value = round( $value * 0.024, 2 );
	}

	return $value;
}
add_filter( 'aeviewer_get_the_sensor_record_value', 'aeviewer_radiacion_value', 10, 2 );


/**
 * This filter runs before getting the last records from a sensor
 * When sensor description is "lluvia", calculate a summatory.
 */
function aeviewer_lluvia_sum( $args, $sensor )
{
	$description = strtolower( $sensor->description );

	if ( $description === 'lluvia' )
	{
		$args['sql_function'] = 'SUM';
	}

	return $args;
}
add_filter( 'aeviewer_last_records_args', 'aeviewer_lluvia_sum', 10, 2 );


/**
 * This filter runs before printing the channel chart type
 * When sensor description is "lluvia", set chart type to "bar".
 */
function aeviewer_lluvia_chart_type( $chart_type, $sensor )
{
	$description = strtolower( $sensor->description );

	if ( $description === 'lluvia' )
	{
		$chart_type = 'bar';
	}

	return $chart_type;
}
add_filter( 'aeviewer_get_the_sensor_chart_type', 'aeviewer_lluvia_chart_type', 10, 2 );
