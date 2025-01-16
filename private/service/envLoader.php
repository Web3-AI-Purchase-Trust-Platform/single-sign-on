<?php

class envLoader {
    private static array $env;

    public static function loadEnv () {
        if(file_exists('../../resources/.env')) {
            self::$env = parse_ini_file('../../resources/.env');
        }
        else
            throw new Exception("Không tìm thấy file .env");
    }

    public static function getEnv (String $name) {
        return self::$env[$name];
    }
}

try {
    envLoader::loadEnv();  
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}