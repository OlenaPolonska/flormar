<?php
/*
Plugin Name: ELP Flormar test
Description: A plugin that adds the [flormar-test-slider] shortcode to display the WooCommerce responsive product slider.
Text Domain: flormar
Version: 1.0
Author: ELP
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

require_once( 'flormar_view.php' );

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'jquery-mobile', plugin_dir_url( __FILE__ ) . '/jquery.mobile-1.4.5.min.js', array( 'jquery' ), '1.4.5' );

	wp_enqueue_script( 'flormar-js', plugin_dir_url( __FILE__ ) . '/public.js', array( 'jquery', 'jquery-mobile' ) );
	
	wp_enqueue_style( 'flormar-css', plugin_dir_url( __FILE__ ) . '/public.css' );
} );

add_shortcode( 'flormar-test-slider', function( $atts ) {
	$defaults = array(
		'title' => '',
		'speed' => 50,
		'slides_visible' => 4,
	);

	$options = array_merge(
        $defaults,
        array_intersect_key( 
			array_map( 'sanitize_text_field', $atts ), 
			$defaults )
    );	
		
	if ( !empty( $atts ) ) {
		if ( !empty( $min_price = $atts['min-price'] ) ) {
			$min_sale_price_query = empty( floatval( $min_price ) ) ? '' :
				array(
					'key'     => '_sale_price',
					'value'   => $min_price,
					'type'    => 'DECIMAL',
					'compare' => '>=',
				);
			$min_regular_price_query = empty( floatval( $min_price ) ) ? '' :
				array(
					'key'     => '_regular_price',
					'value'   => $min_price,
					'type'    => 'DECIMAL',
					'compare' => '>=',
				);
		}

		if ( !empty( $max_price = $atts['max-price'] ) ) {
			$max_sale_price_query = empty( floatval( $max_price ) ) ? '' :
				array(
					'key'     => '_sale_price',
					'value'   => $max_price,
					'type'    => 'DECIMAL',
					'compare' => '<=',
				);
			$max_regular_price_query = empty( floatval( $max_price ) ) ? '' :
				array(
					'key'     => '_regular_price',
					'value'   => $max_price,
					'type'    => 'DECIMAL',
					'compare' => '<=',
				);
		}
	}
		
	$query = array(
		'post_type' => array('product', 'product_variation'),
		'numberposts' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'relation' => 'AND',
				$min_sale_price_query,
				$max_sale_price_query,
			),
			array(
				'relation' => 'AND',
				array(
					'key'     => '_sale_price',
					'compare' => 'NOT EXISTS',
				),
				$min_regular_price_query,
				$max_regular_price_query,
			),
		)
	);
	
	$product_posts = get_posts( $query );
	
	$flormar_view = new FlormarView;
	return $flormar_view->render_slider( $product_posts, $options );
} );
