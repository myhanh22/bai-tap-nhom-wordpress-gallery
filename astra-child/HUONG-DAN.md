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

## Sửa giao diện

- `front-page.php`: nội dung banner, tên Lăng Kính, giới thiệu và cấu trúc trang chủ.
- `style.css`: màu sắc, chữ và bố cục. Các kiểu trang chủ giới hạn trong `.lk-home`.
- `functions.php`: đăng ký Tác phẩm/Chủ đề, nạp CSS/JS và đọc dữ liệu.
- `js/custom.js`: bộ lọc, tìm kiếm, lightbox, Back-to-top.
- Những trang khác tiếp tục dùng template của Astra. Đây là trang chủ được viết bằng PHP, không phải mẫu Elementor.

## Phạm vi bài mẫu

Đã có giao diện, chủ đề/album, tác giả, lightbox, lazy loading và escaping đầu ra trong template (`esc_html`, `esc_attr`, `esc_url`). Truy vấn chỉ đọc nội dung đã xuất bản, không có form upload công khai hay endpoint tùy chỉnh.

**Chưa có** watermark tự động, tự chuyển ảnh sang WebP, cấu hình plugin bảo mật, đổi URL đăng nhập, giới hạn đăng nhập hay báo cáo quét lỗ hổng. Đây vẫn là các phần nhóm phải làm tiếp theo đề bài. CSS trang login là CSS mẫu; không được nạp vào màn hình đăng nhập trong phiên bản này.

Không chứa database, mật khẩu hoặc `wp-config.php`. Việc kích hoạt theme không thay hoặc xóa nội dung WordPress hiện có. Khi đổi sang theme khác, Tác phẩm sẽ không hiện trong menu quản trị, nhưng vẫn được giữ trong database; kích hoạt lại child theme để truy cập.

## Giấy phép

Mã theme: GPL-2.0-or-later (tương thích Astra/WordPress). Theme cha Astra: GPL-2.0-or-later theo khai báo của theme; cần ghi phiên bản cài thực tế vào bảng kiểm kê. Quyền sử dụng các ảnh gốc cần kiểm tra riêng; giấy phép của mã không tự xác nhận bản quyền ảnh.
