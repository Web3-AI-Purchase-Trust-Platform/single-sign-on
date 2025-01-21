<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload Composer

require_once __DIR__ . "/envLoader.php";

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function sendMail($to, $id, $code) {
    require  __DIR__ . '/../../vendor/autoload.php';

    $mail = new PHPMailer(true);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $current_url = $protocol . $host;

    $url = $current_url . "/api/verifyOtp.php?id=" . $id . "&otp=" . $code;

    $ip = getUserIP();

    try {
        $mail->CharSet = 'UTF-8';
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = envLoader::getEnv('smtp_host'); 
        $mail->SMTPAuth = true;
        $mail->Username = envLoader::getEnv('smtp_username'); 
        $mail->Password = envLoader::getEnv('smtp_password'); 
        $mail->SMTPSecure = envLoader::getEnv('smtp_secure'); 
        $mail->Port = envLoader::getEnv('smtp_port'); 

        // Thông tin người gửi
        $mail->setFrom(envLoader::getEnv('smtp_username'), 'SSO Service');
        $mail->addAddress($to); 

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Phát hiện đăng nhập trên thiết bị mới';
        $mail->Body = '
            <html>
                <head>
                    <style>
                        /* Định nghĩa các kiểu CSS thông thường */
                        body {
                            font-family: Arial, sans-serif;
                            display: flex;
                            align-items: center;
                            flex-direction: column;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f9;
                        }

                        p.lead {
                            font-size: 18px;
                            font-weight: bold;
                            color: #333;
                            margin: 20px 0;
                        }

                        .btn {
                            padding: 10px 20px;
                            font-size: 16px;
                            background-color: #BE3144;
                            color: black;
                            text-align: center;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                        }

                        .btn:hover {
                            background-color: #872341;
                        }

                        a {
                            color: black !important;
                            text-decoration: none !important;
                        }
                    </style>
                </head>
                <body>
                    <p class="lead">Phát hiện truy cập từ thiết bị mới.</p>
                    <p><strong>from ip:</strong> ' . htmlspecialchars($ip) . ' </p>

                    <a href="' . $url . '" class="btn">Nhấn vào đây để xác minh</a>
                </body>
            </html>
        '
        ;

        // Gửi email
        $mail->send();
    } catch (Exception $e) {
        throw $e;
    }
}