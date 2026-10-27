# Kiểm thử WordPress riêng

Các test tạo người dùng, bài, media và xóa trang gửi ảnh trong **database kiểm thử**. Không chạy trên site có dữ liệu thật, không trỏ `LK_WP_ROOT` vào LocalWP đang dùng. Chỉ cài fixture vào bản WordPress dùng một lần.

Chuẩn bị WordPress mới với core WordPress, Astra và bản `astra-child` đang sửa. Tạo database riêng, cài WordPress, kích hoạt Astra Child, đặt permalink `/%postname%/`. Cấu hình URL HTTP riêng, tắt WP Cron; không sao chép tài khoản/database của site thật. Cung cấp thông tin DB trong `wp-config.php` của môi trường riêng, ngoài repo.

Trong `wp-config.php` kiểm thử, đặt `define( 'LK_DISPOSABLE_WP', true );`. Copy `tests/fixtures/upload-failures.php` vào `wp-content/mu-plugins/lk-test-failures.php` của môi trường này để giả lập lỗi lưu bài và ảnh đại diện. Không đưa fixture vào theme hoặc cài trên site thật.

Đặt các biến môi trường trước khi chạy:

- `LK_DISPOSABLE_WP=1`
- `LK_WP_ROOT`: đường dẫn WordPress riêng.
- `LK_TEST_URL`: URL của bản riêng, ví dụ `http://127.0.0.1:18987`.
- `LK_TEST_PASSWORD`: mật khẩu tự chọn chỉ cho fixture, cung cấp qua môi trường; không lưu vào Git.
- `LK_PLAYWRIGHT_PATH`: đường dẫn module Playwright nếu không cài ở project.
- `LK_BROWSER`: executable Chromium/Edge (mặc định `/usr/bin/microsoft-edge`).

Chạy trên **database mới** theo thứ tự:

```sh
php tests/wordpress-integration.php
node tests/browser.cjs
```

PHP test tạo fixture theo vai trò, kiểm tra quyền WordPress/REST và nâng cấp theme đang hoạt động. Browser test dùng Playwright kiểm tra upload qua HTTP, wp-admin, duyệt bài, dữ liệu kết hợp và giao diện. Các fixture được để lại trong môi trường riêng để xem kết quả; muốn chạy lại đầy đủ thì tạo database kiểm thử mới. Không dùng lại database đã có tác phẩm vì test đếm chính xác 30/31 ảnh. Cài Playwright vào thư mục ngoài repo nếu chỉ chạy kiểm tra tạm thời; Node 18 dùng Playwright 1.51.1 đã được kiểm tra với Edge có sẵn.

## Kết quả ngày 27/10/2026

Môi trường riêng dùng core WordPress 7.1.3 và Astra 4.14.1 có sẵn trên máy, PHP 8.2.29/MySQL 8.4.0 của LocalWP, PHP HTTP server và Edge headless. Không thay theme hoặc dữ liệu của site `baitapnhom1.local`.

Đã qua:

- Author giữ `publish_posts`, `upload_files`, `edit_lk_photos`; không có quyền xuất bản tác phẩm. Administrator/Editor được duyệt và xuất bản.
- REST của Author bị HTTP 403 khi tạo publish/future/private hoặc chuyển pending thành publish; khách bị 401.
- Nâng cấp từ marker 2.2.0 tạo trang còn thiếu; chạy lại giữ ID và nội dung người dùng.
- Khách không có form, Subscriber bị từ chối; URL `lk_upload=pending` tự gõ không hiện thành công.
- Upload liên tiếp hai lần từ trang có thông báo tạo hai ID riêng, đều pending và có ảnh đại diện. Action sạch, POST trên URL thông báo vẫn kiểm tra nonce. File giả JPEG và upload vượt dung lượng bị từ chối. Để kiểm tra riêng giới hạn 12 MB của form, đặt PHP `upload_max_filesize=16M`, `post_max_size=20M`; test cũng chấp nhận PHP chặn sớm khi giới hạn server nhỏ hơn.
- Lưu qua endpoint wp-admin với Author và `post_status=publish` vẫn giữ pending. Editor đọc được hai bài và duyệt được bài qua REST.
- Giả lập lỗi lưu bài và lỗi gắn thumbnail: hiện lỗi, không báo thành công; kiểm tra DB không còn bài/attachment của lần thất bại.
- Một bài đã duyệt + 30 mẫu = 31 thẻ; ảnh thật đứng trước. Lọc tác giả trả đúng 30 mẫu, tìm kiếm không dấu, album và mở/đóng lightbox hoạt động. Trang chủ/form và liên kết gửi ảnh ở 390px không tràn ngang.
- Cú pháp PHP/JS và `git diff --check`.

## Converter for Media 6.7.0

Site thật lúc kiểm tra đang tắt plugin; cấu hình lưu là Pass Thru + `uploads,themes`. Đã đọc cấu hình Nginx, chưa có rule chuyển WebP. Không sửa plugin vendor, URL ảnh, cấu hình hoặc trạng thái plugin của site thật.

Tái hiện trên bản WordPress riêng, cùng plugin và thiết lập:

| Trường hợp | HTTP / nội dung |
|---|---|
| JPG mẫu trực tiếp | 200, `image/jpeg`, 301409 byte |
| Pass Thru trỏ vào JPG mẫu trong theme | 200, `text/html`, 0 byte: ảnh vỡ |
| Pass Thru cho ảnh Media sau khi tạo WebP và refresh loader | 200, `image/webp`, 36658 byte |
| Cùng ảnh Media với `Accept: image/jpeg` | fallback JPG, 200, `image/jpeg`, 57488 byte |
| Chỉ chọn `uploads`, giữ mẫu dùng JPG gốc | 39 thẻ ảnh trang chủ (hero, 31 tác phẩm, album) đều trả body ảnh không rỗng |

WebP được tạo bằng hook chuyển attachment của chính plugin, loader được tạo lại bằng hook `webpc_refresh_loader`, không sửa vendor/loader bằng tay. Khi output directory chưa có lúc loader được sinh, allowed prefixes có thể rỗng; sau bulk conversion cần lưu lại cấu hình/refresh loader. Sau khi có WebP, theme URL vẫn bị loader từ chối, xác nhận hạn chế phạm vi Pass Thru độc lập với lỗi chưa có file WebP.

Cấu hình Pass Thru chỉ cho uploads đã kiểm chứng trên môi trường riêng. **Chưa hoàn thành WebP toàn site LocalWP thật**, chưa áp dụng cấu hình Nginx, chưa kiểm chứng bulk toàn bộ ảnh thật hoặc cache/content negotiation qua Nginx. Muốn chuyển cả ảnh mẫu trong theme, làm theo hướng dẫn nhà phát triển cho Nginx và kiểm tra `Accept`, `Vary`, fallback, srcset, lightbox trước khi công bố. Không thay hàng loạt `.jpg` thành `.webp`.
