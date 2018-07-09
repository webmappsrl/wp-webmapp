<?php

/**
 * Check user credentials and return user data
 *
 * @return object|null bounding box webmapp setting or null if none.
 */
function wm_api_purchase(WP_REST_Request $request) {
  $route_id = $request->get_param("route_id");
  $user_id = $request->get_param("user_id");
  $data = array('route_id'=>$route_id,'user_id'=>$user_id);

  // Preparo $resp
  $resp['data']=$data;
  $resp['error'] = '';
  $resp['message'] = '';

  $user = get_userdata($user_id);

  // Verifica esistenza user_id -> 401 No user
  if($user===false) {
    $resp['error'] = 'No user';
    return new WP_REST_Response($resp,401);
  }

  // Verifica esistenza route -> 401 No route
  $route = get_post($route_id,ARRAY_A);
  if($route===null || $route['post_type']!='route') {
    $resp['error'] = 'No route';
    return new WP_REST_Response($resp,401);
  }


    $users = get_field('n7webmap_route_users', $route_id);
    $users_id=array();
    if (count($users)>0) {
    foreach ($users as $user) {
      $users_id[] = strval($user['ID']);
    }
    }
    if(!in_array($user_id, $users_id))  array_push($users_id, $user_id);
    update_field('n7webmap_route_users', $users_id, $route_id);


  $resp['message'] = 'OK';
  return new WP_REST_Response($resp,200);

}


add_action('rest_api_init', function () {
  register_rest_route('webmapp/v1', '/purchase', array(
      'methods' => 'POST',
      'callback' => 'wm_api_purchase'
    )
  );
});

