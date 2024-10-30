<?php
$content_type2 = get_option('content_type2'); 
if($content_type2 == 1) {
 // Creating the widget 
    class ESCC_CustomContentWidget extends WP_Widget {

        function __construct() {
        parent::__construct(
        // Base ID of your widget
        'ESCC_CustomContentWidget', 

        // Widget name will appear in UI
        __('Custom Content Widget', 'escc_easysoftonic_company'), 

        // Widget description
        array( 'description' => __( 'This plugin display Custom Content.', 'escc_easysoftonic_company' ), ) 
        );
        }

        // Creating widget front-end
        // This is where the action happens
        public function widget( $args, $instance ) {

            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }

            $escontentpost = new WP_Query( apply_filters( 'widget_posts_args', array(
            'post_type' => 'custom-content',
			'post__in'   => array($instance['showescontents']),
			) ) );
//echo '<pre>'. print_r($instance['showescontents']). '</pre>'; 

            if ($escontentpost->have_posts()) :
            ?>
              <div class="nav-box"> <h1 class="widget-title"><?php echo $instance['title'];?></h1></div><!-- nav-box -->

                <?php  while ( $escontentpost->have_posts() ) : $escontentpost->the_post(); ?>
                 
                    <div class="custom-content-widget"><!-- comm-most -->
                    
 <?php 
if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
     //your code here
$shortcodeContent = get_the_content();
$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( $shortcodeContent );
if ( ! empty( $shortcodes_custom_css ) ) {
    $shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
    $output = '';
	$output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
    $output .= $shortcodes_custom_css;
    $output .= '</style>';
    echo $output;
}
echo apply_filters( 'the_content', $shortcodeContent );
} else {
get_the_content() ? the_content() : the_ID();
}
 ?>
 </div><!-- comm-most -->

                <?php endwhile; ?>

                <?php
                // Reset the global $the_post as this query will have stomped on it
                wp_reset_postdata();

            endif;
        }
		
        // Widget Backend 
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Custom Content', 'escc_easysoftonic_company' );
            }

           $oldpostid =  isset($instance['showescontents']) ? $instance['showescontents'] : '';
		   //echo $oldpostid;
            // Widget admin form
    ?>
          <?php
		  $argcptwidgets = get_posts('post_type=custom-content&numberposts=-1&orderby=name');
                //echo '<pre>'. print_r($argcptwidgets). '</pre>';
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
            <br>

            <label for="<?php echo $this->get_field_id( 'showescontents' ); ?>"><?php _e( 'Select Custom Contents:' ); ?></label> 
            <select id="<?php echo $this->get_field_id('showescontents'); ?>" name="<?php echo $this->get_field_name('showescontents'); ?>" class="widefat" >
                <?php foreach ( $argcptwidgets as $argcptwidget ) {?>
                        <option <?php selected( $oldpostid,  $argcptwidget->post_title );?> value="<?php echo $argcptwidget->ID ; ?>" <?php if($oldpostid == $argcptwidget->ID){echo "selected";}?>><?php echo $argcptwidget->post_title ; ?> </option>
                <?php } ?>
            </select>

            </p>
        <?php 
        }

        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['showescontents'] = $new_instance['showescontents'];
            return $instance;
        }
    } // Class ESCC_CustomContentWidget ends here
  
    // Register and load the widget
    function escc_load_widget_custom_content() {
        register_widget( 'ESCC_CustomContentWidget' );
    }

    add_action( 'widgets_init', 'escc_load_widget_custom_content' );
	}