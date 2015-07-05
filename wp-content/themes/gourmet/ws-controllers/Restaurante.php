<?php

/*
  Controller name: Restaurante
  Controller description: Funciones para el envio de información a la aplicación android
 */

final class JSON_API_Restaurante_Controller {

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

    public function get_restaurantes() 
    {
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'));
        
        $latitud = isset($data->latitud) ? $data->latitud : NULL;
        $longitud = isset($data->longitud) ? $data->longitud : NULL;
        
        if (empty($latitud) || empty($longitud)) 
            return array("status" => false);

//        $latitud = -11.995679;
//        $longitud = -77.008658;

        $query = 'select `idrestaurante`, `nombre`, `telefonos`, `tipo_restaurante`, `horario_restaurante`, `direccion`, `latitud`, `longitud`, `foto`, '
                . '(DEGREES(acos(sin(radians('.$latitud.')) * sin(radians(`latitud`)) + cos(radians('.$latitud.')) *  cos(radians(`latitud`)) * cos(radians('.$longitud.') - radians(`longitud`)))) * 111133.84) as distancia '
                . 'from restaurant '
                . 'where `estado` = 1 '
                . 'having distancia <= 1000'; // 1KM
        
        $result = $wpdb->get_results($query);
        
        return array(
            'count' => count($result),
            'results' => print $result
        );
    }
    
    public function get_platos() 
    {
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'));
        
        $idrestaurante = isset($data->idRestaurante) ? $data->idRestaurante : NULL ;
                       
        if(empty($idrestaurante))
            return array("status" => false);

        $query = 'SELECT `idplato`,`nombre`,`tipo`, `precio`, `foto`, `isentrada`, `idrestaurante` FROM `plato` WHERE `estado` = 1 and `idrestaurante` = ' .$idrestaurante; // 1KM
        
//        echo var_dump($query);die();

        $result = $wpdb->get_results($query);
        
        return array(
            'count' => count($result),
            'results' => $result
        );
    }
    
    public function consultar_reserva()
    {
        $data = json_decode(file_get_contents('php://input'));
        
        $idRestaurante = isset($data->idRestaurante) ? $data->idRestaurante : NULL;
        $fecha = isset($data->fecha) ? $data->fecha : NULL ;
        $hora = isset($data->hora) ? $data->hora : NULL ;
        $numeroPersonas = isset($data->numeroPersonas) ? $data->numeroPersonas : NULL ;
        
        if(empty($idRestaurante) || empty($fecha) || empty($hora) || empty($numeroPersonas)) 
            return array("status" => false);
        
        return array("status" => true);
    }
    
    public function guardar_reserva()
    {  
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'));
        
        $idReserva = isset($data->idReserva) ? $data->idReserva : NULL ;
        $fecha = isset($data->fecha) ? $data->fecha : NULL ;
        $hora = isset($data->hora) ? $data->hora : NULL ;
        $numeroPersonas = isset($data->numeroPersonas) ? $data->numeroPersonas : NULL ;
        $pedido = isset($data->pedido) ? $data->pedido : NULL ;
        $nombreUsuario = isset($data->nombreUsuario) ? $data->nombreUsuario : NULL ;
        $apellidoUsuario = isset($data->apellidoUsuario) ? $data->apellidoUsuario : NULL ;
        $email = isset($data->email) ? $data->email : NULL ;
        $dni = isset($data->dni) ? $data->dni : NULL ;
        $telefono = isset($data->telefono) ? $data->telefono : NULL ;
        $idRestaurante = isset($data->idRestaurante) ? $data->idRestaurante : NULL;
        
        if(empty($fecha) || empty($hora) || empty($numeroPersonas) || empty($pedido) || empty($nombreUsuario) || empty($apellidoUsuario) || empty($email) || empty($dni) || empty($idRestaurante))
            return array("status" => false);
        
        $data = array(
                'idreserva' => $idReserva,
                'fechaReserva' => $fecha,
                'horaReserva' => $hora,
                'numPersonas' => $numeroPersonas,
                'pedidoReserva' => $pedido,
                'idrestaurante' => $idRestaurante,
                'nombreUsuario' => $nombreUsuario,
                'apellidosUsuario' => $apellidoUsuario,
                'email' => $email,
                'dni' => $dni,
                'telefono' => $telefono,
                'estado' => 1
            );
        
        if(! $wpdb->insert('reserva', $data))
            return array("status" => false); 
            
        return array("status" => TRUE); 
    }
    
    public function actualizar_reserva()
    {
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'));
        
        $idReserva = isset($data->idReserva) ? $data->idReserva : NULL ;
        $fecha = isset($data->fecha) ? $data->fecha : NULL ;
        $hora = isset($data->hora) ? $data->hora : NULL ;
        $numeroPersonas = isset($data->numeroPersonas) ? $data->numeroPersonas : NULL ;
        
        if(empty($idReserva) || empty($fecha) || empty($hora) || empty($numeroPersonas))
            return array("status" => false); 
        
        $data = array(
                'fechaReserva' => $fecha,
                'horaReserva' => $hora,
                'numPersonas' => $numeroPersonas,
            );
        
        $condicion = array('idreserva' => $idReserva);
        
        if(!$wpdb->update('reserva',$data, $condicion))
            return array("status" => false); 
        
        return array("status" => true); 
    }
    
    public function eliminar_reserva()
    {
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'));
        
        $idReserva = isset($data->idReserva) ? $data->idReserva : NULL ;
        
        if(empty($idReserva))
           return array("status" => false); 
        
        if(!$wpdb->delete('reserva', array('idreserva' => $idReserva)))
            return array("status" => false); 
        
        return array("status" => true); 
        
    }
}
