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
    $result = $wpdb->get_results('SELECT `direccion`, `telefono`, `latitud`, `longitud`, `img` FROM `restaurant` WHERE `id` = ' .$post->ID);
?>
    <div>        
        <label for="r-direccion">Dirección:</label>
        <input name="r-direccion" type="text" value="<?php echo $result[0]->direccion ?>"><br><br>
        
        <label for="r-telefono">Teléfono:</label>
        <input name="r-telefono" type="text" value="<?php echo $result[0]->telefono ?>"><br><br>
        
        <h4>Ubicación</h4>
        <label for="r-latitud">Latitud:</label>
        <input name="r-latitud" type="number" value="<?php echo $result[0]->latitud ?>">
        <label for="r-longitud">Longitud:</label>
        <input name="r-longitud" type="number" value="<?php echo $result[0]->longitud ?>"><br><br>
        
        <input class="hidden-field" name="r-img" type="text" value="<?php echo $result[0]->img ?>"><br><br>
    </div>

    <script type="text/javascript">
        /*var buttom_media = document.getElementsByClassName("media-button-select");
        console.log(buttom_media)
        buttom_media.onclick = function(){
            var setting = document.getElementsByClassName("setting");
            console.log('ssss')
        };*/
    </script>
    
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
    $latitud = '';
    $longitud = '';
    $img = '';
    
    if(isset($_POST["post_title"]))  
        $nombre = $_POST["post_title"];
    
    if(isset($_POST["r-direccion"]))  
        $direccion = $_POST["r-direccion"];
    
    if(isset($_POST["r-telefono"]))  
        $telefono = $_POST["r-telefono"];
    
    if(isset($_POST["r-latitud"]))  
        $latitud = $_POST["r-latitud"];
    
    if(isset($_POST["r-longitud"]))  
        $longitud = $_POST["r-longitud"];
    
    save_data(
        'restaurant',
        array(
            'id' => $post_id,
            'nombre' => $nombre,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'img' => $img
        ),
        $post_id
    );
    
}
add_action("save_post", "formulario_restaurant_save");

?>