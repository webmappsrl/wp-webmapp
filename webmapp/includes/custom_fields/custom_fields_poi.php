<?php // custom_fields_poi.php
function getPoiCustomFields() {

  $poi_fields = array(
      array(
          'key' => 'field_58528c8fff96b',
          'label' => 'Coordinate',
          'name' => 'n7webmap_coord',
          'type' => 'google_map',
          'center_lat' => '43.6551217',
          'center_lng' => '11.0812834',
          'zoom' => '7',
          'instructions' => 'Insert point of interests from map to get coordinates'
      ),
      array (
        'key' => 'wm_poi_color',
        'name' => 'color',
        'label' => 'The color of the POI',
        'type' => 'color_picker',
        'instructions' => 'Use the color picker (click on the "Select Color" button) to select the color of the POI or insert direclty the color RGB code in the followong format: #RRGGBB'
      ),
      array (
        'key' => 'wm_poi_icon',
        'name' => 'icon',
        'label' => 'The WEBMAPP Icon of the POI',
        'type' => 'text',
        'instructions' => 'Insert the icon associated to the taxonomy term. Use the WEBMAPP icons: https://icon.webmapp.it/'
      ),
    array(
      'key' => 'wm_poi_code',
      'label' => __("Code"),
      'name' => 'code',
      'type' => 'text',
      'instructions' => 'Use this field to add a code to the POI.'

    ),

      array (
      'key' => 'wm_poi_noDetails',
      'label' => 'No Details',
      'name' => 'noDetails',
      'type' => 'true_false',
      'instructions' => 'Check this if you want to disable the details of this POI in the APP and webapp',
      'default_value' => 0
    ),
      array (
      'key' => 'wm_poi_noInteractions',
      'label' => 'No Interaction',
      'name' => 'noInteraction',
      'type' => 'true_false',
      'instructions' => 'Check this if you want to disable any interaction of this POI in the APP and webapp',
      'default_value' => 0
    ),
      array(
          'library' => 'all',
          'insert' => 'append',
          'key' => 'field_5853f586c83cd',
          'label' => 'Media Gallery',
          'name' => 'n7webmap_media_gallery',
          'type' => 'gallery'
      ),
      array(
          'sub_fields' => array(
              array(
                  'key' => 'field_585cdd464c509',
                  'label' => 'url',
                  'name' => 'net7webmap_related_url',
                  'type' => 'url'
              ),
          ),
          'layout' => 'table',
          'button_label' => 'Add Url',
          'key' => 'field_585cdc9229191',
          'label' => 'related url',
          'name' => 'n7webmap_rpt_related_url',
          'type' => 'repeater'
      )
  );

  return $poi_fields;
}

function getPoiAddressFields() {

  $poi_fields = array(
      array(
      'key' => 'field_58db8898b885d',
      'label' => 'Street',
      'name' => 'addr:street',
      'type' => 'text',
      'instructions' => 'Insert the name of the respective street.'
      ),
      array(
      'key' => 'field_58db8898b885e',
      'label' => 'Housenumber',
      'name' => 'addr:housenumber',
      'type' => 'text',
      'instructions' => 'Insert the name the house number (may contain letters, dashes or other characters).'
      ),
      array(
      'key' => 'field_58db8898b885f',
      'label' => 'Postcode',
      'name' => 'addr:postcode',
      'type' => 'text',
      'instructions' => 'The postal code of the building/area.'
      ),
      array(
      'key' => 'field_58db8898b885g',
      'label' => 'City',
      'name' => 'addr:city',
      'type' => 'text',
      'instructions' => 'The name of the city as given in postal addresses of the building/area.'
      )

  );

  return $poi_fields;
}

function getPoiContactFields() {

  $poi_fields = array(
      array(
      'key' => 'field_58db8898b886d',
      'label' => 'Phone',
      'name' => 'contact:phone',
      'type' => 'text',
      'instructions' => 'Insert the contact phone number (format: +[country code] [area code] [local number] eg: +39 050 123456).'
      ),
      array(
      'key' => 'field_58db8898b886e',
      'label' => 'Email',
      'name' => 'contact:email',
      'type' => 'email',
      'instructions' => 'Insert the contact email (must be a valid email address).'
      ),
      array(
      'key' => 'field_58db8898b886f',
      'label' => 'Opening Hours',
      'name' => 'opening_hours',
      'type' => 'text',
      'instructions' => 'Insert the opening hours of the POI. Please refer to the OSM Wiki for examples and explenations: http://wiki.openstreetmap.org/wiki/Key:opening_hours#Examples'
      ),
      array(
      'key' => 'field_58db8898b886g',
      'label' => 'Capacity',
      'name' => 'capacity',
      'type' => 'text',
      'instructions' => 'Insert the capacity a facility is suitable for.'
      )

  );

  return $poi_fields;
}

if (function_exists('acf_add_local_field_group')):

  acf_add_local_field_group(array(
      'key' => 'group_58528c8aa5b2f',
      'title' => 'POI',
      'fields' => getPoiCustomFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'poi',
              ),
          ),
      ),
      'menu_order' => 0,
      'active' => 1
  ));

  acf_add_local_field_group(array(
      'title' => 'Adress Info',
      'fields' => getPoiAddressFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'poi',
              ),
          ),
      ),
      'menu_order' => 1,
      'active' => 1
  ));

  acf_add_local_field_group(array(
      'title' => 'Contact and general info',
      'fields' => getPoiContactFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'poi',
              ),
          ),
      ),
      'menu_order' => 2,
      'active' => 1
  ));



endif;
