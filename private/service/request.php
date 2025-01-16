<?php
    class request {
        public static function send($method, $data, $url, $token=null) {
            $data_string = http_build_query($data);

            $options;

            if($token) {
                $options = [
                    'http' => [
                        'header' => "Authorization: Bearer $token\r\n",
                        'method' => $method
                    ]
                ];
            } else {
                $options = [
                    'http' => [
                        'method'  => $method, 
                        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                        'content' => $data_string, 
                    ]
                ];
            }

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === FALSE) {
                throw new Exception();
            }

            return $response;
        }
    }
?>