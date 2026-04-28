<?php
class User {
    private $conn;
    private $table = "utilisateur";

    private $idUser;
    private $nom;
    private $prenom;
    private $email;
    private $motDePasse;
    private $statut;
    private $role;
    private $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Getters ───────────────────────────────────────────────────────────────

    public function getConn() { return $this->conn; }
    public function getTable(): string { return $this->table; }
    public function getIdUser() { return $this->idUser; }
    public function getNom(): ?string { return $this->nom; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function getEmail(): ?string { return $this->email; }
    public function getMotDePasse(): ?string { return $this->motDePasse; }
    public function getStatut(): ?string { return $this->statut; }
    public function getRole(): ?string { return $this->role; }
    public function getDateCreation(): ?string { return $this->date_creation; }

    // ── Setters ───────────────────────────────────────────────────────────────

    public function setIdUser($id): void { $this->idUser = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setMotDePasse(string $motDePasse): void { $this->motDePasse = $motDePasse; }
    public function setStatut(string $statut): void { $this->statut = $statut; }
    public function setRole(string $role): void { $this->role = $role; }
    public function setDateCreation(string $date): void { $this->date_creation = $date; }
}
?>