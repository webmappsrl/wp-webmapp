<?php

require_once 'custom_fields.php';
require_once 'geojson_track.php';
require_once 'geojson_tracks.php';
require_once 'geojson_pois.php';


add_action('rest_api_init', function () {


  // POIS
  $poi_fields = getPoiCustomFields();
  registerField("poi", $poi_fields);
  $poi_fields = getPoiAddressFields();
  registerField("poi", $poi_fields);
  $poi_fields = getPoiContactFields();
  registerField("poi", $poi_fields);

  // ROUTES
  $routes_fields = getRouteCustomFields();
  registerField("route", $routes_fields);

  // TRACKS
  $track_fields = getTrackCustomFields();
  registerField("track", $track_fields);
  $track_fields = getTrackHikingFields();
  registerField("track", $track_fields);

  // MAP
  $map_fields = getMapMainInfoCustomFields();
  registerField("map", $map_fields);
  $map_fields = getMapGeoInfoCustomFields();
  registerField("map", $map_fields);

  // WEBMAPP TAXONOMY
  $taxonomy_fields = getWebmappTaxonomyCustomFields();
  registerField("webmapp_category", $taxonomy_fields);

  // PAGE
  $page_fields = getPageCustomFields();
  registerField("page",$page_fields);

  registerExtraField();
});


function registerField($post_type, $fields) {

  foreach ($fields as $field) {
    $field_name = $field["name"];
    $field_key = $field["key"];
    register_rest_field($post_type, $field_name, array(
      'get_callback' => function ($poi_array, $field_name) {

        $taxonomy = '';
        if (isset($poi_array['taxonomy'])) {
          $taxonomy = $poi_array['taxonomy'];
        }

        $poi_obj = get_post($poi_array['id']);

        if ($field_name == "n7webmap_route_media_gallery") {
          $field_name = "field_5853f586c83cd"; //TODO: rendere questa cosa più intelligente! la galleria ha dei problemi ad essere recuperata con il name
        }
        if ($field_name == "n7webmap_media_gallery") {
          $field_name = "field_5853f586c83cd"; //TODO: rendere questa cosa più intelligente! la galleria ha dei problemi ad essere recuperata con il name
        }
        else {
          if ($field_name == "n7webmap_map_bbox") {
            $bbox = get_field($field_name, $poi_obj->ID);
            if ($bbox == NULL || $bbox == "") {
              return get_option('webmapp_bounding_box');
            }
          }
        }
        $id = $poi_obj->ID;
        if ($taxonomy == 'webmapp_category') {
          $id = 'webmapp_category_' . $poi_array['id'];
        }
        return get_field($field_name, $id);
      }
    ));
  }
}

function registerExtraField() {

  register_rest_field("track", 'n7webmap_geojson', array(
    'get_callback' => function ($obj_array, $field_name) {

      return get_post_meta($obj_array['id'], $field_name, TRUE);
    }
  ));

  register_rest_field("route", 'n7webmapp_route_bbox', array(
    'get_callback' => function ($obj_array, $field_name) {

      return get_post_meta($obj_array['id'], $field_name, TRUE);
    }
  ));
}

function get_fields_from_group($group_id) {
  $GroupOrPostSlug = $group_id;
  //or insert the ID of your fields Group.
  $groupID = '';

  global $wpdb;
  $fields = array();
  if (empty($groupID)) {
    $groupID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name =  '$GroupOrPostSlug' ");
  }
  if (empty($groupID)) {
    $groupID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title =  '$GroupOrPostSlug' ");
  }

  $custom_field_keys = acf_get_fields_by_id(0);
  return $custom_field_keys;
}

/**
 * Returns the bounding box webmapp setting
 *
 * @return string|null bounding box webmapp setting or null if none.
 */
function webmapp_bbox($data) {
  $bbox = get_option('webmapp_bounding_box');

  if (empty($bbox)) {
    return NULL;
  }
  $data = array("bounding_box" => $bbox);
  return new WP_REST_Response($data, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/bbox', array(
    'methods' => 'GET',
    'callback' => 'webmapp_bbox',
  ));
});

/**
 * Returns the routes for users
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_user_maps(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $user_id = $param["id"];

  $maps = get_posts(array(
    'post_type' => 'map',
    'meta_query' => array(
      array(
        'key' => 'n7webmap_map_users',
        // name of custom field
        'value' => '"' . $user_id . '"',
        // matches exaclty "123", not just 123. This prevents a match for "1234"
        'compare' => 'LIKE'
      )
    )
  ));
  $controller = new WP_REST_Posts_Controller("map");
  $result = array();
  foreach ($maps as $post) {
    $data = $controller->prepare_item_for_response($post, $request);
    $result[] = $controller->prepare_response_for_collection($data);
  }
  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/maps/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'webmapp_user_maps',
      'args' => array(
        'id' => array(
          'validate_callback' => function ($param, $request, $key) {
            return is_numeric($param);
          }
        ),
      )
    )
  );
});


/**
 * Returns the routes id for users
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_user_maps_id(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $user_id = $param["id"];

  $routes = get_posts(array(
    'post_type' => 'map',
    'meta_query' => array(
      array(
        'key' => 'n7webmap_map_users',
        // name of custom field
        'value' => '"' . $user_id . '"',
        // matches exaclty "123", not just 123. This prevents a match for "1234"
        'compare' => 'LIKE'
      )
    )
  ));
  $controller = new WP_REST_Posts_Controller("map");
  $result = array();
  foreach ($routes as $post) {
    $result[$post->ID] = array("active" => TRUE);
  }
  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/maps_id/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'webmapp_user_maps_id',
      'args' => array(
        'id' => array(
          'validate_callback' => function ($param, $request, $key) {
            return is_numeric($param);
          }
        ),
      )
    )
  );
});


/**
 * Check user credentials and return user data
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_user_data(WP_REST_Request $request) {
  $user = $request->get_param("user");
  $pass = $request->get_param("pass");
  $user = wp_authenticate_username_password(NULL, $user, $pass);
  if ($user->data) {
    $user_data = $user->data;
    $user_data->first_name = get_user_meta($user->ID, "first_name", TRUE);
    $user_data->last_name = get_user_meta($user->ID, "last_name", TRUE);
    $user_data->user_pass = "";
    return new WP_REST_Response($user_data, 200);
  }
  else {
    return new WP_REST_Response($user, 401);
  }
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/user', array(
      'methods' => 'POST',
      'callback' => 'webmapp_user_data'
    )
  );
});


// Map.conf API
require_once('rest_api/map_conf.php');
require_once('rest_api/api_voucher.php');

/**
 * Returns the geojson object for a route
 *
 * @return object|null .
 */

function webmapp_route_geojson(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $obj_id = $param["id"];

  $obj = get_post($obj_id);


  $result = array();
  /*Aggiungo dati di esempio */
//   var_dump($map); //controlla i campi disponibili per la mappa
  $result["route-id"] = $obj->ID;
  $result["name"] = $obj->post_title;

  /*
   * per prendere i custom fields usare la funzione get_field(custom_field, id)
   * per la lista dei custom fields fare riferimento al file includes/custom_fields.php 
   * dove sono registrati i custom fields per ogni post type
   */
  $result["related_track"] = get_field("n7webmap_route_related_track", $obj->ID);

  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/route/(?P<id>\d+).geojson', array(
      'methods' => 'GET',
      'callback' => 'webmapp_route_geojson'
    )
  );
});

/**
 * Returns the track object for a route
 *
 * @return object|null .
 */

function webmapp_track_geojson(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $obj_id = $param["id"];

  $result = geojson_track($obj_id);

  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/track/(?P<id>\d+).geojson', array(
      'methods' => 'GET',
      'callback' => 'webmapp_track_geojson'
    )
  );
});


/**
 * Returns the geojson object for a route
 *
 * @return object|null .
 */

function webmapp_poi_geojson(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $obj_id = $param["id"];

  $obj = get_post($obj_id);


  $result = array();
  /*Aggiungo dati di esempio */
//   var_dump($map); //controlla i campi disponibili per la mappa
  $result["map-id"] = $obj->ID;
  $result["name"] = $obj->post_title;

  /*
   * per prendere i custom fields usare la funzione get_field(custom_field, id)
   * per la lista dei custom fields fare riferimento al file includes/custom_fields.php 
   * dove sono registrati i custom fields per ogni post type
   */
  $result["coords"] = get_field("n7webmap_coord", $obj->ID);

  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/poi/(?P<id>\d+).geojson', array(
      'methods' => 'GET',
      'callback' => 'webmapp_poi_geojson'
    )
  );
});

/**
 * Returns the routes id for users
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_user_route_id(WP_REST_Request $request) {
  $param = $request->get_url_params();
  $user_id = $param["id"];

  $routes = get_posts(array(
    'post_type' => 'route',
    'numberposts' => -1,
    'meta_query' => array(
      array(
        'key' => 'n7webmap_route_users',
        // name of custom field
        'value' => '"' . $user_id . '"',
        // matches exaclty "123", not just 123. This prevents a match for "1234"
        'compare' => 'LIKE'
      )
    )
  ));

  $result = array();
  foreach ($routes as $post) {
    $result[$post->ID] = array("active" => TRUE);
  }
  return new WP_REST_Response($result, 200);
}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/route_id/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'webmapp_user_route_id',
      'args' => array(
        'id' => array(
          'validate_callback' => function ($param, $request, $key) {
            return is_numeric($param);
          }
        ),
      )
    )
  );
});


/**
 * Check user credentials and return user data
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_user_registration(WP_REST_Request $request) {
  $to = get_option('admin_email');
  $email = $request->get_param("mail");
  $pass = $request->get_param("pass");
  $first_name = $request->get_param("first_name");
  $last_name = $request->get_param("last_name");
  $app_name = $request->get_param('appname');

  $newsletter = $request->get_param('newsletter');
  $country = $request->get_param('country');
  
  $subject = $app_name . ' - Nuova registrazione';
  
  $message = "Buongiorno, un nuovo utente ha effettuato la registrazione dalla APP Myeasyroute.<br /><br />";
  $message .= " Indirizzo email: ' . $email<br /><br />";
  $message .= " Newsletter: $newsletter<br /><br />";
  $message .= " Country: $country<br /><br />";

  $headers = array('Content-Type: text/html; charset=UTF-8');

  if (NULL == username_exists($email)) {

    $user_id = wp_create_user($email, $pass, $email);

    wp_update_user(
      array(
        'ID' => $user_id,
        'nickname' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name
      )
    );

    if ($newsletter == true) {
      update_user_meta( $user_id, 'newsletter', true);
    }
      update_user_meta( $user_id, 'country', $country);

    // Set the role
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    wp_mail($to, $subject, $message, $headers);

    return new WP_REST_Response($user, 200);
  }
  else {

    return new WP_REST_Response('L\'emal inserita risulta già registrata', 401);
  }


}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/users', array(
      'methods' => 'POST',
      'callback' => 'webmapp_user_registration'
    )
  );
});

/**
 * Send mail
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function webmapp_send_mail_request(WP_REST_Request $request) {

  $to = get_option('admin_email');
  $email = $request->get_param("email");
  $route_id = $request->get_param('pack');
  $app_name = $request->get_param('appname');
  $title = get_the_title($route_id);

  // Email the user
  if ($route_id && $email && $title) {
    $subject = $app_name . ' - Nuova richiesta download itinerario';
    $user = get_user_by('email', $email);
    $token = $token = bin2hex(random_bytes(10));
    $auth_link = '<a href="' . get_site_url(NULL, 'wp-json/webmapp/v1/authorize?u=' . base64_encode($user->ID) . '&r=' . base64_encode($route_id)) . '&app=' . base64_encode($app_name) . '&token=' . $token .'">AUTORIZZA</a>';
    $deny_link = '<a href="' . get_site_url(NULL, 'wp-json/webmapp/v1/deny?u=' . base64_encode($user->ID) . '&r=' . base64_encode($route_id)) . '&app=' . base64_encode($app_name) . '&token=' . $token . '">NEGA</a>';
    $message = 'Buongiorno, l’utente ' . $user->first_name . ' ' . $user->last_name . ' ' .  $email . ' ha richiesto il download dell’itinerario "' . $title . '"';
    $message .= '<br />' . $auth_link . ' - ' . $deny_link;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $transient = 'request_route_' . $route_id . '_user_' . $user->ID;
    set_transient($transient, $token, WEEK_IN_SECONDS);
    wp_mail($to, $subject, $message, $headers);
    return new WP_REST_Response($request, 200);
  }
  else {
    if (empty($id)) {
      return new WP_REST_Response('Manca il riferimento alla mappa', 401);
    }
    else {
      if (empty($email)) {
        return new WP_REST_Response('Manca l\'email utente', 401);
      }
      else {
        if (empty($title)) {
          return new WP_REST_Response('La mappa non è disponibile', 401);
        }
      }
    }
  }


}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/mail', array(
      'methods' => 'POST',
      'callback' => 'webmapp_send_mail_request'
    )
  );
});

function webmapp_authorize_request(WP_REST_Request $request) {

  $user_id = $request->get_param("u");
  $route_id = $request->get_param("r");
  $app = $request->get_param("app");
  $token = $request->get_param("token");
  $user_id = base64_decode($user_id);
  $route_id = base64_decode($route_id);
  $transient = 'request_route_' . $route_id . '_user_' . $user_id;
  $check_token = get_transient( $transient );

  if ( $token !== $check_token){
    return new WP_REST_Response('La richiesta non &egrave; più valida', 401);
  }

  if ($user_id && $route_id) {
    $title = get_the_title($route_id);
    $users_id = array();
    $users = get_field('n7webmap_route_users', $route_id);
    foreach ($users as $user) {
      $users_id[] = strval($user['ID']);
    }

    array_push($users_id, $user_id);
    update_field('n7webmap_route_users', $users_id, $route_id);

    $user_info = get_userdata($user_id);
    $user_mail = $user_info->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $app_name = '';
    if ($app) {
      $app_name = base64_decode($app);
    }
    if (!empty($user_info->first_name) && !empty($user_info->last_name)) {
      $message = 'Caro ' . $user_info->first_name . ' ' . $user_info->last_name . ',<br />';
    }
    else {
      $message = 'Caro ' . $user_mail . ',<br />';
    }
    $message .= 'complimenti, puoi aggiornare l\'app scorrendo verso l\'alto e scaricare l\'itinerario richiesto. Ci aiuterai cosi nella sperimentazione dell\'app ' . $app_name;
    $subject = $app_name . ' - Sei stato autorizzato a scaricare l\'itinerario: ' . $title;
    wp_mail($user_mail, $subject, $message, $headers);
    delete_transient( $transient );
    $url = admin_url();
    wp_redirect($url);
    exit;
    return new WP_REST_Response('Utente autorizzato.', 200);
  }
  else {
    if (empty($users_id)) {
      return new WP_REST_Response('Manca il riferimento all\'utente', 401);
    }
    else {
      if ($route_id) {
        return new WP_REST_Response('L\'itinerario non è disponibile', 401);
      }
    }
  }

}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/authorize/', array(
      'methods' => 'GET',
      'callback' => 'webmapp_authorize_request'
    )
  );
});


function webmapp_deny_request(WP_REST_Request $request) {

  $user_id = $request->get_param("u");
  $route_id = $request->get_param("r");
  $app = $request->get_param("app");
  $token = $request->get_param("token");
  $user_id = base64_decode($user_id);
  $route_id = base64_decode($route_id);
  $transient = 'request_route_' . $route_id . '_user_' . $user_id;
  $check_token = get_transient( $transient );

  if ( $token !== $check_token){
    return new WP_REST_Response('La richiesta non &egrave; più valida', 401);
  }

  if ($user_id && $route_id) {

    $title = get_the_title($route_id);
    $app_name = '';
    if ($app) {
      $app_name = base64_decode($app);
    }
    $user_info = get_userdata($user_id);
    $user_mail = $user_info->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (!empty($user_info->first_name) && !empty($user_info->last_name)) {
      $message = 'Caro ' . $user_info->first_name . ' ' . $user_info->last_name . ',<br />';
    }
    else {
      $message = 'Caro ' . $user_mail .',<br />';
    }
    $message .= 'questo itinerario è riservato ai clienti ' . $app_name . ' che hanno acquistato questo viaggio. Terminata la fase di sperimentazione, gli itinerari saranno disponibili a tutti.';
    $subject = $app_name . ' - Non sei autorizzato a scaricare l\'itinerario: ' . $title;
    wp_mail($user_mail, $subject, $message, $headers);
    delete_transient( $transient );
    $url = admin_url();
    wp_redirect($url);
    exit;
    return new WP_REST_Response('Utente non autorizzato.', 200);
  }
  else {
    if (empty($users_id)) {
      return new WP_REST_Response('Manca il riferimento all\'utente', 401);
    }
    else {
      if ($route_id) {
        return new WP_REST_Response('L\'itinerario non è disponibile', 401);
      }
    }
  }


}

add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/deny', array(
      'methods' => 'GET',
      'callback' => 'webmapp_deny_request'
    )
  );
});