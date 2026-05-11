<?php
require_once __DIR__ . "/../config/database.php";

class IngredientController {

    public function handleRequest() {
        if (isset($_POST['addIngredient']) && !empty($_POST['nomIngredient'])) {
            $this->add($_POST['nomIngredient']);
        }
    }

    public function add($nom) {
        $db = RecetteDatabase::connect();
        // quantite a une valeur par défaut dans la table
        $db->prepare("INSERT INTO ingredient (nom, quantite) VALUES (?, 0)")->execute([$nom]);
    }

    public function getAll() {
        $db = RecetteDatabase::connect();
        // On retourne idIngredient comme idingredient pour compatibilité
        return $db->query("SELECT idIngredient as idingredient, nom FROM ingredient ORDER BY nom")->fetchAll();
    }
}
