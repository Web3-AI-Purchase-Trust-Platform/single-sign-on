<?php

header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseJsonData("Only POST method is allowed", 405); 
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

try {
    if (strlen($requestBody['password']) < 8 || strlen($requestBody['password']) > 15) {
        throw new Exception("Mật khẩu phải có độ dài từ 8 đến 15 ký tự!");
    }
    
    if (strlen($requestBody['username']) < 4 || strlen($requestBody['username']) > 10) {
        throw new Exception("Tên tài khoản có độ dài 4 - 10 ký tự.");
    }    

    require_once '../../private/database/userData.php';
    require_once  '../../private/service/request.php';
    $r = json_decode(request::send('GET', [], 'https://www.googleapis.com/oauth2/v3/userinfo', $requestBody['token']), true);

    $requestBody['password'] = password_hash($requestBody['password'], PASSWORD_DEFAULT); 

    userData::addNewAccount($requestBody['email'], $requestBody['username'], $requestBody['password'], $requestBody['picture'], $requestBody['name']);

    // responseJsonData($requestBody);
    require_once  '../../private/service/jwtSigner.php';
    responseJsonData(jwtSigner::createToken($requestBody['email']));
} catch (Exception $e) {
    responseJsonData($e->getMessage(), 500);
}
?>