# Kiểm kê Theme/Plugin và giấy phép

Phiên bản dưới đây là những gói được cài trong site LocalWP `baitapnhom1` ngày 27/10/2026. Ghi lại phiên bản thực tế trong **Plugins → Installed Plugins** và **Appearance → Themes** nếu nhóm cập nhật gói.

| Thành phần | Phiên bản | Trạng thái | Giấy phép | Mục đích |
|---|---:|---|---|---|
| Lăng Kính — Astra Child | 2.4.1 | Đang dùng | GPL-2.0-or-later | Giao diện, CPT thư viện ảnh và form gửi ảnh |
| Astra | 4.14.1 | Theme cha | GPL-2.0-or-later | Theme nền |
| Twenty Twenty-Five | 1.5 | Không hoạt động | GPL-2.0-or-later | Theme mặc định WordPress |
| Twenty Twenty-Four | 1.6 | Không hoạt động | GPL-2.0-or-later | Theme mặc định WordPress |
| Twenty Twenty-Three | 1.7 | Không hoạt động | GPL-2.0-or-later | Theme mặc định WordPress |
| Image Watermark — dFactory | 2.0.16 | Đang hoạt động | MIT | Watermark tự động khi upload |
| Converter for Media — matt plugins | 6.7.0 | Đang tắt trên LocalWP | GPL-2.0-or-later | Tạo và phục vụ ảnh WebP |

## Nguồn xác minh

- [Astra](https://wordpress.org/themes/astra/) và WordPress mặc định: metadata `style.css` trong từng theme đã cài.
- [Image Watermark](https://wordpress.org/plugins/image-watermark/): header và `readme.txt` trong gói phiên bản 2.0.16 khai báo MIT.
- [Converter for Media](https://wordpress.org/plugins/webp-converter-for-media/): `readme.txt` trong gói phiên bản 6.7.0 khai báo GPLv2 or later.
- Quyền sử dụng ảnh trong `media-assets/` là quyền riêng của từng ảnh, không được xác nhận bởi license của mã/theme/plugin.

30 bản ảnh mẫu có watermark được tạo bằng renderer Image Watermark, font Open Sans từ plugin; không phân phối thêm file font hoặc sửa vendor. Bản gốc và bản watermark được giữ riêng trong theme. Watermark upload được cấu hình riêng tại Settings → Watermark. WebP trên Nginx chưa hoàn tất; xem HUONG-DAN.md trước khi bật lại Converter for Media.
