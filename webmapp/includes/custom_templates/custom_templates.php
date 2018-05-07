<?php
add_filter( 'page_template', 'wm_page_template_poi_form' );
function wm_page_template_poi_form( $page_template )
{
    if ( is_page( 'poi-form' ) ) {
        $page_template = dirname( __FILE__ ) . '/poi_form.php';
    }
    return $page_template;
}