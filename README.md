# Thư viện ảnh số — Nhóm 1

Giao diện mẫu **Lăng Kính**, child theme của Astra cho WordPress.

## Cài đặt

Cài WordPress trong LocalWP, cài theme cha Astra, rồi tải ZIP child theme lên tại **Giao diện → Thêm mới → Tải giao diện lên** và kích hoạt. Trang chủ tự hiển thị 30 ảnh minh họa. Không cần Elementor hoặc nhập SQL để xem mẫu.

Xem [hướng dẫn cài đặt, nhập tác phẩm và phạm vi tính năng](astra-child/HUONG-DAN.md).

## Cấu trúc

- `astra-child/`: mã giao diện, trang chủ, bộ lọc, lightbox và ảnh minh họa đi kèm.
- `media-assets/`: 30 ảnh gốc của dự án.
- `scripts/build-demo-watermarks.php`: tạo lại 30 bản watermark bằng Image Watermark, không sửa ảnh gốc.

Có thể cài thủ công bằng cách copy `astra-child` vào `wp-content/themes/` trên site LocalWP. Giữ theme cha `astra` bên cạnh, không lồng child theme vào thư mục Astra.

## Tình trạng

Có giao diện responsive, chủ đề/album, tác giả WordPress, tìm kiếm không dấu, lightbox và lazy loading. CPT **Tác phẩm** lưu bài ảnh trong database WordPress. Trang **Đăng tác phẩm** dùng đăng nhập WordPress, nhận JPG/PNG/WebP và tạo bài chờ duyệt. Author không được tự xuất bản tác phẩm qua REST hoặc wp-admin; Admin/Editor duyệt bài. Bản 2.4.0 tự cập nhật quyền và tạo trang gửi ảnh còn thiếu trên theme đang hoạt động.

Trang chủ luôn giữ đủ 30 ảnh mẫu ghi “Bộ sưu tập mẫu”, đặt sau ảnh thật đã duyệt có ảnh đại diện. Ảnh mẫu đã có watermark “© Lăng Kính · Nhóm 1” nhúng trong ảnh; giữ ảnh gốc riêng, không nhập mẫu vào database. Liên kết **Đăng tác phẩm** có trên menu trang chủ và điện thoại.

Form dùng các chủ đề hiện có trên web, mặc định **Tự do**. Chọn **Khác** để nhập tên chủ đề mới (tối đa 80 ký tự); tên đã có được dùng lại. Mọi tác phẩm gửi lên vẫn phải duyệt.

Ô tìm kiếm được căn lại viền focus; lightbox bỏ viền vuông ở nút đóng/trước/sau nhưng giữ focus cho bàn phím. Bố cục lưới được tính lại theo ảnh đang hiển thị khi lọc.

Ảnh banner cố định, không đổi khi đăng tác phẩm mới. Chọn ảnh tại **Giao diện → Tùy biến → Lăng Kính — Ảnh banner**; có thể nhập chú thích riêng. Khi chưa chọn hoặc đã xóa ảnh, dùng ảnh mẫu “Lối về bình yên” có watermark.

WebP trên Nginx cần cấu hình riêng: không bật `themes` khi dùng Pass Thru của bản plugin đã kiểm tra. Xem hướng dẫn bên dưới trước khi bật lại plugin; tắt plugin chỉ là cách khôi phục ảnh tạm thời.

Kiểm thử trên WordPress riêng: xem [tests/README.md](tests/README.md).

Watermark tự động và WebP được xử lý bằng plugin; xem [bảng kiểm kê theme/plugin/license](PLUGIN-LICENSES.md). Còn phải hoàn thiện cấu hình hardening, quét lỗ hổng và hồ sơ báo cáo. Database dùng các bảng WordPress mặc định; không cần SQL schema riêng. Không commit mật khẩu, cấu hình kết nối hay database chứa thông tin nhạy cảm lên GitHub công khai.
