<?php
 
/*
 Controller name: Android
 Controller description: Funciones para el envio de información a la aplicación android
 */
 
final class JSON_API_Android_Controller {
 
  public function info() {
    return array(
      'version' => '1.0'
    );
  }
 
  /*public function get() {
 
    $object = new stdClass();
    $object->name = "Object";
    $object->description = "A JSON encodable object.";
 
    return array(
      "message" => "Hello World!",
      "fruit" => array( "banana", "apple", "pear", "orange" ),
      "object" => $object
    );
  }*/
  
  public function restaurantes()
  {
      global $wpdb;
      $latitud = $_POST['latitud'];
      $longitud = $_POST['longitud'];
      
      $query =  'select * '
              . 'from restaurant';
      
      $result = $wpdb->get_results($query);
      
      
      foreach ($result as $key => $restaurant) 
      {
          $img_path = wp_get_attachment_image_src(get_post_thumbnail_id($restaurant->id), 'thumbnail');
          $result[$key]->img = current($img_path);
      }
      
      return array(
        'data' => $result
      );
  }
 
}