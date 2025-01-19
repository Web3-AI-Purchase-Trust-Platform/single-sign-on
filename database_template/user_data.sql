-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: mysql
-- Thời gian đã tạo: Th1 19, 2025 lúc 03:12 PM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webAI_userdata`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_data`
--

CREATE TABLE `user_data` (
  `email` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://cdn-icons-png.flaticon.com/128/3135/3135715.png',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Bẫy `user_data`
--
DELIMITER $$
CREATE TRIGGER `check_email_before_insert` BEFORE INSERT ON `user_data` FOR EACH ROW BEGIN
    -- Kiểm tra xem email có đúng định dạng hay không
    IF NOT NEW.email REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email không hợp lệ!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_password_length_before_insert` BEFORE INSERT ON `user_data` FOR EACH ROW BEGIN
    -- Kiểm tra độ dài mật khẩu từ 8 đến 15 ký tự
    IF LENGTH(NEW.password) < 8 OR LENGTH(NEW.password) > 15 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Mật khẩu phải có độ dài từ 8 đến 15 ký tự!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_username_before_insert` BEFORE INSERT ON `user_data` FOR EACH ROW BEGIN
    -- Kiểm tra độ dài username từ 4 đến 8 ký tự
    IF LENGTH(NEW.username) < 4 OR LENGTH(NEW.username) > 8 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Username phải có độ dài từ 4 đến 8 ký tự!';
    END IF;

    -- Kiểm tra username không chứa ký tự đặc biệt (chỉ cho phép chữ cái và số)
    IF NEW.username REGEXP '[^a-zA-Z0-9]' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Username không được chứa ký tự đặc biệt!';
    END IF;
END
$$
DELIMITER ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `username` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
