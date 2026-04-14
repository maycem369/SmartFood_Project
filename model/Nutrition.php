<?php
// model/Nutrition.php — Modèle OOP pour l'entité Nutrition
require_once __DIR__ . '/../config/database.php';

class Nutrition {
    private $idNutrition;
    private $idIngredient;
    private $poids; // "poid" in diagram
    private $calories;
    private $proteines;
    private $glucides;
    private $lipides;

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->poids = 100; // Par défaut à 100g comme demandé
    }

    // ========================
    // GETTERS & SETTERS
    // ========================

    public function getIdNutrition() { return $this->idNutrition; }
    public function setIdNutrition($id) { $this->idNutrition = $id; }

    public function getIdIngredient() { return $this->idIngredient; }
    public function setIdIngredient($id) { $this->idIngredient = $id; }

    public function getPoids() { return $this->poids; }
    public function setPoids($poids) { $this->poids = $poids; }

    public function getCalories() { return $this->calories; }
    public function setCalories($calories) { $this->calories = $calories; }

    public function getProteines() { return $this->proteines; }
    public function setProteines($proteines) { $this->proteines = $proteines; }

    public function getGlucides() { return $this->glucides; }
    public function setGlucides($glucides) { $this->glucides = $glucides; }

    public function getLipides() { return $this->lipides; }
    public function setLipides($lipides) { $this->lipides = $lipides; }

    // ========================
    // MÉTHODES CRUD
    // ========================

    public function addNutrition($nutrition) {
        $sql = "INSERT INTO nutrition (idIngredient, poids, calories, proteines, glucides, lipides) 
                VALUES (:idIngredient, :poids, :calories, :proteines, :glucides, :lipides)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idIngredient', $nutrition->getIdIngredient());
        $stmt->bindValue(':poids', $nutrition->getPoids());
        $stmt->bindValue(':calories', $nutrition->getCalories());
        $stmt->bindValue(':proteines', $nutrition->getProteines());
        $stmt->bindValue(':glucides', $nutrition->getGlucides());
        $stmt->bindValue(':lipides', $nutrition->getLipides());
        return $stmt->execute();
    }

    public function updateNutrition($nutrition) {
        $sql = "UPDATE nutrition 
                SET poids = :poids, calories = :calories, proteines = :proteines, 
                    glucides = :glucides, lipides = :lipides 
                WHERE idIngredient = :idIngredient";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idIngredient', $nutrition->getIdIngredient());
        $stmt->bindValue(':poids', $nutrition->getPoids());
        $stmt->bindValue(':calories', $nutrition->getCalories());
        $stmt->bindValue(':proteines', $nutrition->getProteines());
        $stmt->bindValue(':glucides', $nutrition->getGlucides());
        $stmt->bindValue(':lipides', $nutrition->getLipides());
        return $stmt->execute();
    }

    // ========================
    // MÉTHODES SPÉCIFIQUES (Diagramme)
    // ========================

    /**
     * Calculer les nutrition pour un poids personnalisé
     */
    public function calculNutrition($customPoids) {
        $ratio = $customPoids / $this->poids;
        return [
            'calories' => round($this->calories * $ratio, 1),
            'proteines' => round($this->proteines * $ratio, 1),
            'glucides' => round($this->glucides * $ratio, 1),
            'lipides' => round($this->lipides * $ratio, 1)
        ];
    }

    /**
     * Retourne une chaîne formatée pour l'affichage
     */
    public function afficherValeurs() {
        return "{$this->calories} kcal | P: {$this->proteines}g | G: {$this->glucides}g | L: {$this->lipides}g (pour {$this->poids}g)";
    }
}
?>
