<?php

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/envLoader.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Dotenv\Dotenv;
use Firebase\JWT\Key;

class jwtSigner {
    private static string $issuer = 'MK';
    private static int $expirationTime = 10 * 60 * 60;

    public static function createToken($username): string {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $key = password_hash($user_agent, PASSWORD_DEFAULT);
        $issuedAt = time();
        $expirationTime = $issuedAt + self::$expirationTime;

        $payload = [
            'exp' => $expirationTime,
            'sub' => $username,
            'key' => $key
        ];

        return JWT::encode($payload, envLoader::getEnv("jwt_secret"), "HS512");
    }

    public static function validateToken($token) {
        $key = envLoader::getEnv("jwt_secret");

        $decoded = JWT::decode($token, new Key($key, 'HS512'));

        return $decoded;
    }
}