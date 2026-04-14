<?php
// config/database.php — PDO Singleton Connection

class Database {
    private static $instance = null;
    private $connection;

    private $host = "localhost";
    private $dbname = "smartfood";
    private $username = "root";
    private $password = "";

    // Constructeur privé (Singleton)
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    // Méthode Singleton pour obtenir l'instance unique
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Retourne la connexion PDO
    public function getConnection() {
        return $this->connection;
    }
}
?>
