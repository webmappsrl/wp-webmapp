<?php

// REGISTRO LE 5 TASSONOMIE (where,when,theme,activity,who)

function wm_ct_routes() {
   WmRegisterTaxonomy('activity','Activity','Activities');
   WmRegisterTaxonomy('theme','Theme','Themes');
   WmRegisterTaxonomy('where','Places to go','Places to go');
   WmRegisterTaxonomy('when','Season','Seasons');
   WmRegisterTaxonomy('who','Target','Targets');
}

add_action( 'init', 'wm_ct_routes', 0 );

function WmRegisterTaxonomy($name,$label_singular,$label_plural) {

	$labels = array(
		'name'                       => _x( $label_plural, 'Taxonomy General Name', 'webmap_net7' ),
		'singular_name'              => _x( $label_singular, 'Taxonomy Singular Name', 'webmap_net7' ),
		'menu_name'                  => __( $label_plural, 'webmap_net7' ),
		'all_items'                  => __( 'All Items', 'webmap_net7' ),
		'parent_item'                => __( 'Parent Item', 'webmap_net7' ),
		'parent_item_colon'          => __( 'Parent Item:', 'webmap_net7' ),
		'new_item_name'              => __( 'New Item Name', 'webmap_net7' ),
		'add_new_item'               => __( 'Add New Item', 'webmap_net7' ),
		'edit_item'                  => __( 'Edit Item', 'webmap_net7' ),
		'update_item'                => __( 'Update Item', 'webmap_net7' ),
		'view_item'                  => __( 'View Item', 'webmap_net7' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'webmap_net7' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'webmap_net7' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'webmap_net7' ),
		'popular_items'              => __( 'Popular Items', 'webmap_net7' ),
		'search_items'               => __( 'Search Items', 'webmap_net7' ),
		'not_found'                  => __( 'Not Found', 'webmap_net7' ),
		'no_terms'                   => __( 'No items', 'webmap_net7' ),
		'items_list'                 => __( 'Items list', 'webmap_net7' ),
		'items_list_navigation'      => __( 'Items list navigation', 'webmap_net7' ),
	);
	$rewrite = array(
		'slug'                       => $name,
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'meta_box_cb'				 => false,
		'rewrite'                    => $rewrite,
		'show_in_rest'               => true,
	);
	register_taxonomy( $name, array( 'route', 'track' ), $args );

}

 