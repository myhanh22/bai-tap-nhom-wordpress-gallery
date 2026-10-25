
<?php

// Nạp CSS của theme cha Astra và theme con Astra Child
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

function astra_child_enqueue_styles() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
}
