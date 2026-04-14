<?php
class Database {
    public static function connect() {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=smartfood", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>