<?php
    if (isset($_GET['token'])) {
        $token = filter_var($_GET['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        require_once  '../../private/service/request.php';

        $r = json_decode(request::send('GET', [], 'https://www.googleapis.com/oauth2/v3/userinfo', $token), true);
    } else {
        header("Location: /login");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Kí Tài Khoản</title>
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
    style=
    "
        height: 100dvh;
        display: flex;
        justify-self: center;
        align-items: center;
        background-color: #eeeee4;
        background-image: url('https://img.freepik.com/free-photo/christmas-snowy-landscape_1048-3491.jpg?semt=ais_hybrid');
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat; 
    "
>
    <div
        style = "
            box-sizing: border-box;
            padding: 20px 75px;
            border-radius: 5px;
            background-color: white;
            min-height: 80%;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        "
    >   
        <a href="/login">
            <img style="
                position: absolute;
                top: 15px;
                left: 15px;
                cursor: pointer;
            " src="https://cdn-icons-png.flaticon.com/128/93/93634.png" width="25" height="25" alt="">
        </a>

        <div
            style="
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                margin-top: 15px;
            "
        >
            <img style="border-radius: 100%;" src=<?php echo $r['picture'] ?> width="100" height="100" alt="">
            <img style="border-radius: 100%;" src="https://cdn-icons-png.flaticon.com/128/10989/10989754.png" width="70" height="70" alt="">
            <img src="https://cdn-icons-png.flaticon.com/128/1791/1791961.png" width="100" height="100" alt="">
        </div>

        <h5 style="margin-top: 30px; word-wrap: break-word; max-width: 350px;">Đăng kí với tư cách <strong><?php echo $r['name'] ?></strong></h4>

        <hr style="margin-top: 5px;">
        <!-- Các trường input -->
        <Form
            style = "
                margin-top: 10px;
                max-width: 450px;
                min-width: 300px;
                font-size: 0.9rem;
            "
            method="post"
            id="registerForm"
        >
            <label for="exampleFormControlInput1" class="form-label"><b>Tài khoản</b></label>
            <input autocomplete="off" required id="username"  name="username" type="text" class="form-control" placeholder="------">

            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Mật Khẩu</b></label>
            <input autocomplete="off" required id="password"  name="password" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">
        
            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Nhập Lại Mật Khẩu</b></label>
            <input autocomplete="off" required id="rePassword"  name="rePassword" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">

            <button type="submit" class="btn btn-primary w-100 mt-4" id="login">Đăng Kí</button>
        </Form>
    </div>
        
    <script>
        const form = document.getElementById('registerForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn hành vi mặc định của form (không gửi request)

            // Thu thập dữ liệu từ các trường input
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('rePassword').value;

            // In dữ liệu ra console
            console.log({
                username: username,
                password: password,
                confirmPassword: confirmPassword,
            });

            // Hiển thị thông báo hoặc xử lý tiếp nếu cần
            alert('Dữ liệu đã được in ra console!');
        });
    </script>
</body>
</html>