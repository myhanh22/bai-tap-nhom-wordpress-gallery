<?php
// Custom Shortcode hiển thị thông báo hệ thống bảo mật
function custom_system_notice_shortcode( $atts, $content = null ) {
    // Cấu hình thuộc tính mặc định
    $atts = shortcode_atts(
        array(
            'type' => 'info', // info, warning, success
            'title' => 'Thông báo hệ thống',
        ),
        $atts,
        'system_notice'
    );
    // Làm sạch dữ liệu đầu vào (Sanitize)
    $clean_type = sanitize_text_field( $atts['type'] );
    $clean_title = sanitize_text_field( $atts['title'] );
    $clean_content = $content ? sanitize_text_field( $content ) : 'Không có nội dung thông báo.';
    // Xuất dữ liệu được mã hóa an toàn (Escape HTML)
    $output = '<div class="notice-box notice-' . esc_attr( $clean_type ) . '" style="border: 2px solid #0073aa; padding: 15px; background: #f1f1f1; margin: 10px 0; border-radius: 5px;">';
    $output .= '<h4 style="margin:0 0 5px 0; color:#0073aa;">' . esc_html( $clean_title ) . '</h4>';
    $output .= '<p style="margin:0;">' . esc_html( $clean_content ) . '</p>';
    $output .= '</div>';
    return $output;
}
add_shortcode( 'system_notice', 'custom_system_notice_shortcode' );