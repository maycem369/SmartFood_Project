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
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/modal.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <div class="dashboard-container">
        <!-- Sidebar du Back Office -->
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
                <li><a href="#">
                    <span class="nav-icon">&#9989;</span>
                    <span>Validation Recettes</span>
                </a></li>
                <li><a href="#">
                    <span class="nav-icon">&#128202;</span>
                    <span>Statistiques</span>
                </a></li>
            </ul>

            <div class="sidebar-section-label">Compte</div>
            <ul class="nav-links">
                <li><a href="#">
                    <span class="nav-icon">&#9881;</span>
                    <span>Param&egrave;tres</span>
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
                <a href="../frontoffice/index.php" class="sidebar-back-btn">
                    <span>&#8592;</span> Front Office
                </a>
            </div>
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
                    <div class="db-form-row">
                        <div class="input-group db-name-field">
                            <label for="food_name">Nom de l'aliment</label>
                            <input type="text" id="food_name" name="nom" placeholder="Ex: Riz Basmati">
                            <span class="error-msg" id="error-nom"></span>
                        </div>
                    </div>
                    <div class="db-form-row db-macros-row">
                        <div class="input-group">
                            <label for="food_cals">Calories (100g)</label>
                            <input type="text" id="food_cals" name="calories" placeholder="130">
                            <span class="error-msg" id="error-calories"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_prot">Protéines (g)</label>
                            <input type="text" id="food_prot" name="proteines" placeholder="2.7">
                            <span class="error-msg" id="error-proteines"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_carbs">Glucides (g)</label>
                            <input type="text" id="food_carbs" name="glucides" placeholder="28">
                            <span class="error-msg" id="error-glucides"></span>
                        </div>
                        <div class="input-group">
                            <label for="food_fat">Lipides (g)</label>
                            <input type="text" id="food_fat" name="lipides" placeholder="0.3">
                            <span class="error-msg" id="error-lipides"></span>
                        </div>
                    </div>
                    <div class="db-form-actions">
                        <button type="submit" id="btn-submit-ingredient" class="btn-primary">+ Ajouter à la base</button>
                    </div>
                </form>
            </section>

            <!-- Table des ingrédients -->
            <section class="card">
                <div class="card-header-flex db-table-header">
                    <h3>Ingrédients disponibles dans le Front Office</h3>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <span class="badge-status">Total: <?php echo $totalIngredients; ?> Ingrédients</span>
                        <a href="export_csv.php" class="btn-export-csv" title="Télécharger tous les ingrédients en CSV">
                            &#11123; Export CSV
                        </a>
                    </div>
                </div>

                <!-- Barre de recherche -->
                <div class="search-bar">
                    <input type="text" id="search-ingredient" placeholder="🔍 Rechercher un ingrédient..." autocomplete="off">
                </div>
                
                <div class="table-responsive">
                    <table class="smart-table">
                        <thead>
                            <tr>
                                <th data-sort="id" data-col="0" data-type="number">ID DB ↕</th>
                                <th data-sort="nom" data-col="1" data-type="text">Aliment ↕</th>
                                <th data-sort="cals" data-col="2" data-type="number">Cals (100g) ↕</th>
                                <th data-sort="prot" data-col="3" data-type="number">Prot (100g) ↕</th>
                                <th data-sort="gluc" data-col="4" data-type="number">Gluc (100g) ↕</th>
                                <th data-sort="lip" data-col="5" data-type="number">Lip (100g) ↕</th>
                                <th>Actions Admin</th>
                                <th>Aperçu</th>
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
                                <tr id="row-<?php echo $ing['idIngredient']; ?>"
                                    data-ing-id="<?php echo $ing['idIngredient']; ?>"
                                    data-ing-nom="<?php echo htmlspecialchars($ing['nom'], ENT_QUOTES); ?>"
                                    data-ing-cals="<?php echo $ing['calories']; ?>"
                                    data-ing-prot="<?php echo $ing['proteines']; ?>"
                                    data-ing-gluc="<?php echo $ing['glucides']; ?>"
                                    data-ing-lip="<?php echo $ing['lipides']; ?>">
                                    <td class="db-id">#<?php echo $ing['idIngredient']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($ing['nom']); ?></strong></td>
                                    <td><?php echo round($ing['calories'], 1); ?> kcal</td>
                                    <td><?php echo round($ing['proteines'], 1); ?> g</td>
                                    <td><?php echo round($ing['glucides'], 1); ?> g</td>
                                    <td><?php echo round($ing['lipides'], 1); ?> g</td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?php echo $ing['idIngredient']; ?>" class="btn-edit" title="Modifier cet ingrédient">✏️ Modifier</a>
                                        <a href="../../controller/IngredientController.php?action=delete&id=<?php echo $ing['idIngredient']; ?>" 
                                           class="btn-delete" 
                                           title="Retirer de la base de données"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ingrédient ?');">
                                            🗑️ Supprimer
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn-quickview" onclick="openIngredientModal(this)" title="Aperçu rapide">&#128065; Voir</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <tr id="no-search-results" style="display:none;">
                                <td colspan="7" style="text-align:center; color:#999; padding:30px;">
                                    Aucun ingrédient ne correspond à votre recherche.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- ============================================================
         INGREDIENT QUICK-VIEW MODAL
         ============================================================ -->
    <div id="ingredient-modal" class="modal-overlay" onclick="closeModalIfOutside(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeIngredientModal()">&times;</button>

            <div class="modal-header">
                <div class="modal-icon">&#127803;</div>
                <div>
                    <h2 class="modal-title" id="modal-ing-name">Nom Ingrédient</h2>
                    <p class="modal-subtitle">Valeurs nutritionnelles pour <strong>100g</strong></p>
                </div>
            </div>

            <div class="modal-body">
                <!-- Macro stat pills -->
                <div class="modal-stats">
                    <div class="modal-stat modal-stat-cals">
                        <span class="modal-stat-icon">&#128293;</span>
                        <div>
                            <span class="modal-stat-value" id="modal-cals">0</span>
                            <span class="modal-stat-label">kcal</span>
                        </div>
                        <span class="modal-stat-name">Calories</span>
                    </div>
                    <div class="modal-stat modal-stat-prot">
                        <span class="modal-stat-icon">&#128169;</span>
                        <div>
                            <span class="modal-stat-value" id="modal-prot">0</span>
                            <span class="modal-stat-label">g</span>
                        </div>
                        <span class="modal-stat-name">Protéines</span>
                    </div>
                    <div class="modal-stat modal-stat-gluc">
                        <span class="modal-stat-icon">&#127807;</span>
                        <div>
                            <span class="modal-stat-value" id="modal-gluc">0</span>
                            <span class="modal-stat-label">g</span>
                        </div>
                        <span class="modal-stat-name">Glucides</span>
                    </div>
                    <div class="modal-stat modal-stat-lip">
                        <span class="modal-stat-icon">&#129361;</span>
                        <div>
                            <span class="modal-stat-value" id="modal-lip">0</span>
                            <span class="modal-stat-label">g</span>
                        </div>
                        <span class="modal-stat-name">Lipides</span>
                    </div>
                </div>

                <!-- Macro Pie Chart -->
                <div class="modal-chart-wrapper">
                    <canvas id="modal-macro-chart" width="220" height="220"></canvas>
                    <div class="modal-chart-legend">
                        <div class="legend-item"><span class="legend-dot" style="background:#3b82f6"></span>Protéines</div>
                        <div class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span>Glucides</div>
                        <div class="legend-item"><span class="legend-dot" style="background:#10b981"></span>Lipides</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a id="modal-edit-link" href="#" class="btn-primary" style="text-decoration:none;display:inline-block;text-align:center;">✏️ Modifier cet ingrédient</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="../js/theme-toggle.js"></script>
    <script src="../js/validation.js"></script>
    <script src="../js/metier.js"></script>
    <script>
        // ============================================================
        // FORM VALIDATION
        // ============================================================
        document.getElementById('admin-add-food').addEventListener('submit', function(e) {
            if (!validateIngredientForm(this)) {
                e.preventDefault();
            }
        });

        // Auto-hide alerts after 4s
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 500);
            });
        }, 4000);

        // ============================================================
        // QUICK-VIEW MODAL
        // ============================================================
        var macroChart = null;

        function openIngredientModal(btn) {
            var row = btn.closest('tr');
            var nom  = row.getAttribute('data-ing-nom');
            var cals = parseFloat(row.getAttribute('data-ing-cals')) || 0;
            var prot = parseFloat(row.getAttribute('data-ing-prot')) || 0;
            var gluc = parseFloat(row.getAttribute('data-ing-gluc')) || 0;
            var lip  = parseFloat(row.getAttribute('data-ing-lip'))  || 0;
            var id   = row.getAttribute('data-ing-id');

            // Populate text fields
            document.getElementById('modal-ing-name').textContent = nom;
            document.getElementById('modal-cals').textContent = cals;
            document.getElementById('modal-prot').textContent = prot;
            document.getElementById('modal-gluc').textContent = gluc;
            document.getElementById('modal-lip').textContent  = lip;
            document.getElementById('modal-edit-link').href = 'edit.php?id=' + id;

            // Build/update Chart.js pie chart
            var ctx = document.getElementById('modal-macro-chart').getContext('2d');
            var total = prot + gluc + lip;
            var data = {
                labels: ['Protéines', 'Glucides', 'Lipides'],
                datasets: [{
                    data: total > 0 ? [prot, gluc, lip] : [1, 1, 1],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            };

            if (macroChart) {
                macroChart.data = data;
                macroChart.update();
            } else {
                macroChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        cutout: '65%',
                        responsive: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        var t = ctx.dataset.data.reduce(function(a,b){return a+b;},0);
                                        return ' ' + ctx.label + ': ' + ctx.raw + 'g (' + (t>0?Math.round(ctx.raw/t*100):0) + '%)';
                                    }
                                }
                            }
                        },
                        animation: { animateRotate: true, duration: 500, easing: 'easeOutQuart' }
                    }
                });
            }

            // Show modal with animation
            var modal = document.getElementById('ingredient-modal');
            modal.style.display = 'flex';
            requestAnimationFrame(function() { modal.classList.add('modal-visible'); });
        }

        function closeIngredientModal() {
            var modal = document.getElementById('ingredient-modal');
            modal.classList.remove('modal-visible');
            setTimeout(function() { modal.style.display = 'none'; }, 300);
        }

        function closeModalIfOutside(event) {
            if (event.target === document.getElementById('ingredient-modal')) {
                closeIngredientModal();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeIngredientModal();
        });
    </script>

</body>
</html>
