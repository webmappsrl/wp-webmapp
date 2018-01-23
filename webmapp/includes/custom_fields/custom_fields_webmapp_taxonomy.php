<?php //FILE: custom_fields_webmapp_taxonomy.php

function getWebmappTaxonomyCustomFields () {
  $fields = 
  array (
		array (
			'key' => 'wm_taxonomy_color',
			'label' => 'color',
			'name' => 'color',
			'type' => 'color_picker',
			'instructions' => 'Use the color picker (click on the "Select Color" button) to select the color of the POI or insert direclty the color RGB code in the followong format: #RRGGBB'
		),
		array (
			'key' => 'wm_taxonomy_icon',
			'label' => 'Icon',
			'name' => 'icon',
			'type' => 'text',
			'instructions' => 'Insert the icon associated to the taxonomy term. Use the WEBMAPP icons: https://icon.webmapp.it'
		),array (
      'key' => 'wp_taxonomy_show_by_default',
      'label' => 'Show by default',
      'name' => 'show_by_default',
      'type' => 'true_false',
      'instructions' => 'Enable this feature to display items (POIs or TRACKS) on the map by default.',
      'default_value' => 1
    ),
	);
  return $fields;
}


if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'wm_taxonomy_main',
	'title' => 'Additional info',
	'fields' => getWebmappTaxonomyCustomFields(),
	'location' => array (
		array (
			array (
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'webmapp_category',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;