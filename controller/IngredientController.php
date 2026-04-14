<?php
// controller/IngredientController.php — Contrôleur CRUD pour Ingredient

require_once __DIR__ . '/../model/Ingredient.php';
require_once __DIR__ . '/../model/Nutrition.php';

// Déterminer l'action demandée
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {

    // ========================
    // CREATE — Ajouter un ingrédient
    // ========================
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Sauvegarder l'ingrédient
            $ingredient = new Ingredient();
            $ingredient->setNom(trim($_POST['nom']));
            $ingredient->setQuantite(isset($_POST['quantite']) ? intval($_POST['quantite']) : 1);

            $newId = $ingredient->addIngredient($ingredient);

            if ($newId) {
                // 2. Sauvegarder la nutrition liée
                $nutrition = new Nutrition();
                $nutrition->setIdIngredient($newId);
                $nutrition->setCalories(floatval($_POST['calories']));
                $nutrition->setProteines(floatval($_POST['proteines']));
                $nutrition->setGlucides(floatval($_POST['glucides']));
                $nutrition->setLipides(floatval($_POST['lipides']));
                $nutrition->setPoids(100); // Toujours 100g comme demandé

                if ($nutrition->addNutrition($nutrition)) {
                    header('Location: ../view/backoffice/index.php?success=added');
                } else {
                    header('Location: ../view/backoffice/index.php?error=nutrition_add_failed');
                }
            } else {
                header('Location: ../view/backoffice/index.php?error=ingredient_add_failed');
            }
        }
        break;

    // ========================
    // UPDATE — Modifier un ingrédient
    // ========================
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['idIngredient']);

            // 1. Modifier l'ingrédient
            $ingredient = new Ingredient();
            $ingredient->setIdIngredient($id);
            $ingredient->setNom(trim($_POST['nom']));
            $ingredient->setQuantite(intval($_POST['quantite']));

            if ($ingredient->updateIngredient($ingredient)) {
                // 2. Modifier la nutrition liée
                $nutrition = new Nutrition();
                $nutrition->setIdIngredient($id);
                $nutrition->setCalories(floatval($_POST['calories']));
                $nutrition->setProteines(floatval($_POST['proteines']));
                $nutrition->setGlucides(floatval($_POST['glucides']));
                $nutrition->setLipides(floatval($_POST['lipides']));
                $nutrition->setPoids(100);

                if ($nutrition->updateNutrition($nutrition)) {
                    header('Location: ../view/backoffice/index.php?success=updated');
                } else {
                    header('Location: ../view/backoffice/index.php?error=nutrition_update_failed');
                }
            } else {
                header('Location: ../view/backoffice/index.php?error=ingredient_update_failed');
            }
        }
        break;

    // ========================
    // DELETE — Supprimer un ingrédient
    // ========================
    case 'delete':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $ingredient = new Ingredient();

            // ON DELETE CASCADE s'occupe de la table nutrition
            if ($ingredient->deleteIngredient($id)) {
                header('Location: ../view/backoffice/index.php?success=deleted');
            } else {
                header('Location: ../view/backoffice/index.php?error=delete_failed');
            }
        }
        break;

    default:
        header('Location: ../view/backoffice/index.php');
        break;
}

exit();
?>
