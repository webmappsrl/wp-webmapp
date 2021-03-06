<?php // custom_fields_tracks.php


function getTrackCustomFields() {
  $fields = array(
      array (
        'default_value' => '',
        'key' => 'field_58c7ef836e6e2',
        'label' => 'Track color',
        'name' => 'n7webmapp_track_color',
        'type' => 'color_picker'
      ), array(
      'key' => 'wm_track_code',
      'label' => __("Route code"),
      'name' => 'code',
      'type' => 'text'
    ),
      array(
          'library' => 'all',
          'insert' => 'append',
          'key' => 'field_5853f586c83cd',
          'label' => 'Media Gallery',
          'name' => 'n7webmap_track_media_gallery',
          'type' => 'gallery'
      ),
      array(
          'key' => 'field_585933bd79a1a',
          'label' => 'Start',
          'name' => 'n7webmap_start',
          'type' => 'text'
      ),
      array(
          'post_type' => array(
              0 => 'poi',
          ),
          'taxonomy' => array(
          ),
          'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
          ),
          'max' => '1',
          'return_format' => 'object',
          'key' => 'field_585933d079a1b',
          'label' => 'start POI',
          'name' => 'n7webmap_start_poi',
          'type' => 'relationship'
      ),
      array(
          'key' => 'field_585933f779a1c',
          'label' => 'end',
          'name' => 'n7webmap_end'
      ),
      array(
          'post_type' => array(
              0 => 'poi',
          ),
          'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
          ),
          'max' => '1',
          'return_format' => 'object',
          'key' => 'field_5859340879a1d',
          'label' => 'end POI',
          'name' => 'n7webmap_end_poi',
          'type' => 'relationship'
      ),
      array(
          'post_type' => array(
              0 => 'poi'
          ),
          'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
          ),
          'return_format' => 'object',
          'key' => 'field_5859342579a1e',
          'label' => 'related POI',
          'name' => 'n7webmap_related_poi',
          'type' => 'relationship',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
          ),
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
  return $fields;
}


function getTrackHikingFields() {


  // HIKING FIELDS (from OSM)
  
  $poi_fields = array(
      array(
      'key' => 'wm_track_ref',
      'label' => 'REF (the track number or code)',
      'name' => 'ref',
      'type' => 'text',
      'instructions' => 'Insert the track number or code. Format alphanumeric. Example: 135 or E121.'
      ),
      array(
      'key' => 'wm_track_ascent',
      'label' => 'Ascent (D+)',
      'name' => 'ascent',
      'type' => 'text',
      'instructions' => 'Insert the total ascent (positive gain) of the track considering the track walked from the start point to the end point. Use meter as unit.'
      ),
      array(
      'key' => 'wm_track_descent',
      'label' => 'Descent (D-)',
      'name' => 'descent',
      'type' => 'text',
      'instructions' => 'Insert the the total descent (negative gain) of the track considering the track walked from the start point to the end point. Use meter as unit.'
      ),
      array(
      'key' => 'wm_track_distance',
      'label' => 'Distance (lenght of the track)',
      'name' => 'distance',
      'type' => 'text',
      'instructions' => 'Insert the total lenght of the track. Use meter as unit.'
      ),
      array(
      'key' => 'wm_track_duration:forward',
      'label' => 'Duration forward',
      'name' => 'duration:forward',
      'type' => 'text',
      'instructions' => 'Insert the estimated time to walk the track form the start point to the end point. Use the following format: hh:mm'
      ),
      array(
      'key' => 'wm_track_duration_backword',
      'label' => 'Duration backward',
      'name' => 'duration:backward',
      'type' => 'text',
      'instructions' => 'Insert the estimated time to walk the track form the end point to the start point. Use the following format: hh:mm'
      ),
      array(
      'key' => 'wm_track_cai_scale',
      'label' => 'Difficulty',
      'name' => 'cai_scale',
      'type' => 'text',
      'instructions' => 'Insert the path difficulty classification according to the Club Alpino Italiano scale (T,E,EE,EEA). Please refer to the following address for more information: http://wiki.openstreetmap.org/wiki/Proposed_features/cai_scale'
      ),

  );

  return $poi_fields;
}



if (function_exists('acf_add_local_field_group')):

  acf_add_local_field_group(array(
      'key' => 'group_5853f89462666b4',
      'title' => 'Import track (GPX file)',
      'fields' => array(
          array(
              'key' => 'field_parse_gpx',
              'label' => 'Import track from GPX file',
              'name' => 'n7webmap_import_gpx',
              'type' => 'message'
          ),
      ),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'track',
              ),
          ),
      ),
      'menu_order' => 0,
      'position' => 'acf_after_title',
  ));

  acf_add_local_field_group(array(
      'key' => 'group_5853f894626b4',
      'title' => 'Track',
      'fields' => getTrackCustomFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'track',
              ),
          ),
      ),
      'menu_order' => 1
  ));

  acf_add_local_field_group(array(
      'title' => 'Hiking Info',
      'fields' => getTrackHikingFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'track',
              ),
          ),
      ),
      'menu_order' => 2
  ));

endif;

