<?php

/**** SHORTCODE ****/ 

add_shortcode( 'showmodule', 'showmodule_shortcode' );

// shortcode to show the module
function showmodule_shortcode($moduleid) {
    extract(shortcode_atts(array('id' =>'*'),$moduleid));   
    return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');
}


/**** ACTIONS ****/

add_action( 'template_redirect', 'blogger_template_redirect' );
add_action( 'init', 'register_my_menus' );
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );
add_action( 'wp_enqueue_scripts', 'mytheme_custom_scripts' );
// add_action( 'wp_footer', 'mycustom_wp_footer' );

add_action('woocommerce_add_to_cart', 'custom_add_to_cart');
function custom_add_to_cart() {
    global $woocommerce;
    $product_id = $_POST['assessories'];
    remove_action('woocommerce_add_to_cart', __FUNCTION__);
    echo 'custom fuction here';
}

function iconic_add_to_cart_form_action( $redirect ) {
    echo $redirect;
    return '';
}
add_filter( 'woocommerce_add_to_cart_form_action', 'iconic_add_to_cart_form_action' );

function get_wordpress_url($blogger) {
  if ( preg_match('@^(?:https?://)?([^/]+)(.*)@i', $blogger, $url_parts) ) {
    $query = new WP_Query ( 
      array ( "meta_key" => "blogger_permalink", "meta_value" => $url_parts[2] ) );
    if ($query->have_posts()) { 
      $query->the_post();
      $url = get_permalink(); 
    } 
    wp_reset_postdata(); 
  } 
  return $url ? $url : home_url();
}

function blogger_template_redirect() {
  global $wp_query;
  $blogger = $wp_query->query_vars['blogger'];
  if ( isset ( $blogger ) ) {
    wp_redirect( get_wordpress_url ( $blogger ) , 301 );
    exit;
  }
}

function register_my_menus() {
  register_nav_menus(
    array(
      'how-to-use-submenu1' => __( 'How To Use Submenu 1' ),
      'how-to-use-submenu2' => __( 'How To Use Submenu 2' )
    )
  );
}

// remove dashicons in frontend to non-admin 
function wpdocs_dequeue_dashicon() {
    if (current_user_can( 'update_core' )) {
        return;
    }
    wp_deregister_style('dashicons');
}

/* Proper way to enqueue scripts and styles */
function mytheme_custom_scripts(){
    
    // Register and Enqueue a Script
    // get_stylesheet_directory_uri will look up child theme location
    wp_register_script( 'classie', get_stylesheet_directory_uri() . '/js/classie.js', array('jquery'), null, true);
    wp_enqueue_script( 'classie' );
	
    wp_register_script( 'modernizr', get_stylesheet_directory_uri() . '/js/modernizr.custom.js', array('jquery'), null, false);
    wp_enqueue_script( 'modernizr' );
    
    wp_register_script( 'extra', get_stylesheet_directory_uri() . '/js/extra.js', array('jquery'), null, false);
    wp_enqueue_script( 'extra' );
	
}
 
/*function mycustom_wp_footer() {
    
}*/


/**** FILTERS ****/

add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
add_filter( 'pre_get_posts','searchfilter' );
add_filter( 'wp_insert_post_data', 'wpse_40574_populate_excerpt', 99, 2 );
add_filter( 'query_vars', 'blogger_query_vars_filter' );

function custom_excerpt_length( $length ) {
    return 22;//change the number for the length you want
}

function searchfilter($query) {
    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('post'));
    }
    return $query;
}

/** 
 * Returns the original content string if its word count is lesser than $length, 
 * or a trimed version with the desired length.
 * Reference: see this StackOverflow Q&A - http://stackoverflow.com/q/11521456/1287812
 */
function wpse_40574_create_excerpt( $content, $length = 20 )
{
    $the_string = preg_replace( '/\[\/?et_pb.*?\]/', ' ', $content );
    $words = explode( ' ', $the_string );

    /**
     * The following is a more efficient way to split the $content into an array of words
     * but has the caveat of spliting Url's into words ( removes the /, :, ., charachters )
     * so, not very useful in this context, could be improved though.
     * Note that $words[0] has to be used as the array to be dealt with (count, array_slice)
     */
    //preg_match_all( '/\b[\w\d-]+\b/', $content, $words );

    if( count($words) <= $length ) 
        $result = $content;
    else
        $result = implode( ' ', array_slice( $words, 0, $length ) );

    return $result;
}

// Define the custom excerpt length
$wpse_40574_custom_excerpt_length = 20;

/** 
 * Checks if the the post has excerpt or not
 * Code reference: http://wordpress.stackexchange.com/a/52897/12615
 */
// 
function wpse_40574_populate_excerpt( $data, $postarr ) 
{   
    global $wpse_40574_custom_excerpt_length;

    // check if it's a valid call
    if ( !in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) && 'post' == $data['post_type'] ) 
    {
        // if the except is empty, call the excerpt creation function
        if ( strlen($data['post_excerpt']) == 0 ) 
            $data['post_excerpt'] = wpse_40574_create_excerpt( $data['post_content'], $wpse_40574_custom_excerpt_length );
    }

    return $data;
}

function blogger_query_vars_filter( $vars ) {
  $vars[] = "blogger";
  return $vars;
}

?>