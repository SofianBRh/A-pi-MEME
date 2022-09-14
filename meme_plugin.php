<?

/**
 * Plugin Name:       Meme plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Plugin de meme.
 * Version:           1.00
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Messine Belaroussi
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

function wpm_custom_post_type()
{

    // On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
    $labels = array(
        // Le nom au pluriel
        'name'                => _x('Random Memes', 'Post Type General Name'),
        // Le nom au singulier
        'singular_name'       => _x('Random Meme', 'Post Type Singular Name'),
        // Le libellé affiché dans le menu
        'menu_name'           => __('Random Meme'),
        // Les différents libellés de l'administration
        'all_items'           => __('Tout meme'),
        'view_item'           => __('Voir les meme'),
        'add_new_item'        => __('Ajouter un meme'),
        'add_new'             => __('Ajouter'),
        'edit_item'           => __('Editer les meme'),
        'update_item'         => __('Modifier le meme'),
        'search_items'        => __('Rechercher un meme'),
        'not_found'           => __('Non trouvée'),
        'not_found_in_trash'  => __('Non trouvée dans la corbeille'),
    );

    // On peut définir ici d'autres options pour notre custom post type

    $args = array(
        'label'               => __('RandomMeme'),
        'description'         => __('Tous sur les meme'),
        'labels'              => $labels,
        // On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
        'supports'            => array('title', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
        /* 
    * Différentes options supplémentaires
    */
        'show_in_rest' => true,
        'hierarchical'        => false,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'              => array('slug' => 'RandomMeme'),
    );

    // On enregistre notre custom post type qu'on nomme ici "RandomMeme" et ses arguments
    register_post_type('RandomwMeme', $args);
}

// -------

function prefix_get_product($data)
{

    $posts = get_post(
        $data['id']
    );

    if (empty($posts)) {
        echo ("null");
        return null;
    }
    return $posts->post_title;
}


function prefix_get_products()
{
    // In practice this function would fetch the desired data. Here we are just making stuff up.
    $args = array(

        'post_type'   => 'RandomwMeme'
    );
    $allmeme = get_posts($args);

    shuffle($allmeme);

    $allmeme = $allmeme[0];

    $thumbnail = get_the_post_thumbnail($allmeme->ID);

    return [
        'title' => $allmeme->post_title,
        'image' => $thumbnail
    ];
}

function display_random_meme(){
    wp_enqueue_script( 'random_meme', plugin_dir_url( __FILE__ ) . "script.js", ['jquery'], 1.1, true );
    wp_register_script( 'random_meme', plugin_dir_url( __FILE__ ) . "script.js", ['jquery'], 1.1, true );
    return '<div id="test"></div>';
}

add_shortcode('Meme', 'display_random_meme');
/**
 * This function is where we register our routes for our example endpoint.
 */
function prefix_register_product_routes()
{
    // Here we are registering our route for a collection of products.
    register_rest_route('RandomMeme/v1', '/meme', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'prefix_get_products',
    ));
    // Here we are registering our route for single products. The (?P<id>[\d]+) is our path variable for the ID, which, in this example, can only be some form of positive number.
    register_rest_route('RandomMeme/v1', '/meme/(?P<id>[\d]+)', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'prefix_get_product',
    ));
}

add_action('rest_api_init', 'prefix_register_product_routes');
add_action('init', 'wpm_custom_post_type', 0);
