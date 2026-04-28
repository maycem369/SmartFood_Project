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

    public function createDefault(): bool {
        $query = "INSERT INTO " . $this->table .
                 " (id_user, age, sexe, poids, taille, objectif, niveau_activite, allergies, photo)
                   VALUES (:id_user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-avatar.png')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    }

    public function getByUserId(int $id_user): ?array {
        $query = "SELECT p.*, u.nom, u.prenom, u.email
                  FROM " . $this->table . " p
                  JOIN utilisateur u ON p.id_user = u.idUser
                  WHERE p.id_user = :id_user
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(): bool {
        $query = "UPDATE " . $this->table . "
                  SET age = :age, sexe = :sexe, poids = :poids, taille = :taille,
                      objectif = :objectif, niveau_activite = :niveau_activite,
                      allergies = :allergies, photo = :photo
                  WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':age',            $this->age);
        $stmt->bindParam(':sexe',           $this->sexe);
        $stmt->bindParam(':poids',          $this->poids);
        $stmt->bindParam(':taille',         $this->taille);
        $stmt->bindParam(':objectif',       $this->objectif);
        $stmt->bindParam(':niveau_activite',$this->niveau_activite);
        $stmt->bindParam(':allergies',      $this->allergies);
        $stmt->bindParam(':photo',          $this->photo);
        $stmt->bindParam(':id_user',        $this->id_user);
        return $stmt->execute();
    }

    // ── Calcul de l'IMC (logique métier dans le modèle) ──────────────────────
    public static function calculIMC(float $poids, float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        $tailleM = $taille / 100;
        return round($poids / ($tailleM * $tailleM), 1);
    }

    // Label IMC déplacé ici (était dans le contrôleur — violation MVC)
    public static function getImcLabel(float $imc): string {
        if      ($imc < 18.5) return "Insuffisance pondérale";
        elseif  ($imc < 25)   return "Normal";
        elseif  ($imc < 30)   return "Surpoids";
        else                  return "Obésité";
    }
}
?>