<?php

if ( function_exists( 'add_theme_support' ) )
    add_theme_support( 'post-thumbnails' );

//include get_template_directory() . '/post-type/restaurant.php';
get_template_part('post-type/restaurant');
get_template_part('post-type/plato');

function custom_controllers( $controllers ) {
  // JSON API will look for classes based on the names given here
  // and following the format JSON_API_[name]_Controller
  $controllers[] = 'Android';
  return $controllers;
}
 
function restaurant_controller_path( $default_path ) {
  return get_template_directory() . '/ws-controllers/android.php';
}
 
add_filter( 'json_api_controllers', 'custom_controllers' );
add_filter( 'json_api_android_controller_path', 'restaurant_controller_path' );

function save_data($table, $data, $condicion)
{
    global $wpdb;
    
    $result = $wpdb->update($table,$data, $condicion);
    
    if (!$result)
    {
        $wpdb->insert($table,$data);
    }
}

?>
  
 
