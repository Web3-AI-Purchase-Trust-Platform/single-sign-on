<?php

class envLoader {
    private static array $env;

    public static function loadEnv ($file) {
        if(file_exists($file)) {
            self::$env = parse_ini_file($file);
        }
        else
            throw new Exception("Không tìm thấy file .env");
    }

    public static function getEnv (String $name) {
        return self::$env[$name];
    }
}

try {
    envLoader::loadEnv(__DIR__ . '/.env');
} catch (Exception $e) {
    echo __DIR__;
    echo "Lỗi: " . $e->getMessage();
}