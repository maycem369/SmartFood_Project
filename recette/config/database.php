<?php

class RecetteDatabase {

    public static function connect() {
        try {
            return new PDO("mysql:host=localhost;dbname=smartfood_db;charset=utf8mb4", "root", "");
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
    }

}
