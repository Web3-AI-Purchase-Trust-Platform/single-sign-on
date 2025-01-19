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

                    header("Location: " . $state . "?token=" . $token);
                } else {
                    header("Location: /register?token=" . $token . "&redirect=" . $state);
                    exit();
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
