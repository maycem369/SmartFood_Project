<?php
// backoffice/index.php — Vue Back Office: CRUD Ingrédients
require_once __DIR__ . '/../../controller/IngredientController.php';

$ctrl = new IngredientController();
$ingredients = $ctrl->listIngredients();
$totalIngredients = count($ingredients);

$successMsg = '';
$errorMsg   = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':   $successMsg = "✅ Ingrédient ajouté avec succès !";   break;
        case 'updated': $successMsg = "✅ Ingrédient modifié avec succès !";  break;
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
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/modal.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

<div class="dashboard-container">

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo-mark">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none">
                    <circle cx="14" cy="14" r="13" stroke="#2D6A4F" stroke-width="2" fill="rgba(45,106,79,0.15)"/>
                    <path d="M8 14 Q14 6 20 14 Q14 22 8 14Z" fill="#2D6A4F" opacity="0.8"/>
                    <circle cx="14" cy="14" r="3" fill="#FF8C00"/>
                </svg>
            </div>
            <div class="logo">Smart<span>Food</span> <small>Admin</small></div>
        </div>

        <div class="sidebar-section-label">Gestion</div>
        <ul class="nav-links">
            <li class="active"><a href="index.php">
                <span class="nav-icon">&#127803;</span>
                <span>Base Ingr&eacute;dients</span>
                <span class="nav-badge">DB</span>
            </a></li>
            <li><a href="nutrition_dashboard.php">
                <span class="nav-icon">&#128202;</span>
                <span>Tableau Nutrition</span>
            </a></li>
        </ul>

        <div class="sidebar-footer">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <button class="theme-toggle" aria-label="Changer le thème">
                    <span class="toggle-icon icon-moon">&#127769;</span>
                    <span class="toggle-icon icon-sun">&#9728;&#65039;</span>
                </button>
                <span style="font-size:0.78rem;color:rgba(255,255,255,0.35);font-weight:500;">Thème</span>
            </div>
        </div>
    </nav>

    <main class="main-content">

        <header class="admin-header nutrition-header">
            <div>
                <h1>⚙️ Gestion des Ingrédients</h1>
                <p>Gérez la base d'ingrédients disponibles dans l'application.</p>
            </div>
        </header>

        <!-- Messages -->
        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?php echo $successMsg; ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="alert alert-error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <section class="card">
            <h3>Ajouter un Nouvel Ingrédient</h3>
            <form id="admin-add-food" action="../../controller/IngredientController.php?action=add" method="POST" novalidate>
                <div class="db-form-row db-macros-row">
                    <div class="input-group db-name-field">
                        <label for="food_name">Nom de l'aliment</label>
                        <input type="text" id="food_name" name="nom" placeholder="Ex: Tomate">
                        <span class="error-msg" id="error-nom"></span>
                    </div>
                    <div class="input-group">
                        <label for="food_quantite">Quantité</label>
                        <input type="number" id="food_quantite" name="quantite" placeholder="100" step="0.01" min="0">
                        <span class="error-msg" id="error-quantite"></span>
                    </div>
                    <div class="input-group">
                        <label for="food_unite">Unité</label>
                        <select id="food_unite" name="unite">
                            <option value="g">g</option>
                            <option value="kg">kg</option>
                            <option value="ml">ml</option>
                            <option value="L">L</option>
                            <option value="pièce">pièce</option>
                            <option value="cuillère">cuillère</option>
                        </select>
                    </div>
                </div>
                <div class="db-form-actions">
                    <button type="submit" class="btn-primary">+ Ajouter à la base</button>
                </div>
            </form>
        </section>

        <!-- Table des ingrédients -->
        <section class="card">
            <div class="card-header-flex db-table-header">
                <h3>Ingrédients disponibles</h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span class="badge-status">Total: <?php echo $totalIngredients; ?> Ingrédients</span>
                    <a href="export_csv.php" class="btn-export-csv" title="Télécharger en CSV">&#11123; Export CSV</a>
                </div>
            </div>

            <div class="search-bar">
                <input type="text" id="search-ingredient" placeholder="🔍 Rechercher un ingrédient..." autocomplete="off">
            </div>

            <div class="table-responsive">
                <table class="smart-table" id="ingredients-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Unité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ingredients)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;color:#999;padding:30px;">
                                    Aucun ingrédient dans la base de données.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ingredients as $ing): ?>
                            <tr>
                                <td class="db-id">#<?php echo $ing['idIngredient']; ?></td>
                                <td><strong><?php echo htmlspecialchars($ing['nom']); ?></strong></td>
                                <td><?php echo htmlspecialchars($ing['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($ing['unite']); ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?php echo $ing['idIngredient']; ?>" class="btn-edit" title="Modifier">✏️ Modifier</a>
                                    <a href="../../controller/IngredientController.php?action=delete&id=<?php echo $ing['idIngredient']; ?>"
                                       class="btn-delete"
                                       onclick="return confirm('Supprimer cet ingrédient ?');">
                                        🗑️ Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr id="no-search-results" style="display:none;">
                            <td colspan="5" style="text-align:center;color:#999;padding:30px;">
                                Aucun ingrédient ne correspond à votre recherche.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

<script src="../js/theme-toggle.js"></script>
<script>
// Recherche en temps réel
document.getElementById('search-ingredient').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    var rows = document.querySelectorAll('#ingredients-table tbody tr:not(#no-search-results)');
    var found = 0;
    rows.forEach(function(row) {
        var nom = row.querySelector('td:nth-child(2)');
        if (!nom) return;
        if (nom.textContent.toLowerCase().includes(q)) {
            row.style.display = '';
            found++;
        } else {
            row.style.display = 'none';
        }
    });
    document.getElementById('no-search-results').style.display = found === 0 ? '' : 'none';
});

// Auto-hide alerts
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
