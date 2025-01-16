<?php
    $username = $_POST['username'];  
    $password = $_POST['password'];  
    $remember = isset($_POST['rememberMe']);  

    echo "Tài khoản: " . $username . "<br>";
    echo "Mật khẩu: " . $password . "<br>";
    
    if ($remember) {
        echo "Checkbox được chọn (Ghi nhớ tài khoản).";
    } else {
        echo "Checkbox không được chọn (Không ghi nhớ tài khoản).";
    }
?>