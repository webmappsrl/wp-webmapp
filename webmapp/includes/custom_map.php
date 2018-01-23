<?php
/**
 * Created by PhpStorm.
 * User: lucabaroncini
 * Date: 11/01/18
 * Time: 09:41
 */
function webmap_custom_map_scripts() {

	wp_register_script( 'custom-map', plugin_dir_url(__FILE__) . '/js/custom-map.js', array('jquery'), '1.0.0', true );

	$tiles_url = get_option('webmapp_map_tilesUrl');
	$zoom = get_option( 'webmapp_map_zoom' );
	$app_url = get_option( 'webmapp_map_appUrl' );
	if (empty($tiles_url)){
		$tiles_url = 'https://api.webmapp.it/trentino/tiles/map/{z}/{x}/{y}.png';
	}
	if (empty($zoom)){
		$zoom = '14';
	}
	if (empty($app_url)){
		$app_url = 'http://pnab.j.webmapp.it';
	}

	$data = array(
		'label' => 'Mappa',
		'tilesUrl' => $tiles_url,
		'zoom' => $zoom,
		'appUrl' => $app_url,

	);

	wp_localize_script( 'custom-map', 'data', $data );
	wp_enqueue_script( 'custom-map' );

}

add_action( 'wp_enqueue_scripts', 'webmap_custom_map_scripts' );