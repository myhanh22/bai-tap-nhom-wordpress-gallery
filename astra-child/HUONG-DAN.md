# Lăng Kính — Mẫu thư viện ảnh Nhóm 1

## Cài trong LocalWP

1. Xuất bản sao lưu website bằng LocalWP → chuột phải website → Export.
2. Start site → WP Admin → Giao diện (Appearance) → Giao diện → Thêm mới.
3. Tìm **Astra** của Brainstorm Force, cài đặt theme cha. Không xóa Astra sau khi cài child theme.
4. Thêm mới → Tải giao diện lên → chọn **lang-kinh-astra-child.zip** → Cài đặt → Kích hoạt.
5. Nếu website đã có thư mục `astra-child`, WordPress có thể hỏi thay thế phiên bản hiện tại: chỉ thay sau khi đã sao lưu phần tùy chỉnh cũ. ZIP này nâng cấp child theme cũ của nhóm.
6. Mở địa chỉ gốc của website, không phải một trang con. Mẫu dùng `front-page.php`, được WordPress tự chọn làm trang chủ, kể cả khi đang đặt trang chủ tĩnh. Không cần nhập database, Elementor hay plugin gallery.
7. Cài đặt → Đường dẫn tĩnh → Lưu thay đổi để cập nhật đường dẫn cho mục Tác phẩm. Cài đặt → Tổng quan → Ngôn ngữ trang web → Tiếng Việt nếu muốn.

## Có gì ngay khi kích hoạt?

- Banner, điều hướng, 30 ảnh mẫu, lưới ảnh, bộ sưu tập theo chủ đề, phần giới thiệu và footer.
- Bộ lọc chủ đề, lọc tác giả; tìm theo tên tác phẩm hoặc tác giả, hỗ trợ nhập không dấu.
- Lightbox: xem ảnh, ảnh trước/sau, phím mũi tên, Esc để đóng; giữ bàn phím trong hộp thoại bằng dialog của trình duyệt.
- Giao diện điện thoại, nút về đầu trang, lazy loading; ảnh thật dùng thêm srcset của WordPress.
- Không tải font, thư viện JS hay ảnh từ bên ngoài. Ảnh mẫu lấy từ `media-assets` của repo nhóm.
- Tên ảnh mẫu được đặt để minh họa. Không gán tác giả nhiếp ảnh giả; tất cả ghi “Bộ sưu tập mẫu”. Nhóm cần kiểm tra quyền sử dụng từng ảnh trước khi công bố.

## Thêm tác phẩm thật từ trang quản trị

1. **Tác phẩm → Chủ đề / Album**: tạo chủ đề như Phong cảnh, Chân dung, Đời sống. Một tác phẩm có thể thuộc nhiều chủ đề.
2. **Tác phẩm → Thêm mới**: nhập tên, thêm mô tả, chọn **Ảnh tác phẩm / Featured image**, chọn chủ đề, rồi Đăng.
3. Chọn **Tác giả** trong phần cài đặt bài viết. Nếu cần nhiều tác giả, quản trị viên tạo người dùng bằng Người dùng → Thêm mới và đặt tên hiển thị phù hợp. Đây là tác giả quản lý bài trong WordPress; chỉ dùng tên nhiếp ảnh gia khi đã xác nhận quyền ghi công.
4. Khi có ít nhất một tác phẩm đã đăng có ảnh đại diện, trang chủ tự thay toàn bộ bộ mẫu bằng tác phẩm thật. Bài nháp và bài chưa có ảnh đại diện không được trưng bày.
5. Ảnh đầu tiên trên trang chủ là tác phẩm được đăng gần đây nhất có ảnh đại diện. Bộ sưu tập, số ảnh và danh sách tác giả tự lấy từ dữ liệu.
6. Điền văn bản thay thế cho ảnh trong Thư viện Media để hỗ trợ người dùng trình đọc màn hình.

Lưu ý: ảnh mẫu nằm trong theme để có giao diện ngay; chúng không tự nhập vào Media. Nên tải tác phẩm thật lên Media qua giao diện quản trị. Không cần sửa code để thay ảnh.

## Gửi tác phẩm từ frontend

- Child theme tạo trang **Đăng tác phẩm** tại `/dang-tac-pham/` khi được kích hoạt. Trang yêu cầu đăng nhập WordPress.
- Quản trị viên tạo tài khoản cho người gửi tại **Người dùng → Thêm mới**, chọn vai trò **Tác giả (Author)**. Không bật đăng ký tài khoản công khai.
- Form nhận JPG, PNG hoặc WebP, tối đa 12 MB; tiêu đề được lọc, có nonce chống CSRF và kiểm tra quyền `upload_files`/`edit_posts`.
- Ảnh được lưu trong Media Library, tạo bản ghi attachment và CPT `lk_photo` trong database WordPress, đặt làm ảnh đại diện và trạng thái **Chờ duyệt**. Quản trị viên duyệt trong **Tác phẩm** trước khi ảnh xuất hiện công khai.
- Không cần tự tạo bảng SQL. WordPress lưu nội dung trong các bảng posts/postmeta/taxonomy hiện có. Xuất SQL chỉ khi cần bàn giao bản sao đã loại dữ liệu tài khoản/cấu hình nhạy cảm.
- Đăng nhập dùng màn hình WordPress mặc định; người gửi quay lại trang gửi ảnh sau khi đăng nhập.

## Watermark và WebP

- **Image Watermark**: vào **Settings → Watermark**, bật `Automatic Watermarking`, `Front-end Watermarking` và `Manual Watermarking`; chọn `All uploads` vì form tải ảnh tạo attachment trước khi gắn vào tác phẩm. Chọn các kích thước `thumbnail`, `medium`, `large`, `full` và `lk-gallery`; bật backup ảnh gốc để có thể phục hồi. Dùng watermark chữ “© Lăng Kính · Nhóm 1” hoặc logo nhóm có quyền sử dụng; đặt ở góc dưới, opacity khoảng 55–65%.
- **Converter for Media**: vào **Settings → Settings → Converter for Media**, giữ output WebP, bật thư mục `uploads` và `themes` để tối ưu ảnh Media và ảnh mẫu trong child theme. LocalWP đang chạy Nginx; chọn `Pass Thru` nếu plugin báo chưa có rewrite, rồi kiểm tra diagnostics trong trang plugin. Chạy bulk optimization cho ảnh Media cũ.
- Watermark ảnh cũ chỉ sau khi bật backup và thử trên một ảnh mẫu. Không tải logo watermark lên như ảnh tác phẩm.
- Xem phiên bản/license trong [PLUGIN-LICENSES.md](../PLUGIN-LICENSES.md). Cập nhật bảng nếu site cài phiên bản khác.

## Sửa giao diện

- `front-page.php`: nội dung banner, tên Lăng Kính, giới thiệu và cấu trúc trang chủ.
- `style.css`: màu sắc, chữ và bố cục. Các kiểu trang chủ giới hạn trong `.lk-home`.
- `functions.php`: đăng ký Tác phẩm/Chủ đề, nạp CSS/JS và đọc dữ liệu.
- `js/custom.js`: bộ lọc, tìm kiếm, lightbox, Back-to-top.
- Những trang khác tiếp tục dùng template của Astra. Đây là trang chủ được viết bằng PHP, không phải mẫu Elementor.

## Phạm vi bài mẫu

Đã có giao diện, chủ đề/album, tác giả, lightbox, lazy loading, escape đầu ra trong template (`esc_html`, `esc_attr`, `esc_url`) và form upload chỉ dành cho tài khoản được phép. Ảnh mới được lưu ở trạng thái chờ duyệt.

Watermark/WebP được cấu hình qua plugin trên site LocalWP. **Chưa có** cấu hình hardening, đổi URL đăng nhập, giới hạn đăng nhập hay báo cáo quét lỗ hổng. CSS trang login là CSS mẫu; không được nạp vào màn hình đăng nhập trong phiên bản này.

Không chứa database, mật khẩu hoặc `wp-config.php`. Việc kích hoạt theme không thay hoặc xóa nội dung WordPress hiện có. Khi đổi sang theme khác, Tác phẩm sẽ không hiện trong menu quản trị, nhưng vẫn được giữ trong database; kích hoạt lại child theme để truy cập.

## Giấy phép

Mã theme: GPL-2.0-or-later (tương thích Astra/WordPress). Theme cha Astra: GPL-2.0-or-later theo khai báo của theme; cần ghi phiên bản cài thực tế vào bảng kiểm kê. Quyền sử dụng các ảnh gốc cần kiểm tra riêng; giấy phép của mã không tự xác nhận bản quyền ảnh.
