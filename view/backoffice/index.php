<?php
// backoffice/index.php — Vue Back Office: CRUD Ingrédients
require_once __DIR__ . '/../../controller/IngredientController.php';

// Récupérer tous les ingrédients via le contrôleur
$ctrl = new IngredientController();
$ingredients = $ctrl->listIngredients();
$totalIngredients = count($ingredients);

// Messages de succès/erreur
$successMsg = '';
$errorMsg = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added': $successMsg = "✅ Ingrédient ajouté avec succès !"; break;
        case 'updated': $successMsg = "✅ Ingrédient modifié avec succès !"; break;
        case 'deleted': $successMsg = "✅ Ingrédient supprimé avec succès !"; break;
    }
}
if (isset($_GET['error'])) {
    $errorMsg = "❌ Une erreur est survenue. Veuillez réessayer.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Back Office Nutrition</title>
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
                    <h1>⚙️ Gestion de la Base de Données (Nutrition)</h1>
                    <p>Gérez les ingrédients et leurs valeurs nutritionnelles pour le calculateur utilisateur.</p>
                </div>
            </header>

            <!-- Messages de feedback -->
            <?php if ($successMsg): ?>
                <div class="alert alert-success"><?php echo $successMsg; ?></div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <div class="alert alert-error"><?php echo $errorMsg; ?></div>
            <?php endif; ?>

            <!-- Formulaire d'ajout -->
            <section class="card">
                <h3>Ajouter un Nouvel Ingrédient (Valeurs pour 100g)</h3>
                
                <form id="admin-add-food" action="../../controller/IngredientController.php?action=add" method="POST" novalidate>
                    <div class="form-grid db-grid">
                        <div class="input-group">
                            <label for="food_name">Nom de l'aliment</label>
                            <input type="text" id="food_name" name="nom" placeholder="Ex: Riz Basmati">
                            <span class="error-msg" id="error-nom"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_cals">Cals (pour 100g)</label>
                            <input type="text" id="food_cals" name="calories" placeholder="130">
                            <span class="error-msg" id="error-calories"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_prot">Prot (g/100g)</label>
                            <input type="text" id="food_prot" name="proteines" placeholder="2.7">
                            <span class="error-msg" id="error-proteines"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_carbs">Gluc (g/100g)</label>
                            <input type="text" id="food_carbs" name="glucides" placeholder="28">
                            <span class="error-msg" id="error-glucides"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_fat">Lip (g/100g)</label>
                            <input type="text" id="food_fat" name="lipides" placeholder="0.3">
                            <span class="error-msg" id="error-lipides"></span>
                        </div>
                        <div class="input-group btn-container">
                            <button type="submit" id="btn-submit-ingredient" class="btn-primary align-bottom-btn">+ Ajouter à la base</button>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Table des ingrédients -->
            <section class="card">
                <div class="card-header-flex db-table-header">
                    <h3>Ingrédients disponibles dans le Front Office</h3>
                    <span class="badge-status">Total: <?php echo $totalIngredients; ?> Ingrédients</span>
                </div>
                
                <div class="table-responsive">
                    <table class="smart-table">
                        <thead>
                            <tr>
                                <th>ID DB</th>
                                <th>Aliment</th>
                                <th>Cals (100g)</th>
                                <th>Prot (100g)</th>
                                <th>Gluc (100g)</th>
                                <th>Lip (100g)</th>
                                <th>Actions Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ingredients)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; color:#999; padding:30px;">
                                        Aucun ingrédient dans la base de données.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ingredients as $ing): ?>
                                <tr id="row-<?php echo $ing['idIngredient']; ?>">
                                    <td class="db-id">#<?php echo $ing['idIngredient']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($ing['nom']); ?></strong></td>
                                    <td><?php echo $ing['calories']; ?> kcal</td>
                                    <td><?php echo $ing['proteines']; ?> g</td>
                                    <td><?php echo $ing['glucides']; ?> g</td>
                                    <td><?php echo $ing['lipides']; ?> g</td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?php echo $ing['idIngredient']; ?>" class="btn-edit" title="Modifier cet ingrédient">✏️ Modifier</a>
                                        <a href="../../controller/IngredientController.php?action=delete&id=<?php echo $ing['idIngredient']; ?>" 
                                           class="btn-delete" 
                                           title="Retirer de la base de données"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ingrédient ?');">
                                            🗑️ Supprimer
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="../js/validation.js"></script>
    <script>
        // Attacher la validation au formulaire d'ajout
        document.getElementById('admin-add-food').addEventListener('submit', function(e) {
            if (!validateIngredientForm(this)) {
                e.preventDefault();
            }
        });

        // Auto-masquer les alertes après 4 secondes
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 500);
            });
        }, 4000);
    </script>

</body>
</html>
