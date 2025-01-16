<?php

class jsonLoader {
    private static array $config;

    public static function loadJson($file) {
        if (file_exists($file)) {
            $jsonContent = file_get_contents($file);
            self::$config = json_decode($jsonContent, true); 
        } else {
            throw new Exception("Không tìm thấy file JSON");
        }
    }

    public static function getConfig($key) {
        return self::$config[$key] ?? null; 
    }
}

try {
    jsonLoader::loadJson(__DIR__ . '/config.json');  
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
