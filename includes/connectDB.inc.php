<?php
session_start();

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $host = 'mysql-nazrin713.alwaysdata.net';
        $dbname = 'nazrin713_driving_experience_db_2025';
        $user = 'YOUR_DATABASE_USERNAME_HERE';   
        $pass = 'YOUR_DATABASE_PASSWORD_HERE';    
        
        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

function random_code($length = 10) {
    $charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz023456789';
    $code = '';
    for($i = 0; $i < $length; $i++) {
        $code .= $charlist[random_int(0, strlen($charlist) - 1)];
    }
    return $code;
}

function calculateDuration($startTime, $endTime) {
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $interval = $start->diff($end);
    return $interval->format('%H:%I');
}
?>