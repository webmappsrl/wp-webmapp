<?php // custom_fields_maps.php

if (function_exists('acf_add_local_field_group')):

  function getMapMainInfoCustomFields() {
    $map_fields = array(
        array(
            'multiple' => 0,
            'allow_null' => 0,
            'choices' => array(
                'all' => 'all',
                'layers' => 'layers',
                'single' => 'single route',
            ),
            'key' => 'field_map_type',
            'label' => 'type',
            'name' => 'n7webmap_type',
            'type' => 'select'
        ),
        array(
            'post_type' => array(
                0 => 'route',
            ),
            'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
            ),
            'max' => 1,
            'return_format' => 'object',
            'key' => 'field_single_map_route',
            'label' => 'related route',
            'name' => 'net7webmap_map_route',
            'type' => 'relationship',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_map_type',
                        'operator' => '==',
                        'value' => 'single',
                    ),
                ),
            )
        ),
        array (
			'key' => 'wm_map_layer_poi',
			'label' => 'Poi\'s layer',
			'name' => 'layer_poi',
			'type' => 'taxonomy',
			'instructions' => 'Select the POI layer (webmapp category) used to create the POI menu and layers in the APP.',
			'required' => 0,
			'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_map_type',
                        'operator' => '==',
                        'value' => 'layers',
                    ),
                ),
            ),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'webmapp_category',
			'field_type' => 'radio',
			'allow_null' => 1,
			'add_term' => 0,
			'save_terms' => 0,
			'load_terms' => 0,
			'return_format' => 'id',
			'multiple' => 0,
		),
array (
      'key' => 'wm_map_tiles',
      'label' => 'tiles',
      'name' => 'tiles',
      'type' => 'text',
      'instructions' => 'Insert the tiles URL in the following form http://{s}.tile.osm.org/',
      'required' => 1,
      'default_value' => 'http://{s}.tile.osm.org/',
    ),
array(           
            'key' => 'wm_map_style',
            'label' => 'Map Style',
            'instructions' => 'Use this textarea to set the MAPP (app and webapp) style. Use valid json format.',
            'name' => 'style',
            'type' => 'textarea'
        ),

      array(           
            'key' => 'wm_map_pages_title',
            'label' => 'Pages Menu Title (About)',
            'instructions' => 'Set the title of the pages menu item. If empty About is used.',
            'name' => 'pages_title',
            'type' => 'text'
        ),

      array(
          'post_type' => array(
              0 => 'page'
          ),
          'filters' => array(
              0 => 'search'
          ),
          'return_format' => 'object',
          'key' => 'wm_map_pages',
          'label' => 'Map Pages',
          'name' => 'pages',
          'type' => 'relationship',
          'instructions' => 'Select pages to be added to the menu'
          ),
      array (
      'key' => 'wp_map_has_offline',
      'label' => 'OFFLINE',
      'name' => 'has_offline',
      'type' => 'true_false',
      'instructions' => 'Activate OFFLINE features',
      'default_value' => 0
    ),
    array (
      'key' => 'wp_map_offline_label',
      'label' => 'OFFLINE menu label',
      'name' => 'offline_menu_label',
      'type' => 'text',
      'instructions' => 'Set The Offline menu label (Mappa offline)',
      'conditional_logic' => array (
        array (
          array (
            'field' => 'wp_map_has_offline',
            'operator' => '==',
            'value' => '1',
          ),
        ),
      ),
    ),

    array (
      'key' => 'wp_map_app_id',
      'label' => 'APP ID',
      'name' => 'app_id',
      'type' => 'text',
      'instructions' => 'Insert the app ID (e.g. it.webmapp.mappadeimontipisani)'
     ),
    array (
      'key' => 'wp_map_app_description',
      'label' => 'APP description',
      'name' => 'app_description',
      'type' => 'textarea',
      'instructions' => 'Insert the app description'
     ),
    array (
      'key' => 'wp_map_app_icon',
      'label' => 'APP ICON',
      'name' => 'app_icon',
      'type' => 'image',
      'instructions' => 'Upload the APP (PNG 1024x1024 not alpha)',
      'return_format' => 'url',
      'preview_size' => 'thumbnail',
      'library' => 'all',
    ),array (
      'key' => 'wp_map_app_splash',
      'label' => 'APP SPLASH',
      'name' => 'app_splash',
      'type' => 'image',
      'instructions' => 'Upload the splashscreen. Splash screen 2732 x 2732 png (not alpha), viewpoort inside the 1200 x 1200 square.',
      'return_format' => 'url',
      'preview_size' => 'thumbnail',
      'library' => 'all',
    ),
          array (
      'key' => 'wp_map_has_languages',
      'label' => 'Multilanguages',
      'name' => 'has_languages',
      'type' => 'true_false',
      'instructions' => 'Activate multilanguages features',
      'default_value' => 0
    ),
    array (
      'key' => 'wp_map_languages_label',
      'label' => 'Multilanguages menu label',
      'name' => 'languages_menu_label',
      'type' => 'text',
      'instructions' => 'Set The Multilanguages menu label (Cambia lingua)',
      'conditional_logic' => array (
        array (
          array (
            'field' => 'wp_map_has_languages',
            'operator' => '==',
            'value' => '1',
          ),
        ),
      ),
    ),
    array (
      'key' => 'wp_map_languages_list',
      'label' => 'Multilanguages list',
      'name' => 'languages_list',
      'type' => 'text',
      'instructions' => 'Set The Multilanguages available languages: use coma separated values (e.g. it_IT,en_EN,fr_FR)',
      'conditional_logic' => array (
        array (
          array (
            'field' => 'wp_map_has_languages',
            'operator' => '==',
            'value' => '1',
          ),
        ),
      ),
    )

    );
    return $map_fields;
  }
  
  function getMapGeoInfoCustomFields() {
    $map_fields = array(
        array(           
            'key' => 'field_map_bbox',
            'label' => 'bounding box',
            'name' => 'n7webmap_map_bbox',
            'type' => 'textarea'
        ),
                array(           
            'key' => 'wm_map_config_url',
            'label' => 'Config URL',
            'name' => 'config_url',
            'type' => 'text'
        )
    );
    return $map_fields;
  }
  
    acf_add_local_field_group(array(
      'key' => 'group_58528c8aa5bbfff',
      'title' => 'Authorized users',
      'fields' => array(
          array(
              'role' => array(
                  0 => 'subscriber',
              ),
              'multiple' => 1,
              'allow_null' => 1,
              'key' => 'field_58b43d34d1201',
              'label' => 'authorized users',
              'name' => 'n7webmap_map_users',
              'type' => 'user',
          )
      ),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'map',
              ),
          ),
      ),
      'menu_order' => 0,
      'active' => 1
  ));

acf_add_local_field_group(array(
      'key' => 'group_58b9286d1af3111',
      'title' => __("Geographical Info"),
      'fields' => getMapGeoInfoCustomFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'map',
              ),
          ),
      ),
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'menu_order' => 1
  ));
  
  acf_add_local_field_group(array(
      'key' => 'group_58b9286d1af31',
      'title' => __("Main info"),
      'fields' => getMapMainInfoCustomFields(),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'map',
              ),
          ),
      ),
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'menu_order' => 2
  ));

endif;

