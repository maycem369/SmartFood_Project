<?php
class User {
    private $conn;
    private $table = "utilisateur";

    public $idUser;
    public $nom;
    public $prenom;
    public $email;
    public $motDePasse;
    public $statut;
    public $role;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (nom, prenom, email, motDePasse, role) VALUES (:nom, :prenom, :email, :motDePasse, :role)";
        $stmt = $this->conn->prepare($query);
        $hashed = password_hash($this->motDePasse, PASSWORD_DEFAULT);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':motDePasse', $hashed);
        $stmt->bindParam(':role', $this->role);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND statut = 'actif'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['motDePasse'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT idUser FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function updatePassword($email, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . " SET motDePasse = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE idUser = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET nom=:nom, prenom=:prenom, email=:email, role=:role WHERE idUser=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->idUser);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE idUser = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>