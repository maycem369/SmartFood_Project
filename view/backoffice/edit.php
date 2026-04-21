<?php
// backoffice/edit.php — Vue Back Office: Modifier un Ingrédient
require_once __DIR__ . '/../../controller/IngredientController.php';

session_start();

// Vérifier qu'un ID est passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php?error=no_id');
    exit();
}

$id = intval($_GET['id']);
$ctrl = new IngredientController();
$ing = $ctrl->getIngredientById($id);

// Si l'ingrédient n'existe pas
if (!$ing) {
    header('Location: index.php?error=not_found');
    exit();
}

// Récupérer les erreurs de validation PHP pour la 2ème entité (Nutrition)
$errors = [];
if (isset($_SESSION['nutrition_errors'])) {
    $errors = $_SESSION['nutrition_errors'];
    unset($_SESSION['nutrition_errors']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Modifier Ingrédient</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">

    <div class="dashboard-container">
        <!-- Sidebar du Back Office -->
        <nav class="sidebar admin-sidebar">
            <div class="logo">Smart<span>Food</span><br><small>Espace Nutritionniste</small></div>
            <ul class="nav-links">
                <li><a href="../frontoffice/index.php">⬅️ Retour au Front Office</a></li>
                <li class="active"><a href="index.php">🥑 Base de données Ingrédients</a></li>
                <li><a href="#">⚙️ Validation Recettes</a></li>
            </ul>
        </nav>

        <main class="main-content">
            
            <header class="admin-header nutrition-header">
                <div>
                    <h1>✏️ Modifier l'Ingrédient #<?php echo $ing['idIngredient']; ?></h1>
                    <p>Modifiez les valeurs nutritionnelles de <strong><?php echo htmlspecialchars($ing['nom']); ?></strong>.</p>
                </div>
            </header>

            <section class="card">
                <h3>Modifier les informations (Valeurs pour 100g)</h3>
                
                <?php if (!empty($errors)): ?>
                    <div style="background: rgba(255, 59, 59, 0.1); border-left: 4px solid #ff3b3b; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #ff3b3b;">
                        <strong>Erreurs de validation serveur (PHP) :</strong><br>
                        <?php foreach ($errors as $error): ?>
                            • <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form id="edit-ingredient-form" action="../../controller/NutritionController.php?action=update" method="POST" novalidate>
                    <!-- ID Caché pour Nutrition -->
                    <input type="hidden" name="idNutrition" value="<?php echo $ing['idNutrition'] ?? $ing['idIngredient']; ?>">
                    
                    <div class="form-grid db-grid">
                        <div class="input-group">
                            <label for="food_name">Nom de l'aliment (Lecture seule)</label>
                            <input type="text" id="food_name" name="nom" value="<?php echo htmlspecialchars($ing['nom']); ?>" readonly style="background: #f4f4f4; cursor: not-allowed;">
                        </div>
                        <div class="input-group">
                            <label for="food_cals">Cals (kcal)</label>
                            <input type="text" id="food_cals" name="calories" value="<?php echo $ing['calories']; ?>">
                        </div>
                        <div class="input-group">
                            <label for="food_prot">Prot (g)</label>
                            <input type="text" id="food_prot" name="proteines" value="<?php echo $ing['proteines']; ?>">
                        </div>
                        <div class="input-group">
                            <label for="food_carbs">Gluc (g)</label>
                            <input type="text" id="food_carbs" name="glucides" value="<?php echo $ing['glucides']; ?>">
                        </div>
                        <div class="input-group">
                            <label for="food_fat">Lip (g)</label>
                            <input type="text" id="food_fat" name="lipides" value="<?php echo $ing['lipides']; ?>">
                        </div>
                        <div class="input-group btn-container">
                            <button type="submit" class="btn-primary align-bottom-btn">💾 Sauvegarder (Test PHP)</button>
                        </div>
                    </div>
                </form>

                <div class="profile-action" style="margin-top: 20px; border-bottom: none; padding-bottom: 0;">
                    <a href="index.php" class="btn-secondary" style="text-decoration: none; text-align: center;">← Annuler et retourner</a>
                </div>
            </section>
        </main>
    </div>

    <script src="../js/validation.js"></script>
    <script>
        // Attacher la validation au formulaire de modification
        document.getElementById('edit-ingredient-form').addEventListener('submit', function(e) {
            if (!validateIngredientForm(this)) {
                e.preventDefault();
            }
        });
    </script>

</body>
</html>
