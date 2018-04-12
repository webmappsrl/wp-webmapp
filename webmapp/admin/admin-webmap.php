<?php
/**
 * Created by PhpStorm.
 * User: Chiara
 * Date: 15/12/2016
 * Time: 13.13
 */
add_action('admin_menu', 'webmap_menu');

/** Step 1. */
function webmap_menu() {
  add_menu_page('Webmapp Options', 'Webmapp', 'manage_options', 'webmap_netseven', 'webmap_options');
  //call register settings function
  add_action('admin_init', 'register_webmap_settings');
}

add_action('admin_menu', 'webmapp_sub_menu'); 
function webmapp_sub_menu() { 
    add_submenu_page('edit.php?post_type=poi', 'Import from kml', 'Import from kml', 'manage_options', 'import_poi_kml', 'import_poi_kml'); 
}

function register_webmap_settings() {
  //register our settings
  register_setting('net7-webmap-group', 'google_api_key');
  register_setting('net7-webmap-group', 'webmapp_bounding_box');
  register_setting('net7-webmap-group', 'webmapp_map_zoom');
  register_setting('net7-webmap-group', 'webmapp_map_tilesUrl');
  register_setting('net7-webmap-group', 'webmapp_map_appUrl');
  register_setting('net7-webmap-group', 'webmapp_map_modal');
  register_setting('net7-webmap-group', 'webmapp_map_show-pin');
  register_setting('net7-webmap-group', 'webmapp_map_show-expand');
  register_setting('net7-webmap-group', 'webmapp_map_click-iframe');
}

function webmap_options() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  ?>
  <h1><?php echo __('Webmap Options', 'webmap_net7'); ?></h1>
  <p>Webmapp is a plugin developed by <a href="http://webmapp.it" target="_blank">WEBMAPP s.r.l.</a></p>
  <form method="post" action="options.php">
  <?php
  settings_fields('net7-webmap-group');
  do_settings_sections('net7-webmap-group');
  ?>
    <p><a href="<?php echo plugin_dir_url(__FILE__); ?>../documentation/Piattaformaeditorialemuticanale.pdf" target="_blank"><?php echo __("Download documentazione", "webmap_net7"); ?></a> </p>
    <table class="form-table">      
      <tr valign="top">
        <th scope="row"><?php _e("Google Maps API Key", 'webmap_net7') ?><br>
          <em><a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key"><?php _e("Get a Google API Key") ?></a></em></th>
        <td><input type="text" size="50" name="google_api_key" value="<?php echo esc_attr(get_option('google_api_key')); ?>" /></td>

      </tr>
      <tr valign="top">
        <th scope="row"><?php _e("Bounding Box", 'webmap_net7') ?></th>
        <td><textarea  cols="50" rows="10" name="webmapp_bounding_box"><?php echo esc_attr(get_option('webmapp_bounding_box')); ?></textarea>
        </td>
      </tr>

        <tr valign="top">
            <th scope="row"><?php _e( "Zoom mappa", 'webmap_net7' ) ?></th>
            <td><input type="text" size="50" name="webmapp_map_zoom" value="<?php echo esc_attr( get_option( 'webmapp_map_zoom' ) ); ?>"/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e( "TilesUrl mappa", 'webmap_net7' ) ?></th>
            <td><input type="text" size="50" name="webmapp_map_tilesUrl" value="<?php echo esc_attr( get_option( 'webmapp_map_tilesUrl' ) ); ?>"/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e( "App url mappa", 'webmap_net7' ) ?></th>
            <td><input type="text" size="50" name="webmapp_map_appUrl" value="<?php echo esc_attr( get_option( 'webmapp_map_appUrl' ) ); ?>"/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e( "Apri in modale", 'webmap_net7' ) ?></th>
            <td><select name="webmapp_map_modal">
                    <option value="true" <?php if( get_option( 'webmapp_map_modal' ) == 'true' ) echo 'selected'; ?>>Sì</option>
                    <option value="false" <?php if( get_option( 'webmapp_map_modal' ) == 'false' ) echo 'selected'; ?>>No</option>
                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e( "Mostra Pin", 'webmap_net7' ) ?></th>
            <td><select name="webmapp_map_show-pin">
                    <option value="true" <?php if( get_option( 'webmapp_map_show-pin' ) == 'true' ) echo 'selected'; ?>>Sì</option>
                    <option value="false" <?php if( get_option( 'webmapp_map_show-pin' ) == 'false' ) echo 'selected'; ?>>No</option>
                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e( "Mostra Espandi", 'webmap_net7' ) ?></th>
            <td><select name="webmapp_map_show-expand">
                    <option value="true" <?php if( get_option( 'webmapp_map_show-expand' ) == 'true' ) echo 'selected'; ?>>Sì</option>
                    <option value="false" <?php if( get_option( 'webmapp_map_show-expand' ) == 'false' ) echo 'selected'; ?>>No</option>
                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e( "Click su Iframe", 'webmap_net7' ) ?></th>
            <td><select name="webmapp_map_click-iframe">
                    <option value="true" <?php if( get_option( 'webmapp_map_click-iframe' ) == 'true' ) echo 'selected'; ?>>Sì</option>
                    <option value="false" <?php if( get_option( 'webmapp_map_click-iframe' ) == 'false' ) echo 'selected'; ?>>No</option>
                </select>
            </td>
        </tr>
    </table>
  <?php submit_button(); ?>
  </form>
  <?php
}

function import_poi_kml() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  ?>
  <h1><?php echo __('Import Poi from KML', 'webmap_net7'); ?></h1>
  <div class='upload-kml'>
    <?php echo __("Upload a kml file", "webmap_net7")?><br>
    <input id='kml-upload-file' type='file' data-type="poi" name='async-upload'>
    <span class='hidden-loader loader'><img src='<?php echo plugins_url() ?>/webmapp/includes/images/spinner.gif'><?php echo __("Loading", "webmap_net7") ?></span>
  </div>
  <form method="post" id="create_obj_from_kml" action="options.php">
    <div id="preview-import">
      
    </div>
  <?php
  ?>
    
  <?php // submit_button(); ?>
  </form>
  
  <div id="webmap_category_dialog"  title="<?php echo __("Choose Webmapp categories", "webmap_net7"); ?>">
      <input type="hidden" value="" id="poi_el">
      <ul class="webmap_cat_checklist categorychecklist">  
          <?php wp_terms_checklist(0, array("taxonomy" => "webmapp_category")); ?>
      </ul>
    </div>

    <div id="webmap_osm_dialog" title="<?php echo __("Open map on Openstreetmap", "webmap_net7"); ?>">
      <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="" style="border: 1px solid black"></iframe>
     <br/><small><a href="" target="_blank">Visualizza mappa ingrandita</a></small>
    </div>
  <?php
}
