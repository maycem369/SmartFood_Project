<?php
class Profil {
    private $conn;
    private $table = "profil";

    public $id_user;
    public $age;
    public $sexe;
    public $poids;
    public $taille;
    public $objectif;
    public $niveau_activite;
    public $allergies;
    public $photo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createDefault() {
        $query = "INSERT INTO " . $this->table . " (id_user, age, sexe, poids, taille, objectif, niveau_activite, allergies, photo) VALUES (:id_user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-avatar.png')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    }

    public function getByUserId($id_user) {
        $query = "SELECT p.*, u.nom, u.prenom, u.email FROM " . $this->table . " p JOIN utilisateur u ON p.id_user = u.idUser WHERE p.id_user = :id_user LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET age = :age, sexe = :sexe, poids = :poids, taille = :taille, objectif = :objectif, niveau_activite = :niveau_activite, allergies = :allergies, photo = :photo WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':poids', $this->poids);
        $stmt->bindParam(':taille', $this->taille);
        $stmt->bindParam(':objectif', $this->objectif);
        $stmt->bindParam(':niveau_activite', $this->niveau_activite);
        $stmt->bindParam(':allergies', $this->allergies);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    }

    public static function calculIMC($poids, $taille) {
        if (!$poids || !$taille || $taille == 0) return null;
        $tailleM = $taille / 100;
        return round($poids / ($tailleM * $tailleM), 1);
    }
}
?>