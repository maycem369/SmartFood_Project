<?php
// backoffice/edit_nutrition.php — Modifier une fiche nutrition
require_once __DIR__ . '/../../controller/NutritionController.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: nutrition_dashboard.php');
    exit;
}

$ctrl    = new NutritionController();
$fiche   = $ctrl->getNutritionById((int)$_GET['id']);

if (!$fiche) {
    die("Fiche nutritionnelle introuvable.");
}

$errors = isset($_SESSION['nutrition_errors']) ? $_SESSION['nutrition_errors'] : [];
unset($_SESSION['nutrition_errors']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Éditer Nutrition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

<div class="dashboard-container">

    <nav class="sidebar">
        <div class="logo">Smart<span>Food</span> <small>Admin</small></div>
        <ul class="nav-links">
            <li><a href="index.php">🥑 Base Ingrédients</a></li>
            <li class="active"><a href="nutrition_dashboard.php">📊 Tableau Nutrition</a></li>
        </ul>
        <div class="sidebar-footer" style="margin-top:auto;padding:16px 12px 24px;border-top:1px solid rgba(255,255,255,0.05);">
            <button class="theme-toggle" aria-label="Changer le thème">
                <span class="toggle-icon icon-moon">&#127769;</span>
                <span class="toggle-icon icon-sun">&#9728;&#65039;</span>
            </button>
        </div>
    </nav>

    <main class="main-content">

        <header class="admin-header">
            <h1>✏️ Modifier la Fiche #<?php echo $fiche['idNutrition']; ?></h1>
            <p>Utilisateur #<?php echo $fiche['id_user']; ?> — Date : <?php echo htmlspecialchars($fiche['date'] ?? '-'); ?></p>
        </header>

        <div style="max-width:700px;">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>⚠️ Erreurs de validation :</strong>
                    <ul style="margin:8px 0 0 20px;font-size:0.9rem;">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <section class="card">
                <h3>Valeurs nutritionnelles</h3>

                <form action="../../../nutrition/controller/NutritionController.php?action=update" method="POST" novalidate>
                    <input type="hidden" name="idNutrition" value="<?php echo $fiche['idNutrition']; ?>">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                        <div class="input-group">
                            <label>Poids (g)</label>
                            <input type="number" name="poids" value="<?php echo $fiche['poids']; ?>" step="0.1" min="0">
                        </div>
                        <div class="input-group">
                            <label>Calories (kcal)</label>
                            <input type="text" name="calories" value="<?php echo $fiche['calories']; ?>">
                        </div>
                        <div class="input-group">
                            <label>Protéines (g)</label>
                            <input type="text" name="proteines" value="<?php echo $fiche['proteines']; ?>">
                        </div>
                        <div class="input-group">
                            <label>Glucides (g)</label>
                            <input type="text" name="glucides" value="<?php echo $fiche['glucides']; ?>">
                        </div>
                        <div class="input-group">
                            <label>Lipides (g)</label>
                            <input type="text" name="lipides" value="<?php echo $fiche['lipides']; ?>">
                        </div>
                    </div>

                    <div style="display:flex;gap:15px;">
                        <button type="submit" class="btn-primary" style="flex:2;">💾 Enregistrer</button>
                        <a href="../../../index.php?action=nutrition_admin&tab=nutrition" class="btn-edit"
                           style="flex:1;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;">
                            ← Annuler
                        </a>
                    </div>
                </form>
            </section>
        </div>

    </main>
</div>

<script src="../js/theme-toggle.js"></script>
</body>
</html>
