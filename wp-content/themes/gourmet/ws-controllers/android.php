<?php

/*
  Controller name: Android
  Controller description: Funciones para el envio de información a la aplicación android
 */

final class JSON_API_Android_Controller {

    public function info() 
    {
        return array(
            'version' => '1.0'
        );
    }

    /* public function get() {

      $object = new stdClass();
      $object->name = "Object";
      $object->description = "A JSON encodable object.";

      return array(
      "message" => "Hello World!",
      "fruit" => array( "banana", "apple", "pear", "orange" ),
      "object" => $object
      );
      } */

    public function restaurantes() 
    {
        global $wpdb;
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];

//        $latitud = -11.995679;
//        $longitud = -77.008658;

        $query = 'select `idrestaurante`, `nombre`, `telefonos`, `tipo_restaurante`, `horario_restaurante`, `direccion`, `latitud`, `longitud`, `foto`, '
                . '(DEGREES(acos(sin(radians('.$latitud.')) * sin(radians(`latitud`)) + cos(radians('.$latitud.')) *  cos(radians(`latitud`)) * cos(radians('.$longitud.') - radians(`longitud`)))) * 111133.84) as distancia '
                . 'from restaurant '
                . 'having distancia <= 1000'; // 1KM
        
//        echo var_dump($query);die();

        $result = $wpdb->get_results($query);
        
        return array(
            'count' => count($result),
            'results' => $result
        );
    }
}
