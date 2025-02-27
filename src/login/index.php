<?php
    require '../../private/service/configLoader.php';

    if (isset($_GET['redirect'])) {
        $redirect = filter_var($_GET['redirect'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        require_once '../../private/service/configLoader.php';

        $redirect_arr = jsonLoader::getConfig('redirect_url');

        if(!in_array($redirect, $redirect_arr))
            header("Location: /");
    }
    else {
        header("Location: /");
    }

    $google_oath2 = jsonLoader::getConfig('google-oath2');
    $google_oath_callback = "https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email&response_type=code&client_id=". $google_oath2['client-id'] ."&redirect_uri=". $google_oath2['call-back-url'] ."&state=" . $redirect;

    $forgot_bro = "https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email&response_type=code&client_id=". $google_oath2['client-id'] ."&redirect_uri=". $google_oath2['call-back-url'] ."&state=" . "forgot";
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
    redirect = "<?php echo $redirect ?>"

    forgot = "<?php echo $forgot_bro ?>"
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="centeredModalLabel">
                        <img src="https://cdn-icons-png.flaticon.com/128/9195/9195785.png" width="35" height="35" alt="">
                        Thông Báo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!--  -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
            id="loginForm"
        >
            <label for="exampleFormControlInput1" class="form-label"><b>Tài khoản</b></label>
            <input id="username" autocomplete="off" required  name="username" type="text" class="form-control" placeholder="------">

            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Mật Khẩu</b></label>
            <input id="password" autocomplete="off" required  name="password" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">
        
            <div class="form-check" style="margin-top: 10px; display: flex; justify-content: space-between; width: 100%; align-items: center;">
                <div style="margin-right: 15px">
                    <img src="https://cdn-icons-png.flaticon.com/128/10448/10448239.png" width="20" height="20" alt="">
                    <a href = "<?php echo $forgot_bro ?>" style="color: #777; font-size: 14px; transform: translateY(-60%); cursor: pointer">
                        quên mật khẩu
                    </a>
                </div>

                <a href="<?php echo $google_oath_callback; ?>" style="font-size: 0.8rem">Đăng Kí Tài Khoản</a>
            </div>

            <button id="submit-button" type="submit" class="btn btn-primary w-100 mt-3" id="login">Đăng nhập</button>
        </Form>

        <hr style="margin-top: 25px;">

        <!-- Đăng nhập bên thứ ba -->
        <div style="
            text-align: center;
        "
        id="third"
        >
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

    <script>
        function wait(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function disable_function(dis) {
            document.getElementById('username').disabled=dis;
            document.getElementById('password').disabled=dis;
            document.getElementById('submit-button').disabled=dis;

            if(dis === true) {
                document.getElementById('submit-button').innerHTML = `
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
            } else {
                document.getElementById('submit-button').innerHTML = `
                    Đăng Nhập
                `
            }
        }

        async function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if(username.length === 0 || password.length === 0) {
                throw new Error("Nhập thiếu thông tin")
            }

            const res = await fetch(`/api/login.php`, {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json', 
                },
                body: JSON.stringify({
                    username: username, 
                    password: password, 
                })
            })
            .then(response => {   
                return response.json();
            })

            if(res.code != 200)
                throw new Error(res.message)
            
            return res
        }

        const button = document.getElementById('submit-button')

        button.addEventListener('click', async function (e) {
            e.preventDefault()

            disable_function(true)

            try {
                const res = await login()

                document.getElementById('loginForm').innerHTML = `
                    <div style="
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-top: 65px;
                        flex-direction: column;
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/190/190411.png" width="60px" height="60px" alt="">
                        <h4 style="margin-top: 25px">Đăng Nhập Tài Khoản Thành Công</h3>
                        <p style="color: #BFBBA9">tự động điều hướng sau <span id="cd">3</span> giây</p>
                    </div>
                `;

                document.getElementById('third').innerHTML = ``

                await wait(1000)
                document.getElementById('cd').innerHTML = 2;
                await wait(1000)
                document.getElementById('cd').innerHTML = 1;
                await wait(1000)
                document.getElementById('cd').innerHTML = 0;

                window.location.replace(`${document.body.getAttribute('redirect')}?token=${res['message']}`);
            } catch (e) {
                document.getElementById('modalBody').textContent = e.message
            } finally {
                disable_function(false)
            }

            // Hiển thị modal
            const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        })
    </script>
</body>
</html>


