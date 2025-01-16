<?php
    if (isset($_GET['state'], $_GET['code'], $_GET['scope'])) {
        $state = filter_var($_GET['state'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $code = filter_var($_GET['code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $scope = filter_var($_GET['scope'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($state) && !empty($code) && !empty($scope)) {
            require '../../../private/service/envLoader.php';
            require '../../../private/service/configLoader.php';

            $client_secrect = envLoader::getEnv('client_secret');
            $redirect_uri = jsonLoader::getConfig('google-oath2')['call-back-url'];
            $grant_type = jsonLoader::getConfig('google-oath2')['grant_type'];
            $client_id = jsonLoader::getConfig('google-oath2')['client-id'];

            require '../../../private/service/request.php';

            $data = [
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secrect,
                'redirect_uri' => $redirect_uri,
                'grant_type' => $grant_type
            ];

            try {
                $r = json_decode(request::send('POST', $data, 'https://oauth2.googleapis.com/token'), true);
                
                $r = json_decode(request::send('GET', [], 'https://www.googleapis.com/oauth2/v3/userinfo', $r['access_token']), true);
            
                header("Location: /login/callback?email=" . $r['email']);
            } catch (Exception $e) {
                header("Location: /login");
            }
        } else {
            header("Location: /login");
        }
    } else {
        header("Location: /login");
    }
?>
