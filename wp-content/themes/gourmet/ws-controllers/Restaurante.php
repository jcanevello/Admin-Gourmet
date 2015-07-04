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
        $latitud = isset($_POST['latitud']) ? $_POST['latitud'] : NULL;
        $longitud = isset($_POST['longitud']) ? $_POST['longitud'] : NULL;
        
        if (empty($latitud) || empty($longitud)) 
            return array("status" => 'error');

//        $latitud = -11.995679;
//        $longitud = -77.008658;

        $query = 'select `idrestaurante`, `nombre`, `telefonos`, `tipo_restaurante`, `horario_restaurante`, `direccion`, `latitud`, `longitud`, `foto`, '
                . '(DEGREES(acos(sin(radians('.$latitud.')) * sin(radians(`latitud`)) + cos(radians('.$latitud.')) *  cos(radians(`latitud`)) * cos(radians('.$longitud.') - radians(`longitud`)))) * 111133.84) as distancia '
                . 'from restaurant '
                . 'where `estado` = 1 '
                . 'having distancia <= 1000'; // 1KM
        
//        echo var_dump($query);die();

        $result = $wpdb->get_results($query);
        
        return array(
            'count' => count($result),
            'results' => $result
        );
    }
    
    public function get_platos() 
    {
        global $wpdb;
        $idrestaurante = isset($_POST['idRestaurante']) ? $_POST['idRestaurante'] : NULL ;
        
        if(empty($idrestaurante))
            return array("status" => 'error');

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
        $idRestaurante = isset($_POST['idRestaurante']) ? $_POST['idRestaurante'] : NULL;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : NULL ;
        $hora = isset($_POST['hora']) ? $_POST['hora'] : NULL ;
        $numeroPersonas = isset($_POST['numeroPersonas']) ? $_POST['numeroPersonas'] : NULL ;
        
        if(empty($idRestaurante) || empty($fecha) || empty($hora) || empty($numeroPersonas)) 
            return array("status" => 'false');
        
        return array("status" => 'true');
    }
    
    public function guardar_reserva()
    {  
        global $wpdb;
        
        $idReserva = isset($_POST['idReserva']) ? $_POST['idReserva'] : NULL ;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : NULL ;
        $hora = isset($_POST['hora']) ? $_POST['hora'] : NULL ;
        $numeroPersonas = isset($_POST['numeroPersonas']) ? $_POST['numeroPersonas'] : NULL ;
        $pedido = isset($_POST['pedido']) ? $_POST['pedido'] : NULL ;
        $nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : NULL ;
        $apellidoUsuario = isset($_POST['apellidoUsuario']) ? $_POST['apellidoUsuario'] : NULL ;
        $email = isset($_POST['email']) ? $_POST['email'] : NULL ;
        $dni = isset($_POST['dni']) ? $_POST['dni'] : NULL ;
        $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : NULL ;
        $idRestaurante = isset($_POST['idRestaurante']) ? $_POST['idRestaurante'] : NULL;
        
        if(empty($fecha) || empty($hora) || empty($numeroPersonas) || empty($pedido) || empty($nombreUsuario) || empty($apellidoUsuario) || empty($email) || empty($dni) || empty($telefono) || empty($idRestaurante))
            return array("status" => 'error');
        
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
            return array("status" => 'error'); 
            
        return array("status" => 'ok'); 
    }
    
    public function actualizar_reserva()
    {
        global $wpdb;
        
        $idReserva = isset($_POST['idReserva']) ? $_POST['idReserva'] : NULL ;
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : NULL ;
        $hora = isset($_POST['hora']) ? $_POST['hora'] : NULL ;
        $numeroPersonas = isset($_POST['numeroPersonas']) ? $_POST['numeroPersonas'] : NULL ;
        
        if(empty($idReserva) || empty($fecha) || empty($hora) || empty($numeroPersonas))
            return array("status" => 'error'); 
        
        $data = array(
                'fechaReserva' => $fecha,
                'horaReserva' => $hora,
                'numPersonas' => $numeroPersonas,
            );
        
        $condicion = array('idreserva' => $idReserva);
        
        if(!$wpdb->update('reserva',$data, $condicion))
            return array("status" => 'error'); 
        
        return array("status" => 'ok'); 
    }
    
    public function eliminar_reserva()
    {
        global $wpdb;
        
        $idReserva = isset($_POST['idReserva']) ? $_POST['idReserva'] : NULL ;
        
        if(empty($idReserva))
           return array("status" => 'error'); 
        
        if(!$wpdb->delete('reserva', array('idreserva' => $idReserva)))
            return array("status" => 'error'); 
        
        return array("status" => 'ok'); 
        
    }
}
