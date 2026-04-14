<?php
require_once __DIR__ . "/../models/Recette.php";

class RecetteController {

    // SHOW ALL
    public function getAll() {
        return Recette::getAll();
    }

    // SEARCH BY INGREDIENTS
    public function search($ingredients) {
        return Recette::searchByIngredients($ingredients);
    }

    // ADD
    public function add($nom, $description, $ingredients) {
        Recette::add($nom, $description, $ingredients);
    }

    // DELETE
    public function delete($id) {
        Recette::delete($id);
    }

    // UPDATE
    public function update($id, $nom) {
        Recette::update($id, $nom);
    }

    // VALIDATE
    public function validate($id) {
        Recette::validate($id);
    }
}
?>