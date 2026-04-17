<?php
// model/Ingredient.php — Modèle OOP pour l'entité Ingredient (+ Nutrition)
require_once __DIR__ . '/../config/config.php';

class Ingredient {
    // Propriétés privées (encapsulation OOP) avec types (PHP 7.4+)
    private ?int $idIngredient = null;
    private string $nom;

    // Constructeur
    public function __construct() {
        // La connexion est gérée via la classe static config dans les méthodes CRUD
    }

    // ========================
    // GETTERS & SETTERS
    // ========================

    public function getIdIngredient(): ?int {
        return $this->idIngredient;
    }

    public function setIdIngredient(?int $idIngredient): void {
        $this->idIngredient = $idIngredient;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
}

?>
