<?php
// models/User.php
class User {
    private $conn;
    private $table = "utilisateur";

    public $idUser;
    public $nom;
    public $prenom;
    public $email;
    public $motDePasse;
    public $statut = 'actif';
    public $role = 'user';
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (nom, prenom, email, motDePasse, role) 
                 VALUES (:nom, :prenom, :email, :motDePasse, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed = password_hash($this->motDePasse, PASSWORD_DEFAULT);

        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':motDePasse', $hashed);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    // LOGIN
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

    // READ ALL
    public function readAll() {
        $query = "SELECT u.*, p.age, p.poids, p.taille 
                  FROM utilisateur u 
                  LEFT JOIN profil p ON u.idUser = p.id_user 
                  ORDER BY u.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE idUser = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET nom=:nom, prenom=:prenom, email=:email, role=:role 
                  WHERE idUser=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->idUser);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE idUser = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // NOUVELLE MÉTHODE - IMPORTANT
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>