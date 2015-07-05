<?php 

add_action( 'init', 'create_post_type' );
function create_post_type() 
{
  register_post_type( 'restaurant',
    array(
      'labels' => array(
        'name' => __( 'Restaurantes' ),
        'singular_name' => __( 'Restaurant' ),
        'add_new_item'  => __( 'Nuevo restaurant', ''),
        'edit_item' => __( 'Editar restaurant', ''),
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array( 'slug' => 'restaurant' ),
      'menu_icon' => 'dashicons-store',
      'supports' => array('title', 'thumbnail',),
      'capability_type'    => 'post',
    )
  );
}
  
add_action( 'add_meta_boxes', 'formulario_restaurant' );

function formulario_restaurant()
{
    add_meta_box( 
        'fr',
        __( 'Datos', '' ),
        'formulario_restaurant_content',
        'restaurant',
        'side',
        'high'
    );
}

function formulario_restaurant_content($post)
{ 
    wp_nonce_field(basename(__FILE__), "formulario_restaurant_content-nonce");
    global $wpdb;
    $result = $wpdb->get_results('SELECT `direccion`, `telefonos`, `tipo_restaurante`, `horario_restaurante`, `latitud`, `longitud` FROM `restaurant` WHERE `idrestaurante` = ' .$post->ID);
?>
    <div>        
        <label for="r-direccion">Dirección:</label>
        <input name="r-direccion" type="text" value="<?php echo isset($result[0]) ? $result[0]->direccion : '' ?>"><br><br>
        
        <label for="r-telefono">Teléfono:</label>
        <input name="r-telefono" type="text" value="<?php echo isset($result[0]) ? $result[0]->telefonos : ''  ?>"><br><br>
        
        <label for="r-tipo">Tipo:</label>
        <input name="r-tipo" type="text" value="<?php echo isset($result[0]) ? $result[0]->tipo_restaurante : ''  ?>"><br><br>
        
        <label for="r-horario">Horario:</label>
        <input name="r-horario" type="text" value="<?php echo isset($result[0]) ? $result[0]->horario_restaurante : ''  ?>"><br><br>
        
        <h4>Ubicación</h4>
        <label for="r-latitud">Latitud:</label>
        <input name="r-latitud" type="number" step="any" value="<?php echo isset($result[0]) ? $result[0]->latitud : ''  ?>">
        <label for="r-longitud">Longitud:</label>
        <input name="r-longitud" type="number" step="any" value="<?php echo isset($result[0]) ? $result[0]->longitud : ''  ?>"><br><br>
        <hr>
        
        <div class="gtabs-panel">
            <div class="gtab">
                <input type="radio" id="gtab1" class="gtab-radio" name="gtabs" checked="">
                <label for="gtab1" class="gtab-label">Platos</label>
                <div class="gcontent">
                    <h2>Platos</h2>
                    <a href="/wp-admin/post-new.php?post_type=plato&idrestaurante=<?php echo $post->ID ?>">Añadir plato</a>

                    <?php
                    $aPlato = $wpdb->get_results('SELECT * FROM `plato` WHERE `estado` = 1 and `idrestaurante` = ' .$post->ID);
                    ?>
                    <br><br>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <td>Nombre</td>
                                <td>Tipo de menú</td>
                                <td>Precio</td>
                                <td>Tipo de plato</td>
                                <td>Imagen</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aPlato as $oPlato): ?>
                            <tr>
                                <td><?php echo $oPlato->nombre ?></td>
                                <td><?php echo $oPlato->tipo ?></td>
                                <td><?php echo $oPlato->precio ?></td>
                                <td><?php echo $oPlato->isentrada ?></td>
                                <td><img src="<?php echo $oPlato->foto ?>" width="100%"></td>
                                <td>
                                    <a href="/wp-admin/post.php?post=<?php echo $oPlato->idplato ?>&action=edit&idrestaurante=<?php echo $post->ID ?>" >Editar</a><br>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="gtab">
                <input type="radio" id="gtab2" class="gtab-radio" name="gtabs">
                <label for="gtab2" class="gtab-label">Reservas</label>
                <div class="gcontent">
                    <h2>Reservas</h2>
                    <?php
                        $aReservas = $wpdb->get_results('SELECT * FROM `reserva` WHERE `estado` = 1 and `idrestaurante` = ' .$post->ID);
                    ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nombre</td>
                                <td>Apellidos</td>
                                <td>DNI</td>
                                <td>Fecha</td>
                                <td>Hora</td>
                                <td>#Personas</td>
                                <td>Teléfono</td>
                                <td>Email</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aReservas as $oReservas): ?>
                            <tr>
                                <td><?php echo $oReservas->idreserva ?></td>
                                <td><?php echo $oReservas->nombreUsuario ?></td>
                                <td><?php echo $oReservas->apellidosUsuario ?></td>
                                <td><?php echo $oReservas->dni ?></td>
                                <td><?php echo $oReservas->fechaReserva ?></td>
                                <td><?php echo $oReservas->horaReserva ?></td>
                                <td><?php echo $oReservas->numPersonas ?></td>
                                <td><?php echo $oReservas->telefono ?></td>
                                <td><?php echo $oReservas->email ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><hr>
    </div>
    
<?php } 

function formulario_restaurant_save($post_id)
{
    if (!isset($_POST["formulario_restaurant_content-nonce"]) || !wp_verify_nonce($_POST["formulario_restaurant_content-nonce"], basename(__FILE__)))
        return $post_id;
 
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
 
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    
    $nombre = '';
    $direccion = '';
    $telefono = '';
    $tipo_restaurante = '';
    $horario_restaurante = '';
    $latitud = '';
    $longitud = '';
    $img_path = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');
    $img = empty($img_path) ? '' : current($img_path);
    
//    echo var_dump($img);
//    die();
    
    if(isset($_POST["post_title"]))  
        $nombre = $_POST["post_title"];
    
    if(isset($_POST["r-direccion"]))  
        $direccion = $_POST["r-direccion"];
    
    if(isset($_POST["r-telefono"]))  
        $telefono = $_POST["r-telefono"];
    
    if(isset($_POST["r-tipo"]))  
        $tipo_restaurante = $_POST["r-tipo"];
    
    if(isset($_POST["r-horario"]))  
        $horario_restaurante = $_POST["r-horario"];
    
    if(isset($_POST["r-latitud"]))  
        $latitud = $_POST["r-latitud"];
    
    if(isset($_POST["r-longitud"]))  
        $longitud = $_POST["r-longitud"];
    
    save_data(
        'restaurant',
        array(
            'idrestaurante' => $post_id,
            'nombre' => $nombre,
            'direccion' => $direccion,
            'telefonos' => $telefono,
            'tipo_restaurante' => $tipo_restaurante,
            'horario_restaurante' => $horario_restaurante,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'foto' => $img,
            'estado' => 1
        ),
        array('idrestaurante' => $post_id)
    );
    
}
add_action("save_post", "formulario_restaurant_save");

function trash_restaurant($post_id)
{
    save_data('restaurant', array('estado' => 0), array('idrestaurante' => $post_id));
}

add_action('wp_trash_post', 'trash_restaurant');

function delete_restaurant($post_id)
{
    global $wpdb;
    
    $wpdb->delete('restaurant', array('idrestaurante' => $post_id));
}

add_action('delete_post', 'delete_restaurant');

function untrash_restaurant($post_id)
{
    save_data('restaurant', array('estado' => 1), array('idrestaurante' => $post_id));
}

add_action('untrash_post', 'untrash_restaurant');

?>