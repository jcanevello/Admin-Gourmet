<?php

if (function_exists('add_theme_support'))
    add_theme_support('post-thumbnails');

//include get_template_directory() . '/post-type/restaurant.php';
get_template_part('post-type/restaurant');
get_template_part('post-type/plato');
get_template_part('post-type/reserva');

function custom_controllers($controllers) {
    // JSON API will look for classes based on the names given here
    // and following the format JSON_API_[name]_Controller
    $controllers[] = 'Restaurante';
    return $controllers;
}

function restaurante_controller_path($default_path) {
    return get_template_directory() . '/ws-controllers/Restaurante.php';
}

add_filter('json_api_controllers', 'custom_controllers');
add_filter('json_api_restaurante_controller_path', 'restaurante_controller_path');

function save_data($table, $data, $condicion) {
    global $wpdb;

    $result = $wpdb->update($table, $data, $condicion);


    if (!$result) {
        $wpdb->insert($table, $data);
    }
}

function my_custom_admin_style() {
    $post_id = isset($_GET['post']) ? $_GET['post'] : null;
    $post = get_post($post_id);
    if (isset($post) && $post->post_type == 'plato') {
        ?>
        <style>
            .add-new-h2 {
                display: none;
            }
        </style>
        <?php

    }
    ?>
    <style>
        #menu-posts,
        #menu-comments,
        #menu-posts-plato {
            display: none;
        }
        
        .gtabs-panel {
            position: relative;
            display: block;
            min-height: 900px;
            margin-top: 25px;
        }

        .gtab {
            
            float: left;
        }
        
        .gtab-radio {
            display: none !important;
        }
        
        .gtab-label {
            width: 200px;
            display: block;
            padding: 5px;
            text-align: center;
            border: 1px solid #e5e5e5;
            font-size: 14px;
            padding: 8px 12px;
            margin: 0;
            line-height: 1.4;
            font-weight: bold;
            color: #000000;
        }
        
        .gcontent {
            display: none;
            position: absolute;
            left: 0;
            border: 1px solid #e5e5e5;
            background-color: #f5f5f5;
            padding: 10px;
        }
        
        .gtab-radio:checked ~ .gcontent {
            display: block;
        }

    </style>
    <?php

}

add_action('admin_head', 'my_custom_admin_style');
?>
  

