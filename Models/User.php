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

    public function create(): bool {
        $query = "INSERT INTO " . $this->table . " (nom, prenom, email, motDePasse, role)
                  VALUES (:nom, :prenom, :email, :motDePasse, :role)";
        $stmt  = $this->conn->prepare($query);
        $hashed = password_hash($this->motDePasse, PASSWORD_DEFAULT);
        $stmt->bindParam(':nom',        $this->nom);
        $stmt->bindParam(':prenom',     $this->prenom);
        $stmt->bindParam(':email',      $this->email);
        $stmt->bindParam(':motDePasse', $hashed);
        $stmt->bindParam(':role',       $this->role);
        return $stmt->execute();
    }

    public function login(string $email, string $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND statut = 'actif'";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['motDePasse'])) {
            return $user;
        }
        return false;
    }

    public function emailExists(string $email): bool {
        $query = "SELECT idUser FROM " . $this->table . " WHERE email = :email";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function updatePassword(string $email, string $newPassword): bool {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $query  = "UPDATE " . $this->table . " SET motDePasse = :password WHERE email = :email";
        $stmt   = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':email',    $email);
        return $stmt->execute();
    }

    // Retourne un seul utilisateur par id
    public function readOne(int $id): ?array {
        $query = "SELECT * FROM " . $this->table . " WHERE idUser = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Retourne tous les utilisateurs (pour la liste admin)
    public function readAll(): array {
        $query = "SELECT * FROM " . $this->table . " ORDER BY idUser DESC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retourne un utilisateur avec ses données de profil (pour user_details)
    public function readOneWithProfil(int $id): ?array {
        $query = "SELECT u.*, p.age, p.sexe, p.poids, p.taille,
                         p.objectif, p.niveau_activite, p.allergies
                  FROM " . $this->table . " u
                  LEFT JOIN profil p ON u.idUser = p.id_user
                  WHERE u.idUser = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(): bool {
        $query = "UPDATE " . $this->table . "
                  SET nom=:nom, prenom=:prenom, email=:email, role=:role
                  WHERE idUser=:id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id',     $this->idUser);
        $stmt->bindParam(':nom',    $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email',  $this->email);
        $stmt->bindParam(':role',   $this->role);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM " . $this->table . " WHERE idUser = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getLastInsertId(): string {
        return $this->conn->lastInsertId();
    }
}
?>