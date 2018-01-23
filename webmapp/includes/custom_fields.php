<?php

function my_myme_types($mime_types) {

  $mime_types['gpx'] = 'application/gpx+xml'; //Adding svg extension

  return $mime_types;
}

add_filter('upload_mimes', 'my_myme_types');

// POI
require_once('custom_fields/custom_fields_poi.php');

// ROUTES
require_once('custom_fields/custom_fields_routes.php');


// TRACKS
require_once('custom_fields/custom_fields_tracks.php');

// MAPS
require_once('custom_fields/custom_fields_maps.php');

// Webmapp Taxonomy
require_once('custom_fields/custom_fields_webmapp_taxonomy.php');

// PAGES
require_once('custom_fields/custom_fields_pages.php');

// USERS
require_once('custom_fields/custom_fields_users.php');




