<?php

header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseJsonData("Api chỉ cho phép yêu cầu POST.", 405); 
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

$requestBody = json_decode(file_get_contents('php://input'), true);

$password = filter_var($requestBody['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

try {
    if(strlen($password) < 4 || strlen($password) > 15) {
        throw new Exception("Mật khẩu từ 4 tới 15 ký tự.");
    }

    require_once '../../private/database/userData.php';
    require_once  '../../private/service/request.php';
    $r = json_decode(request::send('GET', [], 'https://www.googleapis.com/oauth2/v3/userinfo', $requestBody['token']), true);

    if($r['email'] != $requestBody['email']) {
        throw new Exception("Xác thực email thất bại.");
    }

    $password = password_hash($requestBody['password'], PASSWORD_DEFAULT); 

    userData::addNewAccount($requestBody['email'], $requestBody['username'], $password, $requestBody['picture'], $requestBody['name']);

    // responseJsonData($requestBody);
    require_once  '../../private/service/jwtSigner.php';
    responseJsonData(jwtSigner::createToken($requestBody['email']));
} catch (Exception $e) {
    responseJsonData($e->getMessage(), 500);
}
?>