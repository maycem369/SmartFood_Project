<?php
// model/Ingredient.php — Modèle OOP pour l'entité Ingredient (+ Nutrition)

require_once __DIR__ . '/../config/database.php';

class Ingredient {
    // Propriétés privées (encapsulation OOP)
    private $idIngredient;
    private $nom;
    private $quantite;

    // Connexion PDO
    private $db;

    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // ========================
    // GETTERS & SETTERS
    // ========================

    public function getIdIngredient() {
        return $this->idIngredient;
    }

    public function setIdIngredient($idIngredient) {
        $this->idIngredient = $idIngredient;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getQuantite() {
        return $this->quantite;
    }

    public function setQuantite($quantite) {
        $this->quantite = $quantite;
    }

    // ========================
    // MÉTHODES CRUD
    // ========================

    /**
     * READ — Récupérer tous les ingrédients (avec leurs valeurs nutritionnelles liées)
     * @return array Liste de tous les ingrédients
     */
    public function getAllIngredients() {
        $sql = "SELECT i.*, n.calories, n.proteines, n.glucides, n.lipides, n.poids as valeur_portion 
                FROM ingredient i
                LEFT JOIN nutrition n ON i.idIngredient = n.idIngredient
                ORDER BY i.idIngredient ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * READ — Récupérer un ingrédient par son ID
     * @param int $id L'identifiant de l'ingrédient
     * @return array|false Les données de l'ingrédient ou false
     */
    public function getIngredientById($id) {
        $sql = "SELECT i.*, n.calories, n.proteines, n.glucides, n.lipides, n.poids as valeur_portion 
                FROM ingredient i
                LEFT JOIN nutrition n ON i.idIngredient = n.idIngredient
                WHERE i.idIngredient = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * CREATE — Ajouter un nouvel ingrédient
     * @param Ingredient $ingredient L'objet ingrédient à ajouter
     * @return int|false L'ID de l'ingrédient inséré ou false
     */
    public function addIngredient($ingredient) {
        $sql = "INSERT INTO ingredient (nom, quantite) VALUES (:nom, :quantite)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $ingredient->getNom());
        $stmt->bindValue(':quantite', $ingredient->getQuantite());
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * UPDATE — Modifier un ingrédient existant
     * @param Ingredient $ingredient L'objet ingrédient modifié
     * @return bool Succès ou échec
     */
    public function updateIngredient($ingredient) {
        $sql = "UPDATE ingredient SET nom = :nom, quantite = :quantite WHERE idIngredient = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $ingredient->getIdIngredient(), PDO::PARAM_INT);
        $stmt->bindValue(':nom', $ingredient->getNom());
        $stmt->bindValue(':quantite', $ingredient->getQuantite());
        return $stmt->execute();
    }

    /**
     * DELETE — Supprimer un ingrédient par son ID
     * @param int $id L'identifiant de l'ingrédient à supprimer
     * @return bool Succès ou échec
     */
    public function deleteIngredient($id) {
        // La suppression de la nutrition est gérée par ON DELETE CASCADE au niveau SQL
        $sql = "DELETE FROM ingredient WHERE idIngredient = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
