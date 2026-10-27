<?php
/** Lăng Kính — Astra child theme for the Group 1 gallery. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'after_setup_theme', function () {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_image_size( 'lk-gallery', 800, 1000, false );
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'astra-parent', get_template_directory_uri() . '/style.css', array(), wp_get_theme( 'astra' )->get( 'Version' ) );
    wp_enqueue_style( 'lk-style', get_stylesheet_uri(), array( 'astra-parent' ), filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_script( 'lk-gallery', get_stylesheet_directory_uri() . '/js/custom.js', array(), filemtime( get_stylesheet_directory() . '/js/custom.js' ), true );
} );

add_action( 'init', function () {
    register_post_type( 'lk_photo', array(
        'labels' => array( 'name' => 'Tác phẩm', 'singular_name' => 'Tác phẩm', 'add_new_item' => 'Thêm tác phẩm', 'edit_item' => 'Sửa tác phẩm', 'featured_image' => 'Ảnh tác phẩm', 'set_featured_image' => 'Chọn ảnh tác phẩm' ),
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-format-gallery',
        'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt' ),
        'rewrite' => array( 'slug' => 'tac-pham' ),
    ) );
    register_taxonomy( 'lk_topic', 'lk_photo', array(
        'labels' => array( 'name' => 'Chủ đề / Album', 'singular_name' => 'Chủ đề', 'add_new_item' => 'Thêm chủ đề' ),
        'hierarchical' => true, 'show_in_rest' => true, 'public' => true,
        'rewrite' => array( 'slug' => 'chu-de' ),
    ) );
} );

/** Read actual published works first. Demo files are used only when none exist. */
function lk_gallery_items() {
    $items = array();
    $photos = get_posts( array( 'post_type' => 'lk_photo', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
    foreach ( $photos as $photo ) {
        $id = get_post_thumbnail_id( $photo->ID );
        if ( ! $id ) { continue; }
        $terms = get_the_terms( $photo->ID, 'lk_topic' );
        $topics = array();
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) { $topics[ $term->slug ] = $term->name; }
        }
        if ( ! $topics ) { $topics = array( 'tu-do' => 'Tự do' ); }
        $items[] = array(
            'title' => get_the_title( $photo ), 'author' => get_the_author_meta( 'display_name', $photo->post_author ),
            'topics' => $topics, 'src' => wp_get_attachment_image_url( $id, 'lk-gallery' ),
            'full' => wp_get_attachment_image_url( $id, 'full' ),
            'srcset' => wp_get_attachment_image_srcset( $id, 'lk-gallery' ),
            'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ) ?: get_the_title( $photo ),
        );
    }
    if ( $items ) { return $items; }
    $demo = array(
        array('phong-canh-que-15','Lối về bình yên','doi-song','Đời sống'),
        array('co-gai-che-o-08','Một thoáng dịu dàng','chan-dung','Chân dung'),
        array('hoang-hon-12','Chạm vào hoàng hôn','phong-canh','Phong cảnh'),
        array('hoa-sen-26','Hương mùa hạ','thien-nhien','Thiên nhiên'),
        array('ganh-hang-rong-22','Nhịp phố','doi-song','Đời sống'),
        array('con-meo-14','Người bạn nhỏ','dong-vat','Động vật'),
        array('duong-la-do-11','Hẹn với mùa thu','phong-canh','Phong cảnh'),
        array('be-gai-ha-giang-19','Nắng trên miền đá','chan-dung','Chân dung'),
        array('danh-ca-24','Ngày trở về','doi-song','Đời sống'),
        array('hoa-tulip-17','Bản hòa ca sắc màu','thien-nhien','Thiên nhiên'),
        array('sa-mac-06','Dấu chân trên cát','phong-canh','Phong cảnh'),
        array('tach-tra-09','Một buổi sáng chậm','tinh-vat','Tĩnh vật'),
        array('mua-thu-02','Khoảng trời xanh','phong-canh','Phong cảnh'),
        array('hoa-huong-duong-03','Phía mặt trời','thien-nhien','Thiên nhiên'),
        array('cho-con-04','Nụ cười bé nhỏ','dong-vat','Động vật'),
        array('doi-mat-05','Điều chưa kể','chan-dung','Chân dung'),
        array('phong-canh-chau-phi-01','Miền đất xa','phong-canh','Phong cảnh'),
        array('banh-ngot-07','Chút ngọt ngào','tinh-vat','Tĩnh vật'),
        array('be-gai-10','Vòng hoa tuổi thơ','chan-dung','Chân dung'),
        array('con-bo-13','Đồng cỏ yên lành','dong-vat','Động vật'),
        array('cuoi-ngua-16','Tự do trên đồng xanh','doi-song','Đời sống'),
        array('canh-dong-hoa-18','Mùa hoa ở lại','thien-nhien','Thiên nhiên'),
        array('ho-guom-20','Hà Nội trong sương','phong-canh','Phong cảnh'),
        array('cay-lua-21','Mùa gieo hy vọng','doi-song','Đời sống'),
        array('ham-ca-map-23','Góc phố thân quen','doi-song','Đời sống'),
        array('chan-trau-25','Tuổi thơ trên lưng trâu','doi-song','Đời sống'),
        array('co-gai-doi-mu-27','Dịu dàng trong nắng','chan-dung','Chân dung'),
        array('lang-bac-28','Một ngày ở Ba Đình','phong-canh','Phong cảnh'),
        array('cho-da-lat-29','Hẹn ở Đà Lạt','doi-song','Đời sống'),
        array('ban-hoa-30','Chở mùa qua phố','doi-song','Đời sống'),
    );
    foreach ( $demo as $row ) {
        $url = get_stylesheet_directory_uri() . '/assets/demo/' . $row[0] . '.jpg';
        $items[] = array( 'title' => $row[1], 'author' => 'Bộ sưu tập mẫu', 'topics' => array( $row[2] => $row[3] ), 'src' => $url, 'full' => $url, 'srcset' => '', 'alt' => $row[1] );
    }
    return $items;
}

require_once get_stylesheet_directory() . '/inc/upload-form.php';
