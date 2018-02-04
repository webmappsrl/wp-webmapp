<?php
/**
 * voucher
 *
 * Snippet by GenerateWP.com
 * Generated on February 4, 2018 11:05:30
 * @link https://generatewp.com/snippet/odvgd1g/
 */


// Register Custom Post Type VOUCHER
function wm_cpt_voucher() {

	$labels = array(
		'name'                  => _x( 'Vouchers', 'Post Type General Name', 'webmap_net7' ),
		'singular_name'         => _x( 'Voucher', 'Post Type Singular Name', 'webmap_net7' ),
		'menu_name'             => __( 'Vouchers', 'webmap_net7' ),
		'name_admin_bar'        => __( 'Voucher', 'webmap_net7' ),
		'archives'              => __( 'Voucher Archives', 'webmap_net7' ),
		'attributes'            => __( 'Voucher Attributes', 'webmap_net7' ),
		'parent_item_colon'     => __( 'Parent Item:', 'webmap_net7' ),
		'all_items'             => __( 'All Vouchers', 'webmap_net7' ),
		'add_new_item'          => __( 'Add New Voucher', 'webmap_net7' ),
		'add_new'               => __( 'Add New Voucher', 'webmap_net7' ),
		'new_item'              => __( 'New Voucher', 'webmap_net7' ),
		'edit_item'             => __( 'Edit Voucher', 'webmap_net7' ),
		'update_item'           => __( 'Update Voucher', 'webmap_net7' ),
		'view_item'             => __( 'View Voucher', 'webmap_net7' ),
		'view_items'            => __( 'View Vouchers', 'webmap_net7' ),
		'search_items'          => __( 'Search Voucher', 'webmap_net7' ),
		'not_found'             => __( 'Not found', 'webmap_net7' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'webmap_net7' ),
		'featured_image'        => __( 'Featured Image', 'webmap_net7' ),
		'set_featured_image'    => __( 'Set featured image', 'webmap_net7' ),
		'remove_featured_image' => __( 'Remove featured image', 'webmap_net7' ),
		'use_featured_image'    => __( 'Use as featured image', 'webmap_net7' ),
		'insert_into_item'      => __( 'Insert into Voucher', 'webmap_net7' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Voucher', 'webmap_net7' ),
		'items_list'            => __( 'Voucher list', 'webmap_net7' ),
		'items_list_navigation' => __( 'Voucher list navigation', 'webmap_net7' ),
		'filter_items_list'     => __( 'Filter Voucher list', 'webmap_net7' ),
	);
	$args = array(
		'label'                 => __( 'Voucher', 'webmap_net7' ),
		'description'           => __( 'Voucher used to activate download', 'webmap_net7' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-images-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'voucher', $args );

}
add_action( 'init', 'wm_cpt_voucher', 0 );

// CUSTOM FIELDS FOR VOUCHER

function getWMACFVoucher() {
 $fields = array(
		array(
			'key' => 'wm_voucher_route',
			'label' => 'Route',
			'name' => 'route',
			'type' => 'post_object',
			'instructions' => 'Enter the ROUTE corresponding to the voucher you want to activate.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'route',
			),
			'taxonomy' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'return_format' => 'id',
			'ui' => 1,
		),
		array(
			'key' => 'wm_voucher_total_number',
			'label' => 'Total Number',
			'name' => 'total_number',
			'type' => 'range',
			'instructions' => 'Enter the total number of VOUCHER codes. The codes will be generated automatically starting from the main code of the voucher you entered in the title. If for example the main code is 18-0040 and the total number you enter in this field is 3, the following 5 codes will automatically be activated: 18-0040-01, 18-0040-02, 18-0040-03',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'min' => 1,
			'max' => 99,
			'step' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'wm_voucher_expire_date',
			'label' => 'Expire date',
			'name' => 'expire_date',
			'type' => 'date_picker',
			'instructions' => 'Enter the expiry date of the voucher.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		),
	);

    return $fields;

}

function getWMACFVoucherHidden() {
 	$fields = array(
		array(
			'key' => 'wm_voucher_used_codes_number',
			'label' => 'Used Codes Number',
			'name' => 'used_codes_number',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'wm_voucher_used_codes',
			'label' => 'Used Codes',
			'name' => 'used_codes',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	);
		return $fields;

}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_wm_voucher',
	'title' => 'Voucher',
	'fields' => getWMACFVoucher(),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'voucher',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array(
	'key' => 'group_wm_voucher_hidden',
	'title' => 'Voucher hidden',
	'fields' => getWMACFVoucherHidden(),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'voucher',
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

// CUSTOM ADMIN COLUMNS FOR VOUCHER
function ac_wm_voucher() {

	ac_register_columns( 'voucher', array(
		array(
			'columns' => array(
				'title' => array(
					'type' => 'title',
					'label' => 'Titolo',
					'width' => '',
					'width_unit' => '%',
					'edit' => 'off',
					'sort' => 'on',
					'name' => 'title'
				),
				'5a76f18cdc65d' => array(
					'type' => 'column-acf_field',
					'label' => 'Route',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_voucher_route',
					'post_property_display' => 'title',
					'post_link_to' => '',
					'edit' => 'off',
					'sort' => 'on',
					'filter' => 'on',
					'filter_label' => '',
					'name' => '5a76f18cdc65d'
				),
				'5a76f0d852191' => array(
					'type' => 'column-acf_field',
					'label' => 'Expire date',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_voucher_expire_date',
					'date_format' => 'acf',
					'edit' => 'off',
					'sort' => 'on',
					'filter' => 'on',
					'filter_label' => '',
					'filter_format' => 'range',
					'name' => '5a76f0d852191'
				),
				'5a76f18cde4ea' => array(
					'type' => 'column-acf_field',
					'label' => 'Total Number',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_voucher_total_number',
					'edit' => 'off',
					'sort' => 'off',
					'filter' => 'off',
					'filter_label' => '',
					'name' => '5a76f18cde4ea'
				),
				'5a76f18cde9ee' => array(
					'type' => 'column-acf_field',
					'label' => 'Already used (number)',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_voucher_used_codes_number',
					'character_limit' => '20',
					'edit' => 'off',
					'sort' => 'off',
					'filter' => 'off',
					'filter_label' => '',
					'name' => '5a76f18cde9ee'
				),
				'5a76f2a3e2be8' => array(
					'type' => 'column-acf_field',
					'label' => 'Already used (number)',
					'width' => '',
					'width_unit' => '%',
					'field' => 'wm_voucher_used_codes_number',
					'character_limit' => '20',
					'edit' => 'off',
					'sort' => 'off',
					'filter' => 'off',
					'filter_label' => '',
					'name' => '5a76f2a3e2be8'
				)
			),
			
		)
	) );
}
add_action( 'ac/ready', 'ac_wm_voucher' );