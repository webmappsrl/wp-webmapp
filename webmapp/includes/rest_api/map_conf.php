<?php 
// map_conf.php
/* CONF API */

/**
 * Returns the conf object for a map
 *
 * @return object|null .
 */

function webmapp_map_conf( WP_REST_Request $request ) {
  $param = $request->get_url_params();
  $map_id = $param["id"];  

  $map = get_post($map_id);
   
  $result = array();
  
  /*Aggiungo dati di esempio */
//   var_dump($map); //controlla i campi disponibili per la mappa
  $result["map-id"] = $map->ID;
  $result["name"] = $map->post_title;
  
  /*
   * per prendere i custom fields usare la funzione get_field(custom_field, id)
   * per la lista dei custom fields fare riferimento al file includes/custom_fields.php 
   * dove sono registrati i custom fields per ogni post type
   */
  $result["type"] = get_field("field_map_type", $map->ID);
    
  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/map/(?P<id>\d+).conf', array(
      'methods' => 'GET',
      'callback' => 'webmapp_map_conf'
    )
  );
});

