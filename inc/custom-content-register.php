<?php
// add here my functions
//Registering Custom Post Type Themes
add_action( 'init', 'register_escc', 20 );
function register_escc() {
    $labels = array(
        'name' => _x( 'Custom Contents', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'singular_name' => _x( 'Custom Content', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'add_new' => _x( 'Add New', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'add_new_item' => _x( 'Add New Custom Content', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'edit_item' => _x( 'Edit Custom Content', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'new_item' => _x( 'New Custom Content', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'view_item' => _x( 'View Custom Content', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'search_items' => _x( 'Search Custom Contents', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'not_found' => _x( 'No Custom Contents found', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'not_found_in_trash' => _x( 'No Custom Contents found in Trash', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'parent_item_colon' => _x( 'Parent Custom Content:', 'escc_custom_post', 'escc_easysoftonic_company' ),
        'menu_name' => _x( 'Custom Contents', 'escc_custom_post', 'escc_easysoftonic_company' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Custom Contents Posts',
        'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
        //'taxonomies' => array( 'escc_category'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => plugins_url('assets/images/menuicon.png', dirname(__FILE__) ),
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array('slug' => 'custom-content','with_front' => FALSE), // you can rewrite url of Custom Content post
        'public' => true,
        'has_archive' => 'custom-content',
        'capability_type' => 'post'
    );  
    register_post_type( 'custom-content', $args );//max 20 charachter cannot contain capital letters and spaces
}  
// register end CPT

// register column and display shortcode their
add_filter( 'manage_custom-content_posts_columns', 'escc_revealid_add_id_column', 5 );
add_action( 'manage_custom-content_posts_custom_column', 'escc_revealid_id_column_content', 5, 2 );

function escc_revealid_add_id_column( $columns ) {
   $columns['post_id'] = 'Shortcode';
   return $columns;
}

function escc_revealid_id_column_content( $column, $id ) {
   if( 'post_id' == $column ) {
   echo '<code> [es_custom_content id="'.$id.'"]</code>';
 }
}

/**
 * Register all shortcodes
 *
 * @return null
 */
function register_shortcodes_escc() {
    add_shortcode( 'es_custom_content', 'escc_display_contents' );   
}
add_action( 'init', 'register_shortcodes_escc' );

// function display custom contents
function escc_display_contents( $atts ) {
    ob_start();
    
    global $wp_query;
    $original_query = $wp_query;

    $atts = shortcode_atts( array(
        'id' => ''
    ), $atts );

    $loop = new WP_Query(array(
        'post_type' => array(
            'custom-content',
            // more to come
        ),
        'post_status' => 'publish',
        'post__in' => array($atts['id'])
    ) );
 $wp_query = $loop;
    if( ! $loop->have_posts() ) {
        return false;
    }
echo '<div class="main_escc">';
    if($loop->have_posts()) {
        while($loop->have_posts()) {
            $loop->the_post(); 
if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {			 
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
     
 } }   
echo '</div>'; 
    wp_reset_query();
    return ob_get_clean();
}