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

try {
    $username = filter_var($requestBody['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($requestBody['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    require_once '../../private/database/userData.php';

    $originUser = userData::getDataFromUsername($username);

    if(!$originUser)
        responseJsonData("Không tìm thấy tài khoản", 404);

    if (password_verify($password, $originUser['password'])) {
        $hash_respond = userData::findByHashAndUsername($username);

        if(!$hash_respond) {
            $otp = userData::addNewUserAgentWithOtp($username);
            require_once '../../private/service/mailSender.php';
            
            sendMail($originUser['email'], $otp['id'], $otp['otp']);
            responseJsonData("Phát hiện đăng nhập trên thiết bị mới, vui lòng kiểm tra email", 400);
        }

        if($hash_respond[0]['otp']) {
            responseJsonData("Kiểm tra email để xác minh thiết bị đi :v", 400);
        }

        require_once '../../private/service/jwtSigner.php';

        responseJsonData(jwtSigner::createToken($originUser['email']));
    } else {
        responseJsonData("Mật khẩu sai", 401);
    }

} catch (Exception $e) {
    responseJsonData($e->getMessage(), 500);
}
?>