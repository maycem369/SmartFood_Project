<?php
// controller/IngredientController.php — CRUD Ingredient (idIngredient, nom, calories, proteines, glucides, lipides)
require_once __DIR__ . '/../model/Ingredient.php';
require_once __DIR__ . '/../config/config.php';

class IngredientController {

    public function listIngredients(): array {
        $sql = "SELECT idIngredient, nom, calories, proteines, glucides, lipides FROM ingredient ORDER BY idIngredient ASC";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getIngredientById(int $id) {
        $sql = "SELECT idIngredient, nom, calories, proteines, glucides, lipides FROM ingredient WHERE idIngredient = :id";
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

    public function addIngredient(string $nom, float $calories, float $proteines, float $glucides, float $lipides): bool {
        $sql = "INSERT INTO ingredient (nom, calories, proteines, glucides, lipides) VALUES (:nom, :calories, :proteines, :glucides, :lipides)";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nom',       $nom);
            $stmt->bindValue(':calories',  $calories);
            $stmt->bindValue(':proteines', $proteines);
            $stmt->bindValue(':glucides',  $glucides);
            $stmt->bindValue(':lipides',   $lipides);
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateIngredient(int $id, string $nom, float $calories, float $proteines, float $glucides, float $lipides): bool {
        $sql = "UPDATE ingredient SET nom=:nom, calories=:calories, proteines=:proteines, glucides=:glucides, lipides=:lipides WHERE idIngredient=:id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id',        $id, PDO::PARAM_INT);
            $stmt->bindValue(':nom',       $nom);
            $stmt->bindValue(':calories',  $calories);
            $stmt->bindValue(':proteines', $proteines);
            $stmt->bindValue(':glucides',  $glucides);
            $stmt->bindValue(':lipides',   $lipides);
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

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
// ROUTING
// ============================================================
$controller = new IngredientController();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom       = trim($_POST['nom'] ?? '');
            $calories  = floatval($_POST['calories']  ?? 0);
            $proteines = floatval($_POST['proteines'] ?? 0);
            $glucides  = floatval($_POST['glucides']  ?? 0);
            $lipides   = floatval($_POST['lipides']   ?? 0);

            if (empty($nom)) {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&error=nom_vide');
                exit;
            }
            if ($controller->addIngredient($nom, $calories, $proteines, $glucides, $lipides)) {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&success=added');
            } else {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&error=add_failed');
            }
            exit;
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id        = (int)($_POST['idIngredient'] ?? 0);
            $nom       = trim($_POST['nom'] ?? '');
            $calories  = floatval($_POST['calories']  ?? 0);
            $proteines = floatval($_POST['proteines'] ?? 0);
            $glucides  = floatval($_POST['glucides']  ?? 0);
            $lipides   = floatval($_POST['lipides']   ?? 0);

            if (empty($nom) || $id <= 0) {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&error=invalid_data');
                exit;
            }
            if ($controller->updateIngredient($id, $nom, $calories, $proteines, $glucides, $lipides)) {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&success=updated');
            } else {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&error=update_failed');
            }
            exit;
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($controller->deleteIngredient($id)) {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&success=deleted');
            } else {
                header('Location: /smartfoodMVC/index.php?action=nutrition_admin&tab=ingredient&error=delete_failed');
            }
            exit;
        }
        break;

    case 'export_csv':
        $ingredients = $controller->listIngredients();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="smartfood_ingredients_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF");
        fputcsv($out, ['ID','Nom','Calories (kcal)','Protéines (g)','Glucides (g)','Lipides (g)'], ';');
        foreach ($ingredients as $ing) {
            fputcsv($out, [
                $ing['idIngredient'], $ing['nom'],
                $ing['calories'], $ing['proteines'], $ing['glucides'], $ing['lipides']
            ], ';');
        }
        fclose($out);
        exit;
}
?>
