<?php
// backoffice/nutrition_dashboard.php — Tableau de bord des fiches nutrition
require_once __DIR__ . '/../../controller/NutritionController.php';

$ctrl      = new NutritionController();
$fiches    = $ctrl->listAllNutrition();
$stats     = $ctrl->getStats();

$successMsg = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'updated': $successMsg = "✅ Fiche mise à jour avec succès !"; break;
        case 'deleted': $successMsg = "✅ Fiche supprimée avec succès !";   break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Tableau Nutrition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

<div class="dashboard-container">

    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">Smart<span>Food</span> <small>Admin</small></div>
        </div>
        <div class="sidebar-section-label">Gestion</div>
        <ul class="nav-links">
            <li><a href="index.php">
                <span class="nav-icon">&#127803;</span>
                <span>Base Ingrédients</span>
            </a></li>
            <li class="active"><a href="nutrition_dashboard.php">
                <span class="nav-icon">&#128202;</span>
                <span>Tableau Nutrition</span>
            </a></li>
        </ul>
        <div class="sidebar-footer">
            <button class="theme-toggle" aria-label="Changer le thème">
                <span class="toggle-icon icon-moon">&#127769;</span>
                <span class="toggle-icon icon-sun">&#9728;&#65039;</span>
            </button>
        </div>
    </nav>

    <main class="main-content">

        <header class="admin-header nutrition-header">
            <div>
                <h1>📊 Tableau de Bord Nutrition</h1>
                <p>Suivi des fiches nutritionnelles des utilisateurs.</p>
            </div>
        </header>

        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <!-- Stats cards -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px;">
            <div class="card" style="padding:20px;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:var(--green-primary);"><?php echo (int)$stats['total']; ?></div>
                <div style="font-size:0.85rem;color:#888;">Fiches totales</div>
            </div>
            <div class="card" style="padding:20px;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#f59e0b;"><?php echo round($stats['avg_cals'] ?? 0, 1); ?></div>
                <div style="font-size:0.85rem;color:#888;">Moy. Calories</div>
            </div>
            <div class="card" style="padding:20px;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#3b82f6;"><?php echo round($stats['avg_prot'] ?? 0, 1); ?></div>
                <div style="font-size:0.85rem;color:#888;">Moy. Protéines (g)</div>
            </div>
            <div class="card" style="padding:20px;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#10b981;"><?php echo round($stats['avg_gluc'] ?? 0, 1); ?></div>
                <div style="font-size:0.85rem;color:#888;">Moy. Glucides (g)</div>
            </div>
            <div class="card" style="padding:20px;text-align:center;">
                <div style="font-size:2rem;font-weight:700;color:#ef4444;"><?php echo round($stats['avg_lip'] ?? 0, 1); ?></div>
                <div style="font-size:0.85rem;color:#888;">Moy. Lipides (g)</div>
            </div>
        </div>

        <!-- Table des fiches -->
        <section class="card">
            <div class="card-header-flex db-table-header">
                <h3>Fiches Nutritionnelles</h3>
                <span class="badge-status">Total: <?php echo count($fiches); ?> fiches</span>
            </div>

            <div class="table-responsive">
                <table class="smart-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Régime</th>
                            <th>Date</th>
                            <th>Poids (g)</th>
                            <th>Calories</th>
                            <th>Protéines</th>
                            <th>Glucides</th>
                            <th>Lipides</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fiches)): ?>
                            <tr>
                                <td colspan="10" style="text-align:center;color:#999;padding:30px;">
                                    Aucune fiche nutritionnelle enregistrée.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fiches as $f): ?>
                            <tr>
                                <td class="db-id">#<?php echo $f['idNutrition']; ?></td>
                                <td>
                                    <?php if (!empty($f['prenom_user'])): ?>
                                        <?php echo htmlspecialchars($f['prenom_user'] . ' ' . $f['nom_user']); ?>
                                    <?php else: ?>
                                        <span style="color:#aaa;">User #<?php echo $f['id_user']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($f['id_regime'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($f['date'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($f['poids'] ?? '-'); ?></td>
                                <td><?php echo round($f['calories'], 1); ?> kcal</td>
                                <td><?php echo round($f['proteines'], 1); ?> g</td>
                                <td><?php echo round($f['glucides'], 1); ?> g</td>
                                <td><?php echo round($f['lipides'], 1); ?> g</td>
                                <td class="actions">
                                    <a href="edit_nutrition.php?id=<?php echo $f['idNutrition']; ?>" class="btn-edit">✏️ Modifier</a>
                                    <a href="../../controller/NutritionController.php?action=delete&id=<?php echo $f['idNutrition']; ?>"
                                       class="btn-delete"
                                       onclick="return confirm('Supprimer cette fiche ?');">
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

<script src="../js/theme-toggle.js"></script>
<script>
setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(a) {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(function() { a.remove(); }, 500);
    });
}, 4000);
</script>

</body>
</html>
