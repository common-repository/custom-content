<?php
if (!defined('ABSPATH')) die('-1');
$content_type1 = get_option('content_type1'); 
if($content_type1 == 1) {
class ESCC_CustomContent {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'escc_integrateWithVC' ) );

        // Use this when creating a shortcode addon
        add_shortcode( 'es_custom_content_module_vc', array( $this, 'render_es_custom_content_module_vc' ) );
    }

    public function escc_integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'escc_showVcVersionNotice' ));
            return;
        }
function generate_post_select($select_id, $post_type, $selected = 0) {
        $post_type_object = get_post_type_object($post_type);
        $label = $post_type_object->label;
        $posts = get_posts(array('post_type'=> $post_type, 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page'=>-1));
        echo '<select name="'. $select_id .'" id="'.$select_id.'">';
        echo '<option value = "" >All '.$label.' </option>';
        foreach ($posts as $post) {
            echo '<option value="', $post->ID, '"', $selected == $post->ID ? ' selected="selected"' : '', '>', $post->post_title, '</option>';
        }
        echo '</select>';
    }
        /*
        Add your Visual Composer logic here.
        */
	$argcpt = get_posts('post_type=custom-content&numberposts=-1');
        $cptNames = [];
        $cptNames[] = ['label' => __('Select Custom Content', 'escc_easysoftonic_company'), 'value' => 0];
        if ($argcpt) {
            foreach ($argcpt as $ccdata) {
                $cptNames[] = [
                    'label' => $ccdata->post_title . '(' . $ccdata->ID . ')',
                    'value' => $ccdata->ID,
                ];
            }
        } else {
            $cptNames = [
                ['label' => __('No Value found', 'escc_easysoftonic_company'), 'value' => 0],
            ];
        }
        vc_map( array(
            "name" => __("ES: Custom Content", 'escc_easysoftonic_company'),
            "description" => __("", 'escc_easysoftonic_company'),
            "base" => "es_custom_content_module_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/images/module-icon.png', dirname(__FILE__) ) , // or css class name which you can reffer in your css file later. Example: "escc_easysoftonic_company_my_class"
            "category" => __('ES Modules', 'escc_easysoftonic_company'),
			// start making fields
            "params" => array(
               array( 'param_name' => 'escctype', 'type' => 'dropdown', 'heading' => 'Select Custom Content)', 'escc_easysoftonic_company', 'preview' => true,
					"value"         => $cptNames,
					'admin_label' => true,
                    'std'         => 'escctype',
					'save_always' => true,
				),
				
				array( 'param_name' => 'esextraclass', 'type' => 'textfield', 'heading' => 'Extra class name', 'escc_easysoftonic_company', 'save_always' => true )
				
				
            )
        ) );
		
    }

    /*
    Shortcode logic how it should be rendered
    */
    public function render_es_custom_content_module_vc( $atts, $content = null ) {
      extract( shortcode_atts( array(
            'escctype'        		=> '',
			'esextraclass'        => ''		
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
$pb_id = rand(1000, 100000);
$post   = get_post( $escctype );
$postset = $post->post_content;
$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( $postset );
if ( ! empty( $shortcodes_custom_css ) ) {
    $shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
    $output = '';
	$output .= '<style type="text/css" data-type="vc_shortcodes-custom-css-'.$pb_id.'">';
    $output .= $shortcodes_custom_css;
    $output .= '</style>';
    echo $output;
}
   $output = '';
   $output .= '<div id="content-'.$pb_id.'" class="main_escc '.$esextraclass.'">';
   $output .= apply_filters( 'the_content', $postset );
   $output .= '</div>';
	  $output .= '<div class="vc_row-full-width vc_clearfix"></div>';
      return $output;
    wp_reset_query();
    return ob_get_clean();
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function escc_showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'escc_easysoftonic_company'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new ESCC_CustomContent();
}