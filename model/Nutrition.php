<?php
// model/Nutrition.php — Modèle OOP pour l'entité Nutrition
require_once __DIR__ . '/../config/config.php';

class Nutrition {
    private ?int $idNutrition = null;
    private int $idIngredient;
    private float $poids; 
    private float $calories;
    private float $proteines;
    private float $glucides;
    private float $lipides;

    public function __construct() {
        $this->poids = 100.0; // Par défaut à 100g
    }

    // ========================
    // GETTERS & SETTERS
    // ========================

    public function getIdNutrition(): ?int { return $this->idNutrition; }
    public function setIdNutrition(?int $id): void { $this->idNutrition = $id; }

    public function getIdIngredient(): int { return $this->idIngredient; }
    public function setIdIngredient(int $id): void { $this->idIngredient = $id; }

    public function getPoids(): float { return $this->poids; }
    public function setPoids(float $poids): void { $this->poids = $poids; }

    public function getCalories(): float { return $this->calories; }
    public function setCalories(float $calories): void { $this->calories = $calories; }

    public function getProteines(): float { return $this->proteines; }
    public function setProteines(float $proteines): void { $this->proteines = $proteines; }

    public function getGlucides(): float { return $this->glucides; }
    public function setGlucides(float $glucides): void { $this->glucides = $glucides; }

    public function getLipides(): float { return $this->lipides; }
    public function setLipides(float $lipides): void { $this->lipides = $lipides; }

    // ========================
    // MÉTHODES SPÉCIFIQUES
    // ========================

    public function calculNutrition(float $customPoids): array {
        $ratio = $customPoids / $this->poids;
        return [
            'calories' => round($this->calories * $ratio, 1),
            'proteines' => round($this->proteines * $ratio, 1),
            'glucides' => round($this->glucides * $ratio, 1),
            'lipides' => round($this->lipides * $ratio, 1)
        ];
    }

    public function afficherValeurs(): string {
        return "{$this->calories} kcal | P: {$this->proteines}g | G: {$this->glucides}g | L: {$this->lipides}g (pour {$this->poids}g)";
    }
}
?>
