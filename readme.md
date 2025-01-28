# Cách Chạy Ứng Dụng & Cấu Hình 

### Phiên Bản
- php version: 8.2.26
- composer version: 2.5.5

### Cấu Hình (Trong folder **private/service**)
#### Các thông số trong .template.env
- **client_secret**: client_secret trong google oath2
- **database_host và database_port**: host và port của hệ quản trị cơ sở dữ liệu
- Tương tự với **database_user**, **database_password**, **database_name**
- **jwt_secret**: khóa bí mật để kí và xác thực các token jwt
- Cấu hình **smtp server** và **hmac key**
- ***Lưu ý khi triển khai dùng file .env chứ không phải .template.env***

#### Các thông số trong config.json

### Basic Url
- /login?redirect=...
- /login/callback?token=...
- /login/username-callback?...
- /login/google-callback?...
- /account-info

<!-- ----------------------------------------------- -->
# Triển Khai Ứng Dụng
### Môi Trường Thử Nghiệm

### Kiểm Thử

### Sản Phẩm

##### [Cấu hình docker-compose](/sample_docker-compose%20configuration/readme.md):
##### Chạy source:
- git clone dự án về
- cài đặt php và composer đúng với phiên bản
- tạo file .env và config.json trong thư mục config và cấu hình như hướng dẫn
- composer install & composer start

<!-- ----------------------------------------------- -->
# Thông Tin Khác

### Cách Hoạt Động Của Ứng Dụng
- Tương tác giữa user và hệ thống:
- Quy trình hoạt động của hệ thống:
- Các vấn đề về bảo mật:

### Mã Nguồn
#### Cấu trúc:
- **database_template:** Chứ các template SQL (không dữ liệu)
- **private:** Chứa các module php bên ngoài nội dung render ra web.
    - **database:** Kết nối với cơ sở dữ liệu.
    - **service:** Các dịch vụ (Đọc file .env, file config, ký jwt, call api bên thứ 3,... ).

- **src:** Nội dung render ra web mà user có thể call ngoài trình duyệt.
    - **callback:** Xử lí token và redirect trang gốc sau khi xác thực người dùng.
    - **google-callback:** Tương tự nhưng cho google
    - **username-callback:** Tương tự

#### Quản lí mã nguồn:
- Sử dụng github để quản lí mã nguồn.
- Lưu dữ liệu nhạy cảm trong file .env và dữ liệu mẫu trong .template.env và đảm bảo không bị rò rỉ dữ liệu ra ngoài.
- Dữ liệu trong file config đảm bảo chặt chẽ và có thể lấy từ mọi module trong chương trình.
- Sử dụng CI/CD để tự động kiểm tra mã nguồn và triển khai lên môi trường hoặc khi có sự thay đổi.
- Đảm bảo tài liệu minh bạch, rõ ràng và dễ hiểu.

### Các Thư Viện Phụ Thuộc
- Các thư viện được quản lí bởi **composer** - một công cụ quản lý phụ thuộc (dependency manager) cho PHP.
- **firebase/php-jwt**: Giúp tạo và xác thực token cho người dùng khi xác thực trên hệ thống.

### Thông Tin Hệ Thống
- **Kernel**: `5.15.167.4-microsoft-standard-WSL2`
- **Hệ điều hành**: **GNU/Linux**
- **Kiến trúc**: **x86_64**
- **Nền tảng**: **WSL2**