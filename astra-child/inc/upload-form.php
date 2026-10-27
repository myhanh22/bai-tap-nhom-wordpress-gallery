<?php
/** Login-protected frontend submission form for the lk_photo post type. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'lk_upload_form', 'lk_upload_form_shortcode' );
add_action( 'after_switch_theme', 'lk_create_upload_page' );

/** Create the upload page on theme activation without duplicating existing pages. */
function lk_create_upload_page() {
    if ( get_page_by_path( 'dang-tac-pham' ) ) {
        return;
    }

    wp_insert_post(
        array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => 'Đăng tác phẩm',
            'post_name'    => 'dang-tac-pham',
            'post_content' => '[lk_upload_form]',
        )
    );
}

/**
 * Render the upload form and store approved-type image submissions as pending works.
 * A logged-in author/editor with upload_files and edit_posts capabilities is required.
 */
function lk_upload_form_shortcode() {
    $page_url = get_permalink();
    if ( ! is_user_logged_in() ) {
        $login_url = wp_login_url( $page_url );
        return '<section class="lk-upload"><p>Đăng nhập bằng tài khoản cộng tác viên để gửi tác phẩm.</p><p><a href="' . esc_url( $login_url ) . '">Đăng nhập</a></p></section>';
    }

    if ( ! current_user_can( 'upload_files' ) || ! current_user_can( 'edit_posts' ) ) {
        return '<section class="lk-upload"><p>Tài khoản hiện tại chưa được cấp quyền tải ảnh lên.</p></section>';
    }

    $notice = '';
    $notice_class = 'error';
    $method = isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '';

    if ( isset( $_GET['lk_upload'] ) && 'pending' === sanitize_key( wp_unslash( $_GET['lk_upload'] ) ) ) {
        $notice = 'Tác phẩm đã được gửi và đang chờ quản trị viên duyệt.';
        $notice_class = 'success';
    } elseif ( 'POST' === $method && isset( $_POST['lk_upload_submit'] ) ) {
        $nonce = isset( $_POST['lk_upload_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['lk_upload_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'lk_upload_photo' ) ) {
            $notice = 'Phiên gửi biểu mẫu đã hết hạn. Hãy tải lại trang và thử lại.';
        } else {
            $title = isset( $_POST['lk_photo_title'] ) ? sanitize_text_field( wp_unslash( $_POST['lk_photo_title'] ) ) : '';
            $description = isset( $_POST['lk_photo_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lk_photo_description'] ) ) : '';
            $topic_id = isset( $_POST['lk_topic'] ) ? absint( wp_unslash( $_POST['lk_topic'] ) ) : 0;
            $file = isset( $_FILES['lk_photo_file'] ) && is_array( $_FILES['lk_photo_file'] ) ? $_FILES['lk_photo_file'] : null;
            $file_error = $file && isset( $file['error'] ) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
            $file_size = $file && isset( $file['size'] ) ? (int) $file['size'] : 0;
            $max_size = 12 * MB_IN_BYTES;

            if ( '' === $title ) {
                $notice = 'Hãy nhập tên tác phẩm.';
            } elseif ( ! $file || UPLOAD_ERR_NO_FILE === $file_error ) {
                $notice = 'Hãy chọn một tệp ảnh.';
            } elseif ( UPLOAD_ERR_OK !== $file_error ) {
                $notice = 'Không tải được tệp. Hãy thử lại hoặc chọn ảnh nhỏ hơn.';
            } elseif ( $file_size > $max_size || $file_size > wp_max_upload_size() ) {
                $notice = 'Ảnh vượt quá giới hạn tải lên. Giới hạn của biểu mẫu là 12 MB.';
            } else {
                $allowed_mimes = array(
                    'jpg|jpeg' => 'image/jpeg',
                    'png'      => 'image/png',
                    'webp'     => 'image/webp',
                );
                $checked_file = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );

                if ( empty( $checked_file['type'] ) || ! in_array( $checked_file['type'], $allowed_mimes, true ) ) {
                    $notice = 'Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.';
                } else {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';
                    require_once ABSPATH . 'wp-admin/includes/image.php';

                    $attachment_id = media_handle_upload(
                        'lk_photo_file',
                        0,
                        array(),
                        array(
                            'test_form' => false,
                            'mimes'     => $allowed_mimes,
                        )
                    );

                    if ( is_wp_error( $attachment_id ) ) {
                        $notice = 'WordPress không xử lý được ảnh. Hãy thử ảnh khác.';
                    } else {
                        $photo_id = wp_insert_post(
                            array(
                                'post_type'    => 'lk_photo',
                                'post_status'  => 'pending',
                                'post_title'   => $title,
                                'post_content' => $description,
                                'post_author'  => get_current_user_id(),
                            ),
                            true
                        );

                        if ( is_wp_error( $photo_id ) ) {
                            wp_delete_attachment( $attachment_id, true );
                            $notice = 'Không lưu được tác phẩm. Hãy thử lại.';
                        } else {
                            set_post_thumbnail( $photo_id, $attachment_id );
                            update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );
                            wp_update_post(
                                array(
                                    'ID'          => $attachment_id,
                                    'post_parent' => $photo_id,
                                )
                            );

                            if ( $topic_id ) {
                                $topic = get_term( $topic_id, 'lk_topic' );
                                if ( $topic && ! is_wp_error( $topic ) ) {
                                    wp_set_object_terms( $photo_id, array( $topic_id ), 'lk_topic' );
                                }
                            }

                            $redirect_url = $page_url ? $page_url : home_url( '/dang-tac-pham/' );
                            wp_safe_redirect( add_query_arg( 'lk_upload', 'pending', $redirect_url ) );
                            exit;
                        }
                    }
                }
            }
        }
    }

    $topics = get_terms(
        array(
            'taxonomy'   => 'lk_topic',
            'hide_empty' => false,
        )
    );

    ob_start();
    ?>
    <section class="lk-upload" aria-labelledby="lk-upload-title">
        <h1 id="lk-upload-title">Gửi tác phẩm</h1>
        <?php if ( $notice ) : ?>
            <p class="lk-upload-message lk-upload-message--<?php echo esc_attr( $notice_class ); ?>" role="status"><?php echo esc_html( $notice ); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <?php echo wp_nonce_field( 'lk_upload_photo', 'lk_upload_nonce', true, false ); ?>
            <p>
                <label for="lk-photo-title">Tên tác phẩm</label>
                <input id="lk-photo-title" name="lk_photo_title" type="text" maxlength="200" required>
            </p>
            <p>
                <label for="lk-photo-description">Mô tả</label>
                <textarea id="lk-photo-description" name="lk_photo_description" rows="4"></textarea>
            </p>
            <?php if ( ! is_wp_error( $topics ) && $topics ) : ?>
                <p>
                    <label for="lk-photo-topic">Chủ đề / album</label>
                    <select id="lk-photo-topic" name="lk_topic">
                        <option value="0">Chọn chủ đề</option>
                        <?php foreach ( $topics as $topic ) : ?>
                            <option value="<?php echo esc_attr( $topic->term_id ); ?>"><?php echo esc_html( $topic->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <?php endif; ?>
            <p>
                <label for="lk-photo-file">Ảnh (JPG, PNG hoặc WebP; tối đa 12 MB)</label>
                <input id="lk-photo-file" name="lk_photo_file" type="file" accept="image/jpeg,image/png,image/webp" required>
            </p>
            <p>Ảnh gửi lên sẽ được quản trị viên duyệt trước khi xuất hiện trong thư viện.</p>
            <p><button type="submit" name="lk_upload_submit" value="1">Gửi tác phẩm</button></p>
        </form>
    </section>
    <?php

    return ob_get_clean();
}
