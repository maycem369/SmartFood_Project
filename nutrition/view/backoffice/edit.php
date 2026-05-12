<?php
require_once __DIR__ . '/../../controller/IngredientController.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../../../index.php?action=nutrition_admin&tab=ingredient&error=no_id');
    exit();
}

$id   = intval($_GET['id']);
$ctrl = new IngredientController();
$ing  = $ctrl->getIngredientById($id);

if (!$ing) {
    header('Location: ../../../index.php?action=nutrition_admin&tab=ingredient&error=not_found');
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
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">
<div class="dashboard-container">
    <nav class="sidebar">
        <div class="logo">Smart<span>Food</span> <small>Admin</small></div>
        <ul class="nav-links">
            <li class="active"><a href="../../../index.php?action=nutrition_admin&tab=ingredient">🥑 Base Ingrédients</a></li>
            <li><a href="../../../index.php?action=nutrition_admin&tab=nutrition">📊 Tableau Nutrition</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="admin-header nutrition-header">
            <div>
                <h1>✏️ Modifier l'Ingrédient #<?php echo $ing['idIngredient']; ?></h1>
                <p>Modifiez les valeurs de <strong><?php echo htmlspecialchars($ing['nom']); ?></strong>.</p>
            </div>
        </header>

        <section class="card" style="max-width:650px;">
            <form action="../../../nutrition/controller/IngredientController.php?action=update" method="POST">
                <input type="hidden" name="idIngredient" value="<?php echo $ing['idIngredient']; ?>">

                <div class="input-group" style="margin-bottom:20px;">
                    <label>Nom de l'aliment</label>
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($ing['nom']); ?>" required>
                </div>

                <h4 style="margin:0 0 14px;color:#475569;font-size:.85rem;text-transform:uppercase;letter-spacing:.05em;">
                    Valeurs nutritionnelles (pour 100g)
                </h4>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">
                    <div class="input-group">
                        <label>Calories (kcal)</label>
                        <input type="number" name="calories"  value="<?php echo $ing['calories']  ?? 0; ?>" step="0.1" min="0">
                    </div>
                    <div class="input-group">
                        <label>Protéines (g)</label>
                        <input type="number" name="proteines" value="<?php echo $ing['proteines'] ?? 0; ?>" step="0.1" min="0">
                    </div>
                    <div class="input-group">
                        <label>Glucides (g)</label>
                        <input type="number" name="glucides"  value="<?php echo $ing['glucides']  ?? 0; ?>" step="0.1" min="0">
                    </div>
                    <div class="input-group">
                        <label>Lipides (g)</label>
                        <input type="number" name="lipides"   value="<?php echo $ing['lipides']   ?? 0; ?>" step="0.1" min="0">
                    </div>
                </div>

                <div style="display:flex;gap:15px;">
                    <button type="submit" class="btn-primary" style="flex:2;">💾 Sauvegarder</button>
                    <a href="../../../index.php?action=nutrition_admin&tab=ingredient"
                       class="btn-edit" style="flex:1;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;">
                        ← Annuler
                    </a>
                </div>
            </form>
        </section>
    </main>
</div>
<script src="../js/theme-toggle.js"></script>
</body>
</html>
