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

    public static function getDataFromUsername($username) {
        $sql = "
            SELECT * FROM `user_data` 
            WHERE username = ?
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); 
            } else {
                return null; 
            }
        } else {
            return null; 
        }
    }

    public static function addNewUserAgentWithOtp($username) {
        $user_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $hmac_key = envLoader::getEnv('hmac_key');
        $hash = hash_hmac('sha256', $user_agent, $hmac_key);

        $otp = '';
        for ($i = 0; $i < 25; $i++) {
            $otp .= random_int(1, 9);
        }

        $sql = "
            INSERT INTO user_agent (username, hash_key, otp)
            VALUES (?, ?, ?)
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            // Liên kết các tham số với câu lệnh SQL
            $stmt->bind_param("ssi", $username, $hash, $otp);
        
            // Thực thi câu lệnh
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
        
            // Lấy ID của dòng vừa chèn
            $last_insert_id = self::$conn->insert_id;

            $stmt->close();

            return ["id" => $last_insert_id, "otp" => $otp];
        } else {
            throw new Exception("Error: " . self::$conn->error);
        }
    }

    public static function findByHash() {
        $user_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $hmac_key = envLoader::getEnv('hmac_key');
        $hash = hash_hmac('sha256', $user_agent, $hmac_key);

        $sql = "
            SELECT * FROM `user_agent` 
            WHERE hash_key = ?
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param("s", $hash);  
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function getAgentById($id) {
        $sql = "
            SELECT * FROM `user_agent` 
            WHERE id = ?
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param("s", $id);  
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public static function verifyOtp($id) {
        $sql = "
            UPDATE user_agent
            SET otp = 0
            WHERE id = ?
        ";

        if ($stmt = self::$conn->prepare($sql)) {
            $stmt->bind_param("s", $id);  
            $stmt->execute();
        } else {
            return null;
        }
    }
}

try {
    userData::connect();
} catch (Exception $e) {

}