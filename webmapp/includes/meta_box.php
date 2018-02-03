<?php

/*ADD METABOX IN ACF FIELD GROUP */
require_once 'geojson_track.php';
require_once 'geojson_route_related_track.php';
add_action('acf/input/admin_head', 'my_acf_admin_head');

function my_acf_admin_head() {
  if ( is_admin() ){
  global $current_screen;
  ?>
    <div id="webmap_category_dialog"
         title="<?php echo __("Choose Webmapp categories", "webmap_net7"); ?>">
        <input type="hidden" value="" id="poi_el">
        <ul class="webmap_cat_checklist categorychecklist">
          <?php wp_terms_checklist(0, array("taxonomy" => "webmapp_category")); ?>
        </ul>
    </div>

    <div id="webmap_osm_dialog"
         title="<?php echo __("Open map on Openstreetmap", "webmap_net7"); ?>">
        <iframe width="425" height="350" frameborder="0" scrolling="no"
                marginheight="0" marginwidth="0" src=""
                style="border: 1px solid black"></iframe>
        <br/>
        <small><a href="" target="_blank">Visualizza mappa ingrandita</a>
        </small>
    </div>


    <script type="text/javascript">
        (function($) {

            $(document).ready(function() {

                $('.acf-field-parse-gpx .acf-input').append("<form id='webmapp-gpx-import' data-type='<?php echo $current_screen->post_type ?>'></form>");
                $('.acf-field-parse-gpx .acf-input #webmapp-gpx-import').append('<?php wp_nonce_field('image-submission'); ?>');
                $('.acf-field-parse-gpx .acf-input #webmapp-gpx-import').append("<div class='acf-label'><?php echo __("Upload a gpx file to import it's POIs. Use filename with no special character, only the .gpx extension.", "webmap_net7")?></div>");
                $('.acf-field-parse-gpx .acf-input #webmapp-gpx-import').append('<div><input id=\'gpx-upload-file\' type=\'file\' name=\'async-upload\'></div>');

                $('.acf-field-parse-gpx .acf-input').append("<span class='hidden-loader loader'><img src='<?php echo plugin_dir_url(__FILE__) ?>/images/spinner.gif'><?php echo __("Loading", "webmap_net7") ?></span>");
                $('.acf-field-parse-gpx .acf-input').append('<div class=\'acf-repeater\' id=\'track-import-preview\'></div>');
                $('#webmap_category_dialog').dialog(
                    {
                        dialogClass: 'webmapp_category_dialog',
                        draggable: true,
                        autoOpen: false,
                        closeText: 'close',
                        show: true
                    }
                );
            });
        })(jQuery);
    </script>
    <style type="text/css">
        .acf-field #wp-content-editor-tools {
            background: transparent;
            padding-top: 0;
        }
    </style>
  <?php
  }
}

function addingtrack_meta_boxes($post_type, $post) {
  if (get_post_meta($post->ID, "n7webmap_geojson", TRUE) != "" || get_post_meta($post->ID, 'n7webmap_route_related_track') != NULL) {
    add_meta_box(
      'webmapp-track-map',
      __('Track Map', "webmap_net7"),
      'render_map_leaflet',
      'track',
      'normal',
      'high'
    );

    add_meta_box(
      'webmapp-track-map',
      __('Related Track Map', "webmap_net7"),
      'render_map_route_leaflet',
      'route',
      'normal',
      'default'
    );
  }
}

add_action('add_meta_boxes', 'addingtrack_meta_boxes', 10, 2);

function render_map_leaflet($post) {

  ?>
    <div id='track-leaflet-map'></div>
    <script>
        setLeafletMap('track-leaflet-map', <?php echo json_encode(geojson_track($post->ID)) ?>);
    </script>
  <?php
//  echo "<div id='track-leaftlet-map'></div>";
}

function render_map_route_leaflet($post) {
  ?>
    <div id='track-leaflet-map'></div><br>
    <button id="set_bbox">Get bounding box</button> <br><br>
    <textarea id="webmapp_bbox" name="n7webmapp_route_bbox" cols="50" rows="20">
       <?php echo get_post_meta($post->ID, 'n7webmapp_route_bbox', TRUE); ?>
    </textarea>
    <script>
      <?php $related_track = geojson_route_related_track($post->ID);
      if (!empty($related_track)): ?>
      setLeafletMap('track-leaflet-map', <?php echo json_encode(geojson_route_related_track($post->ID)) ?>);
      <?php endif; ?>
    </script>
  <?php
//  echo "<div id='track-leaftlet-map'></div>";
}

function route_save_meta($post_id) {
  if (isset($_POST['n7webmapp_route_bbox'])) {
    update_post_meta($post_id, 'n7webmapp_route_bbox', $_POST['n7webmapp_route_bbox']);
  }
}

add_action('save_post_route', 'route_save_meta');
