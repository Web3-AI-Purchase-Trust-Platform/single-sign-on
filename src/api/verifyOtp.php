<?php

header('Content-Type: application/json');
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responseJsonData("Api chỉ cho phép yêu cầu GET.", 405); 
}

// Cấu hình bắt lỗi Exception
set_exception_handler(function ($exception) {
    http_response_code(500);
    echo json_encode([
        'date' => date('Y-m-d H:i:s'),
        'code' => "500",
        'message' => $exception->getMessage(),
        // 'file' => $exception->getFile(),
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
        // 'file' => $file,
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
    $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $otp = filter_var($_GET['otp'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    require_once '../../private/database/userData.php';
    require_once '../../private/service/envLoader.php';

    $agent = userData::getAgentById($id);

    $user_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if($agent) {
        $agent = $agent[0];

        $hmac_key = envLoader::getEnv('hmac_key');
        $hash = hash_hmac('sha256', $user_agent, $hmac_key);

        // if($hash == $agent['hash_key']) {
        if($agent['otp'] == 0)
            responseJsonData("OTP đã được xác thực", 400);

        if($otp != $agent['otp'])
            responseJsonData("OTP không hợp lệ", 400);

        userData::verifyOtp($id);
        responseJsonData("Xác minh Otp thành công", 200);
        // }

        responseJsonData('Vui lòng xác minh otp trên cùng thiết bị hoặc trình duyệt', 400);
    } else {
        responseJsonData("otp not found", 404);
    }
} catch (Exception $e) {
    responseJsonData($e->getMessage(), 500);
}
?>