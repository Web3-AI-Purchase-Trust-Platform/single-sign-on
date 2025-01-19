<?php

require_once __DIR__. "/../service/envLoader.php";

class userData {
    private static $conn;

    public static function connect() {
        $host = envLoader::getEnv('database_host');
        $port = envLoader::getEnv('database_port');
        $user = envLoader::getEnv('database_user');
        $paswd = envLoader::getEnv('database_password');
        $name = envLoader::getEnv('database_name');

        try {
            self::$conn = new mysqli();
            self::$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
            self::$conn->real_connect($host, $user, $paswd, $name, $port);

            if (!self::$conn->set_charset("utf8mb4")) {
                throw new Exception("Không thể thiết lập UTF-8: " . self::$conn->error);
            }

        } catch (Exception $e) {
            throw new Exception("Không thể kết nối db, " . $e->getMessage());
        }
    }

    private static function queryExecutor($sql) {
        try {
            $result = self::$conn->query($sql);

            if (!$result) {
                throw new Exception("Tạo bảng thất bại!");

                self::$conn->close();
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private static function getDataFromResult($result) {
        $results = [];

        while ($array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $results[] = $array;
        }

        if(!$results)
            return false;

        return $results;
    }

    private static function getDataFromQuery($sql) {
        return self::getDataFromResult(self::queryExecutor($sql));
    }

    // ----------------------------------
    // Query function
    public static function existByEmail($email) {
        $sql = "
            SELECT * FROM user_data 
            WHERE email = ?
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_assoc(); 

                $stmt->close();
                return $data; 
            } else {
                throw new Exception("Error: " . $stmt->error);
            }
        } else {
            throw new Exception("Error: " . self::$conn->error);
        }
    }

    public static function addNewAccount($email, $username, $password, $picture, $name) {
        $sql = "
            INSERT INTO user_data (email, username, password, picture, name) 
            VALUES (?, ?, ?, ?, ?)
        ";


        if ($stmt = self::$conn->prepare($sql)) {
            // Liên kết các tham số với câu lệnh SQL
            $stmt->bind_param("sssss", $email, $username, $password, $picture, $name);
        
            // Thực thi câu lệnh
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
        
            $stmt->close();
        } else {
            throw new Exception("Error: " . self::$conn->error);
        }
    }
}

try {
    userData::connect();
} catch (Exception $e) {

}