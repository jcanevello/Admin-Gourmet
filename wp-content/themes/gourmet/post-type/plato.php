<?php 

add_action( 'init', 'create_post_type_plato' );
function create_post_type_plato() 
{
  register_post_type( 'plato',
    array(
      'labels' => array(
        'name' => __( 'Platos' ),
        'singular_name' => __( 'Plato' ),
        'add_new_item'  => __( 'Nuevo plato', ''),
        'edit_item' => __( 'Editar plato', ''),
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array( 'slug' => 'plato' ),
      'menu_icon' => 'dashicons-store',
      'supports' => array('title', 'thumbnail',),
      'capability_type'    => 'post',
    )
  );
}
  
add_action( 'add_meta_boxes', 'formulario_plato' );

function formulario_plato()
{
    add_meta_box( 
        'fr',
        __( 'Datos', '' ),
        'formulario_plato_content',
        'plato',
        'side',
        'high'
    );
}

function formulario_plato_content($post)
{ 
    wp_nonce_field(basename(__FILE__), "formulario_plato_content-nonce");
    global $wpdb;
    $result = $wpdb->get_results('SELECT `tipo`, `precio`, `isentrada`, `idrestaurante` FROM `plato` WHERE `idplato` = ' .$post->ID);
//    echo var_dump($post);
    $idrestaurante = isset($result[0]->idrestaurante) ? $result[0]->idrestaurante : $_GET['idrestaurante'];
?>
    <div>  
        <label for="p-tipo">Tipo de comida:</label>
        <input name="p-tipo" type="text" value="<?php echo isset($result[0]) ? $result[0]->tipo : ''  ?>" required="true"><br><br>
        
        <label for="p-precio">Precio:</label>        
        <input name="p-precio" type="number" step="any" value="<?php echo isset($result[0]) ? $result[0]->precio : '' ?>" required="true"><br><br>
        
        <h4>Tipo de plato</h4>
        <label>
            <input type="radio" name="isentrada" value="1" <?php echo (isset($result[0])&& $result[0]->isentrada == 1) ? ' checked' : '' ?> <?php echo (!isset($result[0])) ? 'checked' : '' ?>>
            Segundo
        </label><br>
        <label>
            <input type="radio" name="isentrada" value="0" <?php echo (isset($result[0])&& $result[0]->isentrada == 0) ? ' checked' : '' ?>>
            Entrada
        </label>
        <input class="hidden" type="text" name="idrestaurante" value="<?php echo $idrestaurante ?>">
        <h4><a href="/wp-admin/post.php?post=<?php echo $idrestaurante ?>&action=edit">Volver a <?php echo get_post($idrestaurante)->post_title ?></a></h4>
        
    </div>

<?php } 

function formulario_plato_save($post_id)
{
    if (!isset($_POST["formulario_plato_content-nonce"]) || !wp_verify_nonce($_POST["formulario_plato_content-nonce"], basename(__FILE__)))
        return $post_id;
 
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
 
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    
    $nombre = '';
    $tipo = '';
    $precio = '';
    $isentrada = '';
    $idrestaurante = '';
    $img_path = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');
    $img = empty($img_path) ? home_url() . '/wp-content/themes/gourmet/img/imagen-plato-default.png' : current($img_path);
    
//    echo var_dump($img);
//    die();
    
    if(isset($_POST["post_title"]))  
        $nombre = $_POST["post_title"];
    
    if(isset($_POST["p-tipo"]))  
        $tipo = $_POST["p-tipo"];
    
    if(isset($_POST["p-precio"]))  
        $precio = $_POST["p-precio"];
    
    if(isset($_POST["isentrada"]))  
        $isentrada = $_POST["isentrada"];
    
    if(isset($_POST["idrestaurante"]))  
        $idrestaurante = $_POST["idrestaurante"];
    
    save_data(
        'plato',
        array(
            'idplato' => $post_id,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'precio' => $precio,
            'foto' => $img,
            'isentrada' => $isentrada,
            'idrestaurante' => $idrestaurante,
            'estado' => 1
        ),
        array('idplato' => $post_id)
    );
    
}
add_action("save_post", "formulario_plato_save");

function trash_plato($post_id)
{
    $plato = get_post($post_id);
    if($plato->post_type == 'plato')
    {
        global $wpdb;
        wp_reset_query();
        save_data('plato', array('estado' => 0), array('idplato' => $post_id));
        $result = $wpdb->get_results('SELECT `idrestaurante` FROM `plato` WHERE `idplato` = ' .$post_id);
        header('Location: /wp-admin/post.php?post='.current($result)->idrestaurante.'&action=edit');
        exit();
    }
    wp_reset_query();
}

add_action('wp_trash_post', 'trash_plato');

function delete_plato($post_id)
{
    global $wpdb;
    
    $wpdb->delete('plato', array('idplato' => $post_id));
}

add_action('delete_post', 'delete_plato');

function untrash_plato($post_id)
{
    save_data('plato', array('estado' => 1), array('idplato' => $post_id));
}

add_action('untrash_post', 'untrash_plato');

?>