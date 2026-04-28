<?php
class Profil {
    private $conn;
    private $table = "profil";

    private $id_user;
    private $age;
    private $sexe;
    private $poids;
    private $taille;
    private $objectif;
    private $niveau_activite;
    private $allergies;
    private $photo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Getters ───────────────────────────────────────────────────────────────

    public function getConn() { return $this->conn; }
    public function getTable(): string { return $this->table; }
    public function getIdUser() { return $this->id_user; }
    public function getAge() { return $this->age; }
    public function getSexe(): ?string { return $this->sexe; }
    public function getPoids() { return $this->poids; }
    public function getTaille() { return $this->taille; }
    public function getObjectif(): ?string { return $this->objectif; }
    public function getNiveauActivite(): ?string { return $this->niveau_activite; }
    public function getAllergies(): ?string { return $this->allergies; }
    public function getPhoto(): ?string { return $this->photo; }

    // ── Setters ───────────────────────────────────────────────────────────────

    public function setIdUser($id): void { $this->id_user = $id; }
    public function setAge($age): void { $this->age = $age; }
    public function setSexe(?string $sexe): void { $this->sexe = $sexe; }
    public function setPoids($poids): void { $this->poids = $poids; }
    public function setTaille($taille): void { $this->taille = $taille; }
    public function setObjectif(?string $objectif): void { $this->objectif = $objectif; }
    public function setNiveauActivite(?string $niveauActivite): void { $this->niveau_activite = $niveauActivite; }
    public function setAllergies(?string $allergies): void { $this->allergies = $allergies; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }
}
?>