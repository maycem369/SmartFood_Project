<?php
// controller/IngredientController.php — Contrôleur CRUD pour Ingredient
require_once __DIR__ . '/../model/Ingredient.php';
require_once __DIR__ . '/../model/Nutrition.php';
require_once __DIR__ . '/../config/config.php';

class IngredientController {

    /**
     * READ — Récupérer tous les ingrédients (avec leurs valeurs nutritionnelles liées)
     */
    public function listIngredients(): array {
        $sql = "SELECT i.*, n.calories, n.proteines, n.glucides, n.lipides, n.poids as valeur_portion 
                FROM ingredient i
                LEFT JOIN nutrition n ON i.idIngredient = n.idIngredient
                ORDER BY i.idIngredient ASC";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * READ — Récupérer un ingrédient par son ID
     */
    public function getIngredientById(int $id) {
        $sql = "SELECT i.*, n.calories, n.proteines, n.glucides, n.lipides, n.poids as valeur_portion 
                FROM ingredient i
                LEFT JOIN nutrition n ON i.idIngredient = n.idIngredient
                WHERE i.idIngredient = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * CREATE — Ajouter un nouvel ingrédient
     */
    public function addIngredient(Ingredient $ingredient) {
        $sql = "INSERT INTO ingredient (nom) VALUES (:nom)";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nom', $ingredient->getNom());
            
            if ($stmt->execute()) {
                return $db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * CREATE — Ajouter la nutrition liée
     */
    public function addNutrition(Nutrition $nutrition): bool {
        $sql = "INSERT INTO nutrition (idIngredient, poids, calories, proteines, glucides, lipides) 
                VALUES (:idIngredient, :poids, :calories, :proteines, :glucides, :lipides)";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':idIngredient', $nutrition->getIdIngredient(), PDO::PARAM_INT);
            $stmt->bindValue(':poids', $nutrition->getPoids());
            $stmt->bindValue(':calories', $nutrition->getCalories());
            $stmt->bindValue(':proteines', $nutrition->getProteines());
            $stmt->bindValue(':glucides', $nutrition->getGlucides());
            $stmt->bindValue(':lipides', $nutrition->getLipides());
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE — Modifier un ingrédient existant
     */
    public function updateIngredient(Ingredient $ingredient): bool {
        $sql = "UPDATE ingredient SET nom = :nom WHERE idIngredient = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $ingredient->getIdIngredient(), PDO::PARAM_INT);
            $stmt->bindValue(':nom', $ingredient->getNom());
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE — Modifier la nutrition liée
     */
    public function updateNutrition(Nutrition $nutrition): bool {
        $sql = "UPDATE nutrition 
                SET poids = :poids, calories = :calories, proteines = :proteines, 
                    glucides = :glucides, lipides = :lipides 
                WHERE idIngredient = :idIngredient";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':idIngredient', $nutrition->getIdIngredient(), PDO::PARAM_INT);
            $stmt->bindValue(':poids', $nutrition->getPoids());
            $stmt->bindValue(':calories', $nutrition->getCalories());
            $stmt->bindValue(':proteines', $nutrition->getProteines());
            $stmt->bindValue(':glucides', $nutrition->getGlucides());
            $stmt->bindValue(':lipides', $nutrition->getLipides());
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * DELETE — Supprimer un ingrédient par son ID
     */
    public function deleteIngredient(int $id): bool {
        $sql = "DELETE FROM ingredient WHERE idIngredient = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}

// ============================================================
// ROUTING (Gestion des requêtes POST/GET)
// ============================================================

$controller = new IngredientController();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ingredient = new Ingredient();
            $ingredient->setNom(trim($_POST['nom']));
            $newId = $controller->addIngredient($ingredient);

            if ($newId) {
                $nutrition = new Nutrition();
                $nutrition->setIdIngredient((int)$newId);
                $nutrition->setCalories((float)$_POST['calories']);
                $nutrition->setProteines((float)$_POST['proteines']);
                $nutrition->setGlucides((float)$_POST['glucides']);
                $nutrition->setLipides((float)$_POST['lipides']);
                $nutrition->setPoids(100.0);

                if ($controller->addNutrition($nutrition)) {
                    header('Location: ../view/backoffice/index.php?success=added');
                } else {
                    header('Location: ../view/backoffice/index.php?error=nutrition_add_failed');
                }
            } else {
                header('Location: ../view/backoffice/index.php?error=ingredient_add_failed');
            }
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['idIngredient'];
            $ingredient = new Ingredient();
            $ingredient->setIdIngredient($id);
            $ingredient->setNom(trim($_POST['nom']));

            if ($controller->updateIngredient($ingredient)) {
                $nutrition = new Nutrition();
                $nutrition->setIdIngredient($id);
                $nutrition->setCalories((float)$_POST['calories']);
                $nutrition->setProteines((float)$_POST['proteines']);
                $nutrition->setGlucides((float)$_POST['glucides']);
                $nutrition->setLipides((float)$_POST['lipides']);
                $nutrition->setPoids(100.0);

                if ($controller->updateNutrition($nutrition)) {
                    header('Location: ../view/backoffice/index.php?success=updated');
                } else {
                    header('Location: ../view/backoffice/index.php?error=nutrition_update_failed');
                }
            } else {
                header('Location: ../view/backoffice/index.php?error=ingredient_update_failed');
            }
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($controller->deleteIngredient($id)) {
                header('Location: ../view/backoffice/index.php?success=deleted');
            } else {
                header('Location: ../view/backoffice/index.php?error=delete_failed');
            }
        }
        break;

    case 'export_csv':
        // Stream all ingredients as a downloadable CSV file
        $ingredients = $controller->listIngredients();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="smartfood_ingredients_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for Excel compatibility
        fputs($out, "\xEF\xBB\xBF");
        // Header row
        fputcsv($out, ['ID', 'Nom', 'Calories (kcal/100g)', 'Proteines (g/100g)', 'Glucides (g/100g)', 'Lipides (g/100g)'], ';');
        // Data rows
        foreach ($ingredients as $ing) {
            fputcsv($out, [
                $ing['idIngredient'],
                $ing['nom'],
                $ing['calories'],
                $ing['proteines'],
                $ing['glucides'],
                $ing['lipides']
            ], ';');
        }
        fclose($out);
        exit;
}
?>
