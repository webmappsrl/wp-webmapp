<?php
/*
  Plugin Name: Webmapp
  Plugin URI: http://download.be.webmapp.it
  Description: Worpdress plugin to handle Maps
  Author: Netseven
  Version: 0.0.4
  Author URI: http://www.netseven.it
 */

include_once "admin/admin-webmap.php";
include_once "includes/custom_fields.php";
include_once "includes/meta_box.php";
include_once "includes/rest_api.php";
include_once "includes/custom_map.php";
include_once "includes/admin_columns.php";
include_once "third-part/gisconverter/gisconverter.php";

include_once "includes/custom_types/custom_types.php";

add_action('init', 'webmap_load_textdomain');

function webmap_load_textdomain() {
  load_plugin_textdomain('webmap_net7', FALSE, dirname(plugin_basename(__FILE__)) . '/lang');
  wp_enqueue_style("webmap_font_awesome", plugin_dir_url(__FILE__) . 'third-part/font-awesome-4.7.0/css/font-awesome.min.css');
  wp_enqueue_style("webmap_style_net7", plugin_dir_url(__FILE__) . 'includes/css/style.css');
  wp_enqueue_style("webmap_leaflet", plugin_dir_url(__FILE__) . 'third-part/leaflet/leaflet.css');
  wp_enqueue_script("webmap_leaflet_js", plugin_dir_url(__FILE__) . 'third-part/leaflet/leaflet.js');
}



function webmapp_load_gpx_script() {

  wp_enqueue_script('webmapp-import-gpx', plugin_dir_url(__FILE__) . 'includes/js/import-gpx.js', array('jquery'), '0.1.0', TRUE);
  wp_enqueue_script('webmapp-leaflet-map', plugin_dir_url(__FILE__) . 'includes/js/leaflet-map.js');
  wp_enqueue_script("jquery-ui-dialog");
  $data = array(
    'upload_url' => admin_url('async-upload.php'),
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('media-form'),
    'loading_gif_path' => plugin_dir_url(__FILE__) . "includes/images/spinner.gif",
    'loading_text' => __("Loading", "webmap_net7"),
    'import_completed' => __("Import completed succesfully.", "webmap_net7"),
    'track_created_success' => __("Track imported succesfully.", "webmap_net7"),
    'track_created_subtitle' => __("You will see new imported tracks in the route's related tracks after route update", "webmap_net7"),
    'track_created_view' => __("view the track", "webmap_net7")
  );

  wp_localize_script('webmapp-import-gpx', 'webmapp_config', $data);
}

add_action('init', 'webmapp_load_gpx_script');

function webmap_acf_init() {
  if (get_option('google_api_key') && get_option('google_api_key') != "") {
    acf_update_setting('google_api_key', get_option('google_api_key'));
  }
}

add_action('acf/init', 'webmap_acf_init');

function insert_taxonomy_values() {
  /* $parent_term = term_exists( 'fruits', 'product' ); // array is returned if taxonomy is given
    $parent_term_id = $parent_term['term_id']; // get numeric term id
    wp_insert_term(
    'Apple', // the term
    'product', // the taxonomy
    array(
    'description'=> 'A yummy apple.',
    'slug' => 'apple',
    'parent'=> $parent_term_id
    )
    ); */
}

function webmapp_file_upload() {

  $data = isset($_FILES) ? $_FILES : array();

  $temp_name = $data["webmapp_file_upload"]["tmp_name"];

  $fileName = $data["webmapp_file_upload"]["name"];
  $fileNameChanged = str_replace(" ", "_", $fileName);

  $filepath = plugin_dir_path(__FILE__) . "uploads";
  $file = $filepath . "/" . $fileNameChanged;

  $response = array();
  $file_mvd = move_uploaded_file($temp_name, $file);

  if ($file_mvd) {
    $file_type = explode(".", $fileName);

    $ftype = end($file_type);

    if ($ftype == "gpx") {
      $response = webmapp_parse_gpx($file, $response);
    }
    elseif ($fype == "kml") {
      $response = webmapp_parse_kml($file, $response);
    }
    else {
      $response["error"] = "Unknown file type - ".$ftype;
    }
    $response["file_type"] = $ftype;

  }

  echo json_encode($response);

  die();
}

add_action('wp_ajax_webmapp_file_upload', 'webmapp_file_upload');


function webmapp_kml_upload() {

  $data = isset($_FILES) ? $_FILES : array();

  $temp_name = $data["webmapp_file_upload"]["tmp_name"];

  $fileName = $data["webmapp_file_upload"]["name"];
  $fileNameChanged = str_replace(" ", "_", $fileName);

  $filepath = plugin_dir_path(__FILE__) . "uploads";
  $file = $filepath . "/" . $fileNameChanged;

  $response = array();
  $file_mvd = move_uploaded_file($temp_name, $file);

  if ($file_mvd) {
    $response = webmapp_parse_kml($file, $response);


  }

  echo json_encode($response);

  die();
}

add_action('wp_ajax_webmapp_kml_upload', 'webmapp_kml_upload');
add_action('wp_ajax_webmapp_kml_upload', 'webmapp_kml_upload');


function webmapp_parse_gpx($file, $response) {

  $gpx = simplexml_load_file($file);
  if ($gpx === FALSE) {
    $response["error"] = __("Failed loading GPX: ", "webmap_net7");
    foreach (libxml_get_errors() as $error) {
      $response["error"] .= "<br>" . $error->message;
    }
  }
  else {
    if ($gpx->trk->name) {
      $response["label"] = __("Creating Track with name: ", "webmap_net7");
      $response["title"] = (string) $gpx->trk->name;
    }
    else {
      $response["label"] = __("No name given for this track", "webmap_net7");
      $response["title"] = "undefined";
    }
    $response["poi"] = array();

    foreach ($gpx->wpt as $wpt) {
      //        var_dump($wpt); die;
      $response["poi"][] = array(
        name => (string) $wpt->name,
        desc => (string) $wpt->desc,
        lat => (string) $wpt["lat"],
        lon => (string) $wpt["lon"],
      );
    }
    unset($gpx->wpt);
    $gpx->asXML($file);
    $response["gpx_file"] = $file;
  }

  return $response;
}

function webmapp_parse_kml($file, $response) {

  $kml = simplexml_load_file($file);
  if ($kml === FALSE) {
    $response["error"] = __("Failed loading KML: ", "webmap_net7");
    foreach (libxml_get_errors() as $error) {
      $response["error"] .= "<br>" . $error->message;
    }
  }
  else {


    foreach ($kml->Document->Placemark as $plm) {
      if (count($plm->Point) > 0) {
        $point = explode(",", $plm->Point->coordinates);
        $response["poi"][] = array(
          name => (string) $plm->name,
          desc => (string) $plm->description,
          lat => (string) $point[1],
          lon => (string) $point[0]
        );
        unset($plm);
      }
      elseif (count($plm->LineString) > 0) {
        $response["label"] = __("Creating Track with name: ", "webmap_net7");
        $response["title"] = (string) $plm->name;
        $plm->asXML($file);
      }
      else {
        unset($plm);
      }
    }
    $response["gpx_file"] = $file;
  }
  return $response;
}

function webmapp_create_track() {

  $title = $_POST["track_name"] != "" ? $_POST["track_name"] : "undefined";


  $poi = $_POST["poi"];
  $file = $_POST["gpx_file"];
  $post_type = $_POST["content_type"];
  $post_id = 0;
  $file_type = $_POST["file_type"];

  if ($post_type == "track") {
    $post_id = $_POST["post_id"];
  }

  $post = array(
    'ID' => $post_id,
    'post_type' => "track",
    'post_name' => sanitize_title($title), // The name (slug) for your post
    'post_title' => $title, // The title of your post.
    'post_status' => 'publish'
  );

  if ($post_id == 0) {
    $post_id = wp_insert_post($post, TRUE);
  }
  else {
    $post_id = wp_update_post($post, TRUE);
  }

  if (file_exists($file)) {
    $content = file_get_contents($file);
    try {
      if ($file_type == "gpx") {
        $geojson = gpx_to_geojson((string) $content);
      }
      elseif ($file_type == "kml") {
        $geojson = kml_to_geojson((string) $content);

      }
    } catch (Exception $ex) {
      $response["json_error"] = $ex->getMessage();
    }

    if ($geojson != NULL && $geojson != "") {
      $obj = json_decode($geojson, TRUE);
      update_post_meta($post_id, "n7webmap_geojson", serialize($obj));
      unlink($file);
    }

  }


//  $wp_upload_dir = wp_upload_dir();
//  $gpx_file_name = $wp_upload_dir['path'] . "/" . "track_" . $post_id . ".gpx";
//
//  $file_mvd = rename($file, $gpx_file_name);
//
//  if ($file_mvd) {
//    $gpx_id = create_attachment($gpx_file_name);
//    update_field("n7webmap_gpx", $gpx_id, $post_id);
//  }


  if ($post_type == "route") {
    $route_id = $_POST["post_id"];
    $route_related = get_field("n7webmap_route_related_track", $route_id);
    $route_related[] = get_post($post_id);
    update_field("n7webmap_route_related_track", $route_related, $route_id);
  }

  $poi_ids = get_field('n7webmap_related_poi', $post_id);
  if (empty($poi_ids)) {
    $poi_ids = array();
  }

  foreach ($poi as $p) {
    if ($p != "") {
      $post = array(
        'ID' => 0,
        'post_type' => "poi",
        'post_name' => sanitize_title($p['name']),
        // The name (slug) for your post
        'post_title' => $p['name'],
        // The title of your post.
        'post_content' => $p['desc'],
        // The title of your post.
        'post_status' => 'publish'
      );
      if (isset($p['cats']) && $p['cats'] != "") {
        $post['tax_input'] = array("webmapp_category" => explode(",", $p['cats']));
      }
      $poi_id = wp_insert_post($post, TRUE);


      if ($post_id > 0) {
        $poi_ids[] = $poi_id;
        $value = array("address" => "", "lat" => $p["lat"], "lng" => $p["lon"]);
        update_field('n7webmap_coord', $value, $poi_id);
      }
    }
  }

  update_field('n7webmap_related_poi', $poi_ids, $post_id);

  $response = array();
  $response["url"] = get_edit_post_link($post_id, "");
  $response["track_name"] = (string) $title;
  $response["track_id"] = $post_id;
//      echo get_edit_post_link($post_id);
  $response["redirect"] = "";
  if ($post_type == "track") {
    $response["redirect"] = $response["url"];
  }

  echo json_encode($response);
  die;
}

add_action('wp_ajax_webmapp_create_track', 'webmapp_create_track');


function webmapp_import_create_poi() {

  $poi = $_POST["objects"];
  $response = array();
  foreach ($poi as $p) {
    if ($p != "") {
      $post = array(
        'ID' => 0,
        'post_type' => "poi",
        'post_name' => sanitize_title($p['name']),
        // The name (slug) for your post
        'post_title' => $p['name'],
        // The title of your post.
        'post_content' => $p['desc'],
        // The title of your post.
        'post_status' => 'publish'
      );
      if (isset($p['cats']) && $p['cats'] != "") {
        $post['tax_input'] = array("webmapp_category" => explode(",", $p['cats']));
      }
      $poi_id = wp_insert_post($post, TRUE);

      if ($poi_id > 0) {
        $value = array("address" => "", "lat" => $p["lat"], "lng" => $p["lon"]);
        update_field('n7webmap_coord', $value, $poi_id);
        $response[$p['name']] = get_edit_post_link($poi_id, "");
      }
    }
  }
  echo json_encode($response);
  die;
}

add_action('wp_ajax_webmapp_import_create_poi', 'webmapp_import_create_poi');
add_action('wp_ajax_webmapp_import_create_poi', 'webmapp_import_create_poi');

function create_attachment($filename) {
  $filetype = wp_check_filetype(basename($filename), NULL);
  // Get the path to the upload directory.
  $wp_upload_dir = wp_upload_dir();
  $attachment = array(
    'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
    'post_mime_type' => $filetype['type'],
    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
    'post_content' => '',
    'post_status' => 'inherit'
  );

  // Insert the attachment.
  return wp_insert_attachment($attachment, $filename);
}

function gpx_to_geojson($text) {
  $decoder = new gisconverter\GPX();
  return $decoder->geomFromText($text)->toGeoJSON();
}

function kml_to_geojson($text) {
  $decoder = new gisconverter\KML();
  return $decoder->geomFromText($text)->toGeoJSON();
}


// BULK EDIT - QUICK EDIT

add_filter('manage_posts_columns', 'webmapp_manage_posts_columns', 10, 2);
function webmapp_manage_posts_columns($columns, $post_type) {
  switch ($post_type) {

    case 'poi':

      // building a new array of column data
      $new_columns = array();

      foreach ($columns as $key => $value) {

        // default-ly add every original column
        $new_columns[$key] = $value;

        /**
         * If currently adding the title column,
         * follow immediately with our custom columns.
         */
        if ($key == 'title') {
          $new_columns['noDetails'] = 'No Details';
          $new_columns['noInteractions'] = 'No Interaction';
        }

      }

      return $new_columns;
  }


  return $columns;

}

add_action('manage_posts_custom_column', 'webmapp_manage_posts_custom_column', 10, 2);
function webmapp_manage_posts_custom_column($column_name, $post_id) {

  switch ($column_name) {

    case 'noDetails': ?>

        <input type="hidden" id="noDetails-<?php echo $post_id; ?>"
               name="noDetails"
               value="<?php if (get_field('noDetails', $post_id) != 1) {
                 echo 0;
               } ?>">
      <?php if (get_field('noDetails', $post_id) == 1) {
        echo 'ON';
      }
      else {
        echo 'OFF';
      } ?>
      <?php
      break;

	  case 'noInteractions': ?>

          <input type="hidden" id="noInteractions-<?php echo $post_id; ?>"
                 name="noInteractions"
                 value="<?php if (get_field('noInteraction', $post_id) != 1) {
			         echo 0;
		         } ?>">
		  <?php if (get_field('noInteraction', $post_id) == 1) {
			  echo 'ON';
		  }
		  else {
			  echo 'OFF';
		  } ?>
		  <?php
		  break;

  }

}

add_action('quick_edit_custom_box', 'webmapp_manage_bulk_edit_custom_box', 10, 2);
add_action('bulk_edit_custom_box', 'webmapp_manage_bulk_edit_custom_box', 10, 2);

function webmapp_manage_bulk_edit_custom_box($column_name, $post_type) {

  switch ($post_type) {

    case 'poi':

      switch ($column_name) {

        case 'noDetails':

          ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <div class="inline-edit-group wp-clearfix">
                        <label class="inline-edit-status alignleft">
                            <span class="title">No Details</span>
                            <select id="noDetails" name="noDetails">
                                <option value="-1">— No Change —</option>
                                <option value="1">ON</option>
                                <option value="0">OFF</option>
                            </select>
                            </span>
                        </label>
                    </div>
                </div>
            </fieldset>
          <?php
          break;


	      case 'noInteractions':

		      ?>
              <fieldset class="inline-edit-col-right">
                  <div class="inline-edit-col">
                      <div class="inline-edit-group wp-clearfix">
                          <label class="inline-edit-status alignleft">
                              <span class="title">No Interactions</span>
                              <select id="noInteraction" name="noInteraction">
                                  <option value="-1">— No Change —</option>
                                  <option value="1">ON</option>
                                  <option value="0">OFF</option>
                              </select>
                              </span>
                          </label>
                      </div>
                  </div>
              </fieldset>
		      <?php
		      break;


      }
  }

}

add_action('admin_print_scripts-edit.php', 'webmapp_manage_qe_admin_scripts');
function webmapp_manage_qe_admin_scripts() {

  // if using code as plugin
  wp_enqueue_script('webmapp-manage-bulk-quick-edit', trailingslashit(plugin_dir_url(__FILE__)) . 'includes/js/bulk_quick_edit.js', array(
    'jquery',
    'inline-edit-post'
  ), '', TRUE);

}

add_action('save_post', 'webmapp_manage_qe_save_post', 10, 2);
function webmapp_manage_qe_save_post($post_id, $post) {

  // pointless if $_POST is empty (this happens on bulk edit)
  if (empty($_POST)) {
    return $post_id;
  }

  // verify quick edit nonce
  if (isset($_POST['_inline_edit']) && !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
    return $post_id;
  }

  // don't save for autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  // dont save for revisions
  if (isset($post->post_type) && $post->post_type == 'revision') {
    return $post_id;
  }

  switch ($post->post_type) {

    case 'poi':

      /**
       * Because this action is run in several places, checking for the array key
       * keeps WordPress from editing data that wasn't in the form, i.e. if you had
       * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
       */
      $custom_fields = array('noDetails', 'noInteraction');

      foreach ($custom_fields as $field) {

        if (array_key_exists($field, $_POST) && $_POST[$field] != '-1') {
          update_post_meta($post_id, $field, $_POST[$field]);
        }
      }

      break;

  }

}

add_action('wp_ajax_webmapp_manage_bulk_quick_save_bulk_edit', 'webmapp_manage_bulk_quick_save_bulk_edit');
function webmapp_manage_bulk_quick_save_bulk_edit() {
  // we need the post IDs
  $post_ids = (isset($_POST['post_ids']) && !empty($_POST['post_ids'])) ? $_POST['post_ids'] : NULL;

  // if we have post IDs
  if (!empty($post_ids) && is_array($post_ids)) {

    // get the custom fields
    $custom_fields = array('noDetails', 'noInteraction');

    foreach ($custom_fields as $field) {

      // if it has a value, doesn't update if empty on bulk
      if (isset($_POST[$field]) && $_POST[$field] != '-1') {
        // update for each post ID
        foreach ($post_ids as $post_id) {
          update_post_meta($post_id, $field, $_POST[$field]);
        }

      }

    }

  }

}

// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
  global $post;
  return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

/* actions fired when listing/adding/editing posts or pages */
/* admin_head-(hookname) */
add_action('admin_head-post.php', 'admin_head_route_editing');;

function admin_head_route_editing() {

  if ($_GET['add_user']) {
    wp_enqueue_script('webmapp-add_user_to_route', trailingslashit(plugin_dir_url(__FILE__)) . 'includes/js/add_user_to_route.js', array(
      'jquery'
    ), '', TRUE);


  }
}

add_action( 'wp_ajax_nopriv_add_user_to_route', 'add_user_to_route' );
add_action( 'wp_ajax_add_user_to_route', 'add_user_to_route' );

function add_user_to_route() {

  $username = get_userdata($_REQUEST['user_id']);

  echo json_encode($username->data);

  die();

}

function webmapp_load_icons() {
	wp_register_style( 'webmapp-icons', 'https://icon.webmapp.it/style.css' );
	wp_enqueue_style( 'webmapp-icons' );
}

add_action( 'wp_enqueue_scripts', 'webmapp_load_icons' );
add_action( 'admin_enqueue_scripts', 'webmapp_load_icons' );