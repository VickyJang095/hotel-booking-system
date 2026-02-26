# 🏨 Tripto — Hotel Booking System

> Đồ án cơ sở — Trường Đại học Phenikaa

---

## 📋 Giới thiệu

**Tripto** là hệ thống đặt phòng khách sạn trực tuyến được xây dựng trong khuôn khổ môn **Đồ án cơ sở** tại Trường Đại học Phenikaa. Dự án mô phỏng các chức năng cốt lõi của một nền tảng đặt phòng thực tế, bao gồm xác thực người dùng, tìm kiếm và đặt phòng, quản lý đặt chỗ, và giao diện quản trị.

---

## 🚀 Tính năng chính

- **Xác thực người dùng** — Đăng nhập / đăng ký qua OTP gửi email
- **Tìm kiếm khách sạn** — Lọc theo địa điểm, ngày nhận/trả phòng, số khách
- **Chi tiết khách sạn** — Xem ảnh, mô tả, tiện ích, đánh giá
- **Đặt phòng** — Chọn phòng, xác nhận và thanh toán
- **Quản lý tài khoản** — Hồ sơ cá nhân, lịch sử chuyến đi, danh sách yêu thích
- **Đánh giá** — Viết và xem đánh giá khách sạn
- **Quản trị (Admin)** — Quản lý khách sạn, đặt phòng, người dùng
- **Hotel Owner** — Giao diện dành cho chủ khách sạn quản lý tài sản

---

## 🛠️ Công nghệ sử dụng

| Thành phần | Công nghệ |
|---|---|
| Backend | Laravel (PHP) |
| Frontend | Blade Template, Tailwind CSS |
| Database | MySQL |
| Authentication | OTP qua Email (Mailtrap) |
| Version Control | Git / GitHub |

---

## ⚙️ Cài đặt và chạy dự án

### Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL

### Các bước cài đặt

```bash
# 1. Clone repository
git clone https://github.com/VickyJang095/hotel-booking-system.git
cd hotel-booking-system

# 2. Cài đặt dependencies PHP
composer install

# 3. Cài đặt dependencies JavaScript
npm install && npm run build

# 4. Tạo file môi trường
cp .env.example .env
php artisan key:generate

# 5. Cấu hình database trong file .env
DB_DATABASE=hotel_booking
DB_USERNAME=root
DB_PASSWORD=

# 6. Cấu hình mail (OTP) trong file .env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password

# 7. Chạy migration và seeder
php artisan migrate --seed

# 8. Khởi động server
php artisan serve
```

Truy cập tại: `http://localhost:8000`

---

## 📁 Cấu trúc thư mục

```
hotel-booking-system/
├── app/
│   ├── Http/Controllers/     # Controllers xử lý logic
│   ├── Models/               # Eloquent Models
│   └── Mail/                 # Mailable classes (OTP email)
├── database/
│   ├── migrations/           # Cấu trúc bảng CSDL
│   └── seeders/              # Dữ liệu mẫu
├── resources/
│   ├── views/                # Blade templates
│   └── css/, js/             # Assets frontend
├── routes/
│   └── web.php               # Định nghĩa routes
└── public/                   # Entry point
```

---

## 👥 Nhóm thực hiện

| Họ và tên | Mã sinh viên | Vai trò |
|---|---|---|
|  |  |  |
|  |  |  |
|  |  |  |

> *(Điền thông tin thành viên nhóm vào bảng trên)*

---

## 🏫 Thông tin môn học

- **Môn học:** Đồ án cơ sở
- **Trường:** Đại học Phenikaa
- **Năm học:** 2024 – 2025

---

## 📄 Giấy phép

Dự án được thực hiện cho mục đích học thuật tại Trường Đại học Phenikaa.
