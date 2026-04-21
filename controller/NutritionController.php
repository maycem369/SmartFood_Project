<?php
// controller/NutritionController.php — Deuxième Entité Controller
require_once __DIR__ . '/../model/Nutrition.php';
require_once __DIR__ . '/../config/config.php';

class NutritionController {

    /**
     * READ — Effectue une jointure (INNER JOIN) entre nutrition et ingredient.
     */
    public function listAllNutritionWithNames(): array {
        $sql = "SELECT n.*, i.nom as nom_ingredient
                FROM nutrition n
                INNER JOIN ingredient i ON n.idIngredient = i.idIngredient
                ORDER BY n.idNutrition ASC";
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
     * READ — Récupérer une fiche nutritionnelle spécifique.
     */
    public function getNutritionById(int $idNutrition) {
        $sql = "SELECT n.*, i.nom as nom_ingredient
                FROM nutrition n
                INNER JOIN ingredient i ON n.idIngredient = i.idIngredient
                WHERE n.idNutrition = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $idNutrition, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE — Mettre à jour les valeurs nutritionnelles avec validation stricte en PHP.
     */
    public function updateNutritionValues(int $idNutrition, $calories, $proteines, $glucides, $lipides): array {
        $errors = [];

        // Validation PHP manuelle (car on n'utilise pas HTML5)
        if (trim($calories) === '') $errors[] = "Le champ Calories est obligatoire.";
        elseif (!is_numeric($calories) || $calories < 0) $errors[] = "Les calories doivent être un nombre positif.";

        if (trim($proteines) === '') $errors[] = "Le champ Protéines est obligatoire.";
        elseif (!is_numeric($proteines) || $proteines < 0) $errors[] = "Les protéines doivent être un nombre positif.";

        if (trim($glucides) === '') $errors[] = "Le champ Glucides est obligatoire.";
        elseif (!is_numeric($glucides) || $glucides < 0) $errors[] = "Les glucides doivent être un nombre positif.";

        if (trim($lipides) === '') $errors[] = "Le champ Lipides est obligatoire.";
        elseif (!is_numeric($lipides) || $lipides < 0) $errors[] = "Les lipides doivent être un nombre positif.";

        // S'il y a des erreurs, on renvoie le tableau d'erreurs
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Sinon, on procède à l'exécution SQL PDO
        $sql = "UPDATE nutrition 
                SET calories = :calories, proteines = :proteines, 
                    glucides = :glucides, lipides = :lipides 
                WHERE idNutrition = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $idNutrition, PDO::PARAM_INT);
            $stmt->bindValue(':calories', floatval($calories));
            $stmt->bindValue(':proteines', floatval($proteines));
            $stmt->bindValue(':glucides', floatval($glucides));
            $stmt->bindValue(':lipides', floatval($lipides));
            
            $result = $stmt->execute();
            if ($result) {
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => ["Erreur lors de la mise à jour SQL."]];
            }
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ["Erreur PDO: " . $e->getMessage()]];
        }
    }
}

// ============================================================
// ROUTING (Gestion manuelle de la 2ème Entité)
// ============================================================

$controller = new NutritionController();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'update':
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idNutrition = (int)$_POST['idNutrition'];
            
            // Les données sont envoyées telles quelles, pour laisser le contrôleur valider
            $result = $controller->updateNutritionValues(
                $idNutrition,
                $_POST['calories'],
                $_POST['proteines'],
                $_POST['glucides'],
                $_POST['lipides']
            );

            if ($result['success']) {
                header('Location: ../view/backoffice/nutrition_dashboard.php?success=updated');
                exit;
            } else {
                // Stocker les erreurs en session pour les afficher sans JS
                $_SESSION['nutrition_errors'] = $result['errors'];
                header('Location: ../view/backoffice/edit_nutrition.php?id=' . $idNutrition);
                exit;
            }
        }
        break;
}
?>
