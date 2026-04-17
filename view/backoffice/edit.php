<?php
// backoffice/edit.php — Vue Back Office: Modifier un Ingrédient
require_once __DIR__ . '/../../controller/IngredientController.php';

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
                
                <form id="edit-ingredient-form" action="../../controller/IngredientController.php?action=update" method="POST" novalidate>
                    <!-- ID caché -->
                    <input type="hidden" name="idIngredient" value="<?php echo $ing['idIngredient']; ?>">
                    
                    <div class="form-grid db-grid">
                        <div class="input-group">
                            <label for="food_name">Nom de l'aliment</label>
                            <input type="text" id="food_name" name="nom" value="<?php echo htmlspecialchars($ing['nom']); ?>">
                            <span class="error-msg" id="error-nom"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_cals">Cals (pour 100g)</label>
                            <input type="text" id="food_cals" name="calories" value="<?php echo $ing['calories']; ?>">
                            <span class="error-msg" id="error-calories"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_prot">Prot (g/100g)</label>
                            <input type="text" id="food_prot" name="proteines" value="<?php echo $ing['proteines']; ?>">
                            <span class="error-msg" id="error-proteines"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_carbs">Gluc (g/100g)</label>
                            <input type="text" id="food_carbs" name="glucides" value="<?php echo $ing['glucides']; ?>">
                            <span class="error-msg" id="error-glucides"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_fat">Lip (g/100g)</label>
                            <input type="text" id="food_fat" name="lipides" value="<?php echo $ing['lipides']; ?>">
                            <span class="error-msg" id="error-lipides"></span>
                        </div>
                        <div class="input-group btn-container">
                            <button type="submit" class="btn-primary align-bottom-btn">💾 Sauvegarder</button>
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
