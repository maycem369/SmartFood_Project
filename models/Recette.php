<?php
require_once __DIR__ . "/../config/database.php";

class Recette {

    // GET ALL RECIPES
    public static function getAll() {
        $db = Database::connect();

        $sql = "SELECT * FROM recettes";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // SEARCH BY INGREDIENTS (SMART SEARCH)
    public static function searchByIngredients($text) {
        $db = Database::connect();

        $words = explode(" ", $text);

        $sql = "SELECT * FROM recettes WHERE ";

        $conditions = [];
        $params = [];

        foreach ($words as $word) {
            $conditions[] = "ingredients LIKE ?";
            $params[] = "%" . $word . "%";
        }

        $sql .= implode(" OR ", $conditions);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADD RECIPE
    public static function add($nom, $description, $ingredients) {
        $db = Database::connect();

        $sql = "INSERT INTO recettes (nom, description, status, ingredients)
                VALUES (?, ?, 'Non validée', ?)";

        $stmt = $db->prepare($sql);
        $stmt->execute([$nom, $description, $ingredients]);
    }

    // DELETE
    public static function delete($id) {
        $db = Database::connect();

        $sql = "DELETE FROM recettes WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
    }

    // UPDATE NAME
    public static function update($id, $nom) {
        $db = Database::connect();

        $sql = "UPDATE recettes SET nom = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nom, $id]);
    }

    // VALIDATE RECIPE
    public static function validate($id) {
        $db = Database::connect();

        $sql = "UPDATE recettes SET status = 'Validée' WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
    }
}
?>