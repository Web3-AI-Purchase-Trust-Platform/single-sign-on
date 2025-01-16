<?php
    require '../../private/service/configLoader.php';

    $google_oath2 = jsonLoader::getConfig('google-oath2');
    $google_oath_callback = "https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email&response_type=code&client_id=". $google_oath2['client-id'] ."&redirect_uri=". $google_oath2['call-back-url'] ."&state=xyz123";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Đổi favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/1791/1791961.png" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
            user-select: none;
        }
    </style>
</head>
<body 
    style="
        height: 100dvh;
        display: flex;
        justify-self: center;
        align-items: center;
        background-color: #eeeee4;
        background-image: url('https://img.freepik.com/free-photo/christmas-snowy-landscape_1048-3491.jpg?semt=ais_hybrid');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat; 
    ">

    <div
        style = "
            box-sizing: border-box;
            padding: 20px 75px;
            border-radius: 5px;
            background-color: white;
            min-height: 80%;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.1);
        "
    >
        <!-- Tiêu để của containter -->
        <div
            style = "
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            "
        >
            <img src="https://cdn-icons-png.flaticon.com/128/1791/1791961.png" width="80" height="80" alt="">
            <h2
                style = "
                    margin-top: 10px;
                "
            >Single Sign-On</h2>
        </div>
        
        <!-- Các trường input -->
        <Form
            style = "
                margin-top: 30px;
                max-width: 450px;
                min-width: 300px;
            "
            method="post"
            action="/login/username-callback"
        >
            <label for="exampleFormControlInput1" class="form-label"><b>Tài khoản</b></label>
            <input autocomplete="off" required  name="username" type="text" class="form-control" placeholder="------">

            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Mật Khẩu</b></label>
            <input autocomplete="off" required  name="password" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">
        
            <div class="form-check" style="margin-top: 10px;">
                <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Ghi nhớ tài khoản
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3" id="login">Đăng nhập</button>
        </Form>

        <hr style="margin-top: 25px;">

        <!-- Đăng nhập bên thứ ba -->
        <div style="
            text-align: center;
        ">
            <p style="color: #777; font-size: 14px; transform: translateY(-60%);">hoặc đăng nhập với</p>

            <div
                style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 30px;
                "
            >
                <a href="<?php echo $google_oath_callback; ?>">
                    <img id="google-login" style="cursor: pointer;" src="https://cdn-icons-png.flaticon.com/128/2702/2702602.png" width="40" height="40" alt="">
                </a>
                <img style="cursor: pointer;" src="https://cdn-icons-png.flaticon.com/128/145/145802.png" width="40" height="40" alt="">
            </div>
        </div>
    </div>    
</body>
</html>


