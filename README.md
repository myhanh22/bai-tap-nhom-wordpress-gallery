# Thư viện ảnh số — Nhóm 1

Giao diện mẫu **Lăng Kính**, child theme của Astra cho WordPress.

## Cài đặt

Cài WordPress trong LocalWP, cài theme cha Astra, rồi tải ZIP child theme lên tại **Giao diện → Thêm mới → Tải giao diện lên** và kích hoạt. Trang chủ tự hiển thị 30 ảnh minh họa. Không cần Elementor hoặc nhập SQL để xem mẫu.

Xem [hướng dẫn cài đặt, nhập tác phẩm và phạm vi tính năng](astra-child/HUONG-DAN.md).

## Cấu trúc

- `astra-child/`: mã giao diện, trang chủ, bộ lọc, lightbox và ảnh minh họa đi kèm.
- `media-assets/`: 30 ảnh gốc của dự án.

Có thể cài thủ công bằng cách copy `astra-child` vào `wp-content/themes/` trên site LocalWP. Giữ theme cha `astra` bên cạnh, không lồng child theme vào thư mục Astra.

## Tình trạng

Có giao diện responsive, chủ đề/album, tác giả WordPress, tìm kiếm không dấu, lightbox và lazy loading. Có menu **Tác phẩm** để quản lý ảnh thật; dùng ảnh mẫu khi chưa có tác phẩm đã đăng kèm ảnh đại diện.

Chưa bao gồm watermark tự động, tự chuyển WebP, hardening hoặc báo cáo bảo mật. Chưa có database của website nhóm đang làm trên LocalWP. Không commit mật khẩu, cấu hình kết nối hay database chứa thông tin nhạy cảm lên GitHub công khai. Nhóm cần xác minh quyền sử dụng ảnh và lập bảng license cho các theme/plugin cài thực tế.
