<?php
/*
Plugin Name: Custom Content
Plugin URI: http://www.easysoftonic.com/
Description: Custom Content plugin Extend the Visual Composer with ES Modules (ES Custom Content) display custom contents using shortcode, widgets and VC module.
Version: 1.1
Author: Easy Softonic
Author URI: http://www.easysoftonic.com
License: GPLv2 or later
*/

/*
This ES Modules plugin can be used to speed up Visual Composer plugins creation process.
*/
if (!defined('ABSPATH')) die('-1');
define( 'ESCC_PLUGIN_PATH', plugin_dir_path(__FILE__) );
// Require the main plugin class
include_once( ESCC_PLUGIN_PATH . 'inc/custom-content-register.php');
include_once( ESCC_PLUGIN_PATH . 'inc/custom-content-widget.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
include_once( ESCC_PLUGIN_PATH . 'inc/custom-content-vc-module.php');	
} 

function escc_frontend_style()
{
    // Register the style like this for a plugin:
    wp_register_style( 'esccs-customcontent-style', plugins_url( 'assets/css/styles.css', (__FILE__) ), array(), '20200802', 'all' );
    // or
    // Register the style like this for a theme:
    //wp_register_style( 'custom-style', get_template_directory_uri() . '/css/custom-style.css', array(), '20120208', 'all' );
 
    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'esccs-customcontent-style' );
}
add_action( 'wp_enqueue_scripts', 'escc_frontend_style' );

// Enable the use of shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

//---------admin_menu-----------------------
add_action('admin_menu', 'escc_plugin_menu');

function escc_plugin_menu() {
add_submenu_page(
        'edit.php?post_type=custom-content',
        __( 'Settings Page', 'escc_easysoftonic_company' ),
        __( 'Settings', 'escc_easysoftonic_company' ),
        'manage_options',
        'escc_plugin_settings_page',
        'escc_page_callback'
    );	
}
function escc_page_callback() {
	?>
<div class="wrap">
<h2><?php _e('Custom Content Setting Page'); ?></h2>
<div class="notice">
<p>Custom Content display the custom contents into a page by useing shortcode <code>[es_custom_content id="2269"]</code> or by using VC Module or by using Wordpress Widget.</p>
<p>If you want disable or enable you can do this by clicking down options.</p>
<script type="text/javascript">
    jQuery(document).ready(function($) {
 
      jQuery('form#escc_form').submit(function() {
          var data = jQuery(this).serialize();
           
          //alert(data);
          
          jQuery.post(ajaxurl, data, function(response) {
              if(response == 1) {
                  show_message(1);
                  t = setTimeout('fade_message()', 2000);
              } else {
                  show_message(2);
                  t = setTimeout('fade_message()', 2000);
              }
          });
          return false;
      });
 
    });
 
    function show_message(n) {
        if(n == 1) {
            jQuery('#saved').html('<div id="message" class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div>').show();
        } else {
            jQuery('#saved').html('<div id="message" class="error fade"><p><strong><?php _e('Options could not be saved.'); ?></strong></p></div>').show();
        }
    }
 
    function fade_message() {
        jQuery('#saved').fadeOut(1000);
        clearTimeout(t);
    }
    </script>
   
    
    <?php 
	$content_type1 = get_option('content_type1'); 
   $content_type2 = get_option('content_type2'); 
    ?>
    <h2>Enable / Disable Options</h2>
    <form action="/" name="escc_form" id="escc_form">
        <!-- <input type="text" name="test_text" value="<?php //echo $options['test_text']; ?>" /><br /> -->
        <input type="checkbox" name="content_type1"  value="1" <?php if ($content_type1 == 1) { echo 'checked'; } else {  echo '';  } ?> /> VC Module<br />
        <input type="checkbox" name="content_type2"  value="1" <?php if ($content_type2 == 1) { echo 'checked'; } else {  echo '';  } ?> /> Wordpress Widget<br />
        
        <br /><br />
        <input type="hidden" name="action" value="escc_data_save" />
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('es-cc-data'); ?>" />
        <div id="saved"></div> 
        <input type="submit" value="Save Changes" class="button button-primary" /> <br /><br />
    </form>
    </div>
</div>

<?php } 

 
add_action('wp_ajax_escc_data_save', 'escc_save_ajax');
function escc_save_ajax() {
//checkbox sanitization function
        function escc_content_sanitize_checkbox( $input ){
              
            //returns true if checkbox is checked
            return ( isset( $input ) ? true : false );
        }

	if ( ! wp_verify_nonce( $_POST['security'], 'es-cc-data' ) ) {

     die( 'Security check' ); 

} else {

     // Do stuff here.

if ( isset( $_POST['content_type1'] )){

    $data = escc_content_sanitize_checkbox( $_POST['content_type1'] );
update_option( 'content_type1', $data ); 
     

}else{
	update_option( 'content_type1', 0 ); 
	
	}
   
 if ( isset( $_POST['content_type2'] )){

    $data = escc_content_sanitize_checkbox( $_POST['content_type2'] );
update_option( 'content_type2', $data ); 
     

}else{
	update_option( 'content_type2', 0 ); 
	
	} 
	} // end else security
    die('1');
}