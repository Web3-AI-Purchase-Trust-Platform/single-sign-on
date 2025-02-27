<?php

header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    responseJsonData("Api chỉ cho phép yêu cầu PATCH.", 405); 
}

// Cấu hình bắt lỗi Exception
set_exception_handler(function ($exception) {
    http_response_code(500);
    echo json_encode([
        'date' => date('Y-m-d H:i:s'),
        'code' => "500",
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ]);
});

// Cấu hình bắt lỗi PHP Warning, Notice
set_error_handler(function ($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'date' => date('Y-m-d H:i:s'),
        'code' => "500",
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
    exit();
});

function responseJsonData($message, $code = 200) {
    http_response_code($code);

    $responseData = [
        'date' => date('Y-m-d H:i:s'),
        'code' => $code,
        'message' => $message,
        'path' => $_SERVER["REQUEST_URI"]
    ];

    echo json_encode($responseData);

    exit;
}

try {
    $token = filter_var($_GET['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = filter_var($_GET['newPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (strlen($pass) < 4 || strlen($pass) > 15) {
        throw new Exception("Mật khẩu phải từ 4 đến 15 ký tự");
    }

    require_once '../../private/service/jwtSigner.php';
    $email = jwtSigner::validateToken($token)->sub;

    $hash = password_hash($pass, PASSWORD_DEFAULT); ;

    require_once '../../private/database/userData.php';
    userData::changePasswordByEmail($email, $hash);

    responseJsonData("Đổi mật khẩu thành công");
} catch (Exception $e) {
    responseJsonData($e->getMessage(), 500);
}
?>