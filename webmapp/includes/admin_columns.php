<?php
/**
 * Created by PhpStorm.
 * User: lucabaroncini
 * Date: 11/01/18
 * Time: 16:55
 */

function ac_custom_column_settings_9308c7ca() {

	ac_register_columns( 'wp-taxonomy_webmapp_category', array(
		array(
			'columns' => array(
				'name' => array(
					'type' => 'name',
					'label' => 'Nome',
					'width' => '',
					'width_unit' => '%',
					'edit' => 'off',
					'sort' => 'on',
					'name' => 'name'
				),
				'slug' => array(
					'type' => 'slug',
					'label' => 'Slug',
					'width' => '',
					'width_unit' => '%',
					'edit' => 'off',
					'sort' => 'on',
					'name' => 'slug'
				),
				'description' => array(
					'type' => 'description',
					'label' => 'Descrizione',
					'width' => '',
					'width_unit' => '%',
					'edit' => 'off',
					'sort' => 'on',
					'name' => 'description'
				),
				'5a577d666decc' => array(
					'type' => 'column-acf_field',
					'label' => 'Color',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_taxonomy_color',
					'edit' => 'on',
					'name' => '5a577d666decc'
				),
				'5a577d666ed36' => array(
					'type' => 'column-acf_field',
					'label' => 'Icon',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_taxonomy_icon',
					'character_limit' => '20',
					'edit' => 'on',
					'name' => '5a577d666ed36'
				),
				'posts' => array(
					'type' => 'posts',
					'label' => 'Conteggio',
					'width' => '74',
					'width_unit' => 'px',
					'sort' => 'on',
					'name' => 'posts'
				)
			),

		)
	) );
}
add_action( 'ac/ready', 'ac_custom_column_settings_9308c7ca' );


function my_column_meta_value_mask_key( $value, $object_id, $column ) {
	$custom_field_name = '5a577d666ed36';

	if ( $custom_field_name == $column->get_name() && !empty($value) ) {
		$a = 0;
		$value = '<span class="' . $value . '"></span>';
	}

	return $value;
}
add_filter( 'ac/column/value', 'my_column_meta_value_mask_key', 10, 3 );