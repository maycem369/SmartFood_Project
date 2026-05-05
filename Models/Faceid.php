<?php
class FaceId {
    private $conn;
    private $table = "face_descriptors";

    private $id;
    private $id_user;
    private $descriptor;
    private $created_at;
    private $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Getters ───────────────────────────────────────────────────────────────

    public function getConn() { return $this->conn; }
    public function getTable(): string { return $this->table; }
    public function getId() { return $this->id; }
    public function getIdUser() { return $this->id_user; }
    public function getDescriptor(): ?string { return $this->descriptor; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }

    // ── Setters ───────────────────────────────────────────────────────────────

    public function setId($id): void { $this->id = $id; }
    public function setIdUser($id_user): void { $this->id_user = $id_user; }
    public function setDescriptor(?string $descriptor): void { $this->descriptor = $descriptor; }
    public function setCreatedAt(?string $created_at): void { $this->created_at = $created_at; }
    public function setUpdatedAt(?string $updated_at): void { $this->updated_at = $updated_at; }
}
?>