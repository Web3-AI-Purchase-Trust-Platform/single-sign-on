<?php
    if (isset($_GET['state'], $_GET['code'], $_GET['scope'])) {
        $state = filter_var($_GET['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $code = filter_var($_GET['code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $scope = filter_var($_GET['scope'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($state) && !empty($code) && !empty($scope)) {
            require_once  '../../../private/service/envLoader.php';
            require_once  '../../../private/service/configLoader.php';

            $client_secrect = envLoader::getEnv('client_secret');
            $redirect_uri = jsonLoader::getConfig('google-oath2')['call-back-url'];
            $grant_type = jsonLoader::getConfig('google-oath2')['grant_type'];
            $client_id = jsonLoader::getConfig('google-oath2')['client-id'];

            require_once  '../../../private/service/request.php';

            $data = [
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secrect,
                'redirect_uri' => $redirect_uri,
                'grant_type' => $grant_type
            ];

            try {
                $r = json_decode(request::send('POST', $data, 'https://oauth2.googleapis.com/token'), true);

                $token = $r['access_token'];

                $r = json_decode(request::send('GET', [], 'https://www.googleapis.com/oauth2/v3/userinfo', $r['access_token']), true);
            
                // Array ( 
                //     [sub] => 107053002667030459020 
                //     [name] => MK 
                //     [given_name] => MK 
                //     [picture] => https://lh3.googleusercontent.com/a/ACg8ocImvdcQGu_U9Ck2Mt4n0D7W2FoDJcGZKMJ4KxJaBUWPMYmBnII=s96-c 
                //     [email] => qscvdefb@gmail.com [email_verified] => 1 
                // )

                require_once '../../../private/database/userData.php';

                $result = userData::existByEmail($r["email"]);

                if($result) {
                    require_once '../../../private/service/jwtSigner.php';
                    $token = jwtSigner::createToken($r["email"]);
                    if($state != "forgot") {
                        header("Location: " . $state . "?token=" . $token);
                    } 

                    $data = userData::existByEmail($r["email"])['username'];
                } else {
                    header("Location: /register?token=" . $token . "&redirect=" . $state);
                }
            } catch (Exception $e) {
                header("Location: /login?redirect=" . $state);
            }
        } else {
            header("Location: /login?redirect=" . $state);
        }
    } else {
        header("Location: /login?redirect=" . $state);
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
    email="<?php echo $r['email'] ?>"
    name="<?php echo $r['name'] ?>"
    picture = "<?php echo $r['picture'] ?>"
    redirect = "<?php echo $redirect ?>"
    token = "<?php echo $token ?>"
    user = "<?php echo $data ?>"

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
            position: relative;
        "
    >   
        <a href="/login?redirect=<?php echo $redirect ?>">
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

        <h5 style="margin-top: 30px; word-wrap: break-word; max-width: 350px;"><strong>Quên mật khẩu</strong></h4>

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
            <input disabled value="<?php echo $data ?>" autocomplete="off" required id="username"  name="username" type="text" class="form-control">

            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Mật Khẩu</b></label>
            <input autocomplete="off" required id="password"  name="password" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">
        
            <label for="inputPassword5" class="form-label" style="margin-top: 20px;"><b>Nhập Lại Mật Khẩu</b></label>
            <input autocomplete="off" required id="rePassword"  name="rePassword" type="password" class="form-control" aria-describedby="passwordHelpBlock" placeholder="**********">

            <button id="submit-button" type="submit" class="btn btn-primary w-100 mt-4">
                Xác nhận
            </button>
        </Form>
    </div>

    <script>
        function wait(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function disableButton(dis) {
            document.getElementById('password').disabled=dis;
            document.getElementById('rePassword').disabled=dis;
            document.getElementById('submit-button').disabled=dis;

            if(dis === true) {
                document.getElementById('submit-button').innerHTML = `
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
            } else {
                document.getElementById('submit-button').innerHTML = `
                    Xác nhận
                `
            }
        }

        async function register() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('rePassword').value;
            const email = document.body.getAttribute('email');
            const name = document.body.getAttribute('name');
            const picture = document.body.getAttribute('picture');
            const token = document.body.getAttribute('token');

            if(password !== confirmPassword)
                throw new Error("Mật khẩu không khớp")

            const res = await fetch(`/api/changePassword.php?token=${token}&newPassword=${password}`, {
                method: 'PATCH', 
                headers: {
                    'Content-Type': 'application/json', 
                },
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
            e.preventDefault();

            try {
                disableButton(true)
                await wait(1000)
                const res = await register()
                document.getElementById('registerForm').innerHTML = `
                    <div style="
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-top: 65px;
                        flex-direction: column;
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/190/190411.png" width="60px" height="60px" alt="">
                        <h4 style="margin-top: 25px">Đổi Mật Khẩu Thành Công</h3>
                        <p style="color: #BFBBA9">tự động điều hướng sau <span id="cd">3</span> giây</p>
                    </div>
                `;
                await wait(1000)
                document.getElementById('cd').innerHTML = 2;
                await wait(1000)
                document.getElementById('cd').innerHTML = 1;
                await wait(1000)
                document.getElementById('cd').innerHTML = 0;
                
                console.log(res);
                window.location.replace(`${document.body.getAttribute('redirect')}?token=${res['message']}`);
            } catch (e) {
                document.getElementById('modalBody').textContent = e.message
            } finally {
                disableButton(false)
            }
            
            // Hiển thị modal
            const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        })
    </script>
</body>
</html>
