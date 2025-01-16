# Cách Chạy Ứng Dụng & Cấu Hình 

### Phiên Bản
- php version: 8.2.26
- composer version: 2.5.5

### Cấu Hình (Trong File **private**)
#### Các thông số trong .template.env
- **client_secret**: client_secret trong google oath2
- **database_host và database_port**: host và port của hệ quản trị cơ sở dữ liệu
- Tương tự với **database_user**, **database_password**, **database_name**
- **jwt_secret**: khóa bí mật để kí và xác thực các token jwt
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

<!-- ----------------------------------------------- -->
# Thông Tin Khác

### Cách Hoạt Động Của Ứng Dụng
- Tương tác giữa user và hệ thống:
- Quy trình hoạt động của hệ thống:
- Các vấn đề về bảo mật:

### Mã Nguồn
- Cấu trúc:
- Quản lí mã nguồn:

### Các Thư Viện Phụ Thuộc
- Các thư viện được quản lí bởi **composer** - một công cụ quản lý phụ thuộc (dependency manager) cho PHP.
- **firebase/php-jwt**: Giúp tạo và xác thực token cho người dùng khi xác thực trên hệ thống.

### Thông Tin Hệ Thống
- **Kernel**: `5.15.167.4-microsoft-standard-WSL2`
- **Hệ điều hành**: **GNU/Linux**
- **Kiến trúc**: **x86_64**
- **Nền tảng**: **WSL2**