<?php
/** Test-only mu-plugin. Never install on a real website. */
if ( ! defined( 'LK_DISPOSABLE_WP' ) || true !== LK_DISPOSABLE_WP ) { return; }
$failure = $_SERVER['HTTP_X_LK_TEST_FAILURE'] ?? '';
if ( 'insert' === $failure ) {
    add_filter( 'wp_insert_post_empty_content', function ( $empty, $post ) {
        return 'lk_photo' === ( $post['post_type'] ?? '' ) ? true : $empty;
    }, 10, 2 );
}
if ( 'thumbnail' === $failure ) {
    add_filter( 'update_post_metadata', function ( $check, $id, $key ) {
        return '_thumbnail_id' === $key ? false : $check;
    }, 10, 3 );
}
