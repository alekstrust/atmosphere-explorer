<?php

/**
 * Show a list of all loggers. Valid types: form, ul, ol
 *
 * @param array $atts Valid index: type. Possible values: form, ul, ol
 * @return string
 * @todo Implement ul/ol list type
 */
function aeviewer_sc_list( $atts ) {
	$atts = shortcode_atts( array(
		'type'	=> 'form',
	), $atts, 'aeviewer_list' );

	if ( $atts['type'] === 'form' )
	{
		return aeviewer_get_logger_selector();
	}
}
add_shortcode( 'aeviewer_list', 'aeviewer_sc_list' );


/**
 * Show logger informatation
 *
 * @param array $atts 'all' or logger id
 * @return string
 */
function aeviewer_sc_logger( $atts ) {
	$atts = shortcode_atts( array(
		'id'		=> '',
	), $atts, 'aeviewer_logger' );

	return aeviewer_get_the_logger( $logger_id );
}
add_shortcode( 'aeviewer_logger', 'aeviewer_sc_logger' );
