
function astra_child_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'astra_child_scripts' );
