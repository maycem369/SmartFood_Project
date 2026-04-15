<?php
// models/Profil.php
class Profil {
    private $conn;
    private $table = "profil";

    public $idProfil;
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

    // Création du profil par défaut après inscription
    public function createDefault() {
        $query = "INSERT INTO " . $this->table . " 
                 (id_user, age, sexe, poids, taille, objectif, niveau_activite, allergies, photo) 
                 VALUES (:id_user, 28, 'Homme', 72.0, 175.0, 'Perte de poids', 'Modéré', 'Aucune', 'default-avatar.png')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    }

    // Récupérer le profil d'un utilisateur
    public function getByUserId($id_user) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mise à jour du profil
    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                    age = :age,
                    sexe = :sexe,
                    poids = :poids,
                    taille = :taille,
                    objectif = :objectif,
                    niveau_activite = :niveau_activite,
                    allergies = :allergies,
                    photo = :photo
                  WHERE id_user = :id_user";
        
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
}
?>