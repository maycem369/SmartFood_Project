<?php
// ── Guard admin ───────────────────────────────────────────────────────────────
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php?action=login"); exit();
}

// ── Charger les contrôleurs nutrition ────────────────────────────────────────
require_once __DIR__ . '/../../nutrition/controller/IngredientController.php';
require_once __DIR__ . '/../../nutrition/controller/NutritionController.php';

$ingCtrl  = new IngredientController();
$nutCtrl  = new NutritionController();

$ingredients      = $ingCtrl->listIngredients();
$totalIngredients = count($ingredients);
$fiches           = $nutCtrl->listAllNutrition();
$stats            = $nutCtrl->getStats();

// Onglet actif (ingredient | nutrition)
$tab = $_GET['tab'] ?? 'ingredient';

// Messages
$successMsg = '';
$errorMsg   = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':   $successMsg = "✅ Ingrédient ajouté avec succès !";   break;
        case 'updated': $successMsg = "✅ Modifié avec succès !";             break;
        case 'deleted': $successMsg = "✅ Supprimé avec succès !";            break;
    }
}
if (isset($_GET['error'])) {
    $errorMsg = "❌ Une erreur est survenue. Veuillez réessayer.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Nutrition – SmartFood Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f0f4f3; }
        .main-content { margin-left: var(--sidebar-w); padding: 30px; }
        .sidebar { width: var(--sidebar-w); }

        /* ── Onglets ── */
        .nutrition-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0;
        }
        .nutrition-tabs a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            font-size: 0.92rem;
            color: #64748b;
            text-decoration: none;
            border: 2px solid transparent;
            border-bottom: none;
            margin-bottom: -2px;
            transition: all .2s;
        }
        .nutrition-tabs a:hover { background: #f1f5f9; color: #2D6A4F; }
        .nutrition-tabs a.active {
            background: white;
            color: #2D6A4F;
            border-color: #e2e8f0;
            border-bottom-color: white;
        }

        /* ── Tables & Cards ── */
        .nut-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.06); margin-bottom: 20px; }
        .nut-card h3 { margin: 0 0 18px; font-size: 1rem; color: #1e293b; }
        .nut-form-row { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; }
        .nut-form-row .fg { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 140px; }
        .nut-form-row label { font-size: .82rem; font-weight: 600; color: #475569; }
        .nut-form-row input, .nut-form-row select {
            padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: .9rem; outline: none; transition: border .2s;
        }
        .nut-form-row input:focus, .nut-form-row select:focus { border-color: #2D6A4F; }
        .btn-add { background: #2D6A4F; color: white; border: none; padding: 10px 22px;
                   border-radius: 10px; font-weight: 600; cursor: pointer; white-space: nowrap; }
        .btn-add:hover { background: #245a41; }

        .nut-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
        .nut-table th { background: #f8fafc; padding: 10px 14px; text-align: left;
                        font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; }
        .nut-table th:hover { background: #f1f5f9; color: #2D6A4F; }
        .sort-icon { font-size: .75rem; color: #cbd5e1; margin-left: 4px; transition: color .2s; }
        .nut-table td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .nut-table tr:last-child td { border-bottom: none; }
        .nut-table tr:hover td { background: #f8fafc; }

        .btn-edit-sm  { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;
                        padding: 5px 12px; border-radius: 8px; font-size: .8rem; font-weight: 600;
                        text-decoration: none; margin-right: 6px; }
        .btn-del-sm   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
                        padding: 5px 12px; border-radius: 8px; font-size: .8rem; font-weight: 600;
                        text-decoration: none; }
        .btn-edit-sm:hover { background: #fed7aa; }
        .btn-del-sm:hover  { background: #fecaca; }

        .search-input { width: 100%; padding: 9px 14px; border: 1.5px solid #e2e8f0;
                        border-radius: 10px; font-size: .9rem; margin-bottom: 16px; outline: none; }
        .search-input:focus { border-color: #2D6A4F; }

        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-box  { background: white; border-radius: 14px; padding: 18px; text-align: center;
                     box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .stat-box .val { font-size: 1.8rem; font-weight: 700; }
        .stat-box .lbl { font-size: .78rem; color: #94a3b8; margin-top: 4px; }

        .badge-total { background: #dcfce7; color: #166534; padding: 4px 12px;
                       border-radius: 20px; font-size: .78rem; font-weight: 700; }
        .btn-csv { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
                   padding: 7px 16px; border-radius: 10px; font-size: .82rem; font-weight: 600;
                   text-decoration: none; }
        .btn-csv:hover { background: #dcfce7; }
        .tbl-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .alert-ok  { background: #dcfce7; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-weight: 600; }
        .alert-err { background: #fef2f2; color: #dc2626; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-weight: 600; }

        /* ── Dark Mode ── */
        [data-theme="dark"] body { background-color: #0f1a16; }
        [data-theme="dark"] .main-content { background-color: #0f1a16; }

        [data-theme="dark"] .nut-card {
            background: #1a2b23;
            box-shadow: 0 2px 12px rgba(0,0,0,.3);
        }
        [data-theme="dark"] .nut-card h3 { color: #e8f0ec; }

        [data-theme="dark"] .nutrition-tabs { border-bottom-color: #2a3d34; }
        [data-theme="dark"] .nutrition-tabs a { color: #8ca99a; }
        [data-theme="dark"] .nutrition-tabs a:hover { background: #1a2b23; color: #74c69d; }
        [data-theme="dark"] .nutrition-tabs a.active {
            background: #1a2b23;
            color: #74c69d;
            border-color: #2a3d34;
            border-bottom-color: #1a2b23;
        }

        [data-theme="dark"] .nut-form-row label { color: #8ca99a; }
        [data-theme="dark"] .nut-form-row input,
        [data-theme="dark"] .nut-form-row select {
            background: #111d18;
            border-color: #2a3d34;
            color: #e8f0ec;
        }
        [data-theme="dark"] .nut-form-row input:focus,
        [data-theme="dark"] .nut-form-row select:focus { border-color: #74c69d; }

        [data-theme="dark"] .nut-table th {
            background: #111d18;
            color: #8ca99a;
        }
        [data-theme="dark"] .nut-table th:hover { background: #1a2b23; color: #74c69d; }
        [data-theme="dark"] .nut-table td {
            border-bottom-color: #1a2b23;
            color: #e8f0ec;
        }
        [data-theme="dark"] .nut-table tr:hover td { background: #1f3a2a; }

        [data-theme="dark"] .btn-edit-sm {
            background: #1f2d1a;
            color: #fb923c;
            border-color: #7c3a1a;
        }
        [data-theme="dark"] .btn-del-sm {
            background: #2d1a1a;
            color: #f87171;
            border-color: #7c1a1a;
        }

        [data-theme="dark"] .search-input {
            background: #111d18;
            border-color: #2a3d34;
            color: #e8f0ec;
        }
        [data-theme="dark"] .search-input:focus { border-color: #74c69d; }

        [data-theme="dark"] .stat-box {
            background: #1a2b23;
            box-shadow: 0 2px 10px rgba(0,0,0,.3);
        }
        [data-theme="dark"] .stat-box .lbl { color: #8ca99a; }

        [data-theme="dark"] .badge-total { background: #1f3a2a; color: #74c69d; }
        [data-theme="dark"] .btn-csv {
            background: #1f3a2a;
            color: #74c69d;
            border-color: #2a5a3a;
        }
        [data-theme="dark"] .tbl-header h3 { color: #e8f0ec; }

        [data-theme="dark"] .alert-ok  { background: #1f3a2a; color: #74c69d; }
        [data-theme="dark"] .alert-err { background: #2d1a1a; color: #f87171; }

        /* Modal dark */
        [data-theme="dark"] #edit-modal > div {
            background: #1a2b23;
            color: #e8f0ec;
        }
        [data-theme="dark"] #edit-modal label { color: #8ca99a; }
        [data-theme="dark"] #edit-modal input[type="text"],
        [data-theme="dark"] #edit-modal input[type="number"] {
            background: #111d18;
            border-color: #2a3d34;
            color: #e8f0ec;
        }
        [data-theme="dark"] #modal-subtitle { color: #8ca99a; }
        [data-theme="dark"] #edit-modal button[type="button"] {
            background: #111d18;
            color: #8ca99a;
        }

        [data-theme="dark"] h1.page-title { color: #e8f0ec; }
        [data-theme="dark"] p { color: #8ca99a; }
    </style>
</head>
<body>

<!-- ── Sidebar Admin ── -->
<div class="sidebar admin-sidebar">
    <div class="logo">
        <h1 style="color:white;">Smart<span>Food</span></h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">
            <i class="fas fa-chart-line"></i> <span data-i18n="admin_nav_dashboard">Vue d'ensemble</span>
        </a></li>
        <li><a href="index.php?action=users_list">
            <i class="fas fa-user-shield"></i> <span data-i18n="admin_nav_users">Utilisateurs</span>
        </a></li>
        <li><a href="index.php?action=recettes_admin">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes &amp; Menus</span>
        </a></li>
        <li><a href="index.php?action=nutrition_admin" class="active">
            <i class="fas fa-apple-alt"></i> <span data-i18n="admin_nav_nutrition">Nutrition</span>
        </a></li>
        <li><a href="index.php?action=admin_configuration">
            <i class="fas fa-cog"></i> <span data-i18n="admin_nav_config">Configuration</span>
        </a></li>
    </ul>
    <div class="switch-mode" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <a href="index.php?action=logout" class="admin-link" style="color: var(--text-sidebar);">
            <i class="fas fa-sign-out-alt"></i> <span data-i18n="nav_logout">Déconnexion</span>
        </a>
    </div>
</div>

<!-- ── Contenu principal ── -->
<div class="main-content">

    <h1 class="page-title" style="margin-bottom:6px;">
        <i class="fas fa-apple-alt" style="color:#2D6A4F;"></i> <span data-i18n="nut_title">Nutrition</span>
    </h1>
    <p style="color:#94a3b8;margin-bottom:24px;font-size:.9rem;" data-i18n="nut_subtitle">Gérez les ingrédients et leurs valeurs nutritionnelles.</p>

    <!-- Messages -->
    <?php if ($successMsg): ?>
        <div class="alert-ok"><?php echo $successMsg; ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert-err"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <!-- ── Onglet unique : Base Ingrédients ── -->
    <div class="nutrition-tabs">
        <a href="index.php?action=nutrition_admin&tab=ingredient" class="active">
            <i class="fas fa-seedling"></i> <span data-i18n="nut_tab_ingredients">Base Ingrédients</span>
        </a>
    </div>

    <!-- ══════════════════════════════════════════════
         BASE INGRÉDIENTS
    ══════════════════════════════════════════════ -->
    <?php if (true): // toujours affiché ?>

        <!-- Formulaire ajout -->
        <div class="nut-card">
            <h3><i class="fas fa-plus-circle" style="color:#2D6A4F;"></i> <span data-i18n="nut_add_title">Ajouter un Nouvel Ingrédient</span></h3>
            <form method="POST" action="nutrition/controller/IngredientController.php?action=add">
                <div class="nut-form-row">
                    <div class="fg" style="flex:3;">
                        <label data-i18n="nut_modal_nom">Nom de l'aliment</label>
                        <input type="text" name="nom" placeholder="Ex: Poulet" required>
                    </div>
                    <div class="fg">
                        <label data-i18n="nut_modal_cals">Calories (kcal/100g)</label>
                        <input type="number" name="calories" placeholder="0" step="0.1" min="0" value="0">
                    </div>
                    <div class="fg">
                        <label data-i18n="nut_modal_prot">Protéines (g/100g)</label>
                        <input type="number" name="proteines" placeholder="0" step="0.1" min="0" value="0">
                    </div>
                    <div class="fg">
                        <label data-i18n="nut_modal_gluc">Glucides (g/100g)</label>
                        <input type="number" name="glucides" placeholder="0" step="0.1" min="0" value="0">
                    </div>
                    <div class="fg">
                        <label data-i18n="nut_modal_lip">Lipides (g/100g)</label>
                        <input type="number" name="lipides" placeholder="0" step="0.1" min="0" value="0">
                    </div>
                    <div class="fg" style="flex:0;">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn-add" data-i18n="nut_btn_add">+ Ajouter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table ingrédients -->
        <div class="nut-card">
            <div class="tbl-header">
                <h3 style="margin:0;"><i class="fas fa-database" style="color:#2D6A4F;"></i> Ingrédients disponibles</h3>
                <div style="display:flex;gap:10px;align-items:center;">
                    <span class="badge-total"><?php echo $totalIngredients; ?> ingrédients</span>
                    <a href="nutrition/view/backoffice/export_csv.php" class="btn-csv">⬇ Export CSV</a>
                </div>
            </div>

            <input type="text" id="search-ing" class="search-input" placeholder="🔍 Rechercher un ingrédient...">

            <table class="nut-table" id="ing-table">
                <thead>
                    <tr>
                        <th onclick="sortTable(0,'num')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_id">ID</span> <span class="sort-icon">↕</span></th>
                        <th onclick="sortTable(1,'str')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_nom">Nom</span> <span class="sort-icon">↕</span></th>
                        <th onclick="sortTable(2,'num')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_cals">Calories (kcal)</span> <span class="sort-icon">↕</span></th>
                        <th onclick="sortTable(3,'num')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_prot">Protéines (g)</span> <span class="sort-icon">↕</span></th>
                        <th onclick="sortTable(4,'num')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_gluc">Glucides (g)</span> <span class="sort-icon">↕</span></th>
                        <th onclick="sortTable(5,'num')"  style="cursor:pointer;user-select:none;"><span data-i18n="nut_col_lip">Lipides (g)</span> <span class="sort-icon">↕</span></th>
                        <th data-i18n="nut_col_actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ingredients)): ?>
                        <tr><td colspan="7" style="text-align:center;color:#aaa;padding:30px;">Aucun ingrédient.</td></tr>
                    <?php else: ?>
                        <?php foreach ($ingredients as $ing): ?>
                        <tr>
                            <td style="color:#94a3b8;font-size:.8rem;">#<?php echo $ing['idIngredient']; ?></td>
                            <td><strong><?php echo htmlspecialchars($ing['nom']); ?></strong></td>
                            <td><?php echo round($ing['calories']  ?? 0, 1); ?></td>
                            <td><?php echo round($ing['proteines'] ?? 0, 1); ?></td>
                            <td><?php echo round($ing['glucides']  ?? 0, 1); ?></td>
                            <td><?php echo round($ing['lipides']   ?? 0, 1); ?></td>
                            <td>
                                <button class="btn-edit-sm"
                                    onclick="openEditModal(<?php echo $ing['idIngredient']; ?>, '<?php echo addslashes($ing['nom']); ?>', <?php echo floatval($ing['calories'] ?? 0); ?>, <?php echo floatval($ing['proteines'] ?? 0); ?>, <?php echo floatval($ing['glucides'] ?? 0); ?>, <?php echo floatval($ing['lipides'] ?? 0); ?>)">
                                    ✏️ Modifier
                                </button>
                                <a href="nutrition/controller/IngredientController.php?action=delete&id=<?php echo $ing['idIngredient']; ?>"
                                   class="btn-del-sm"
                                   onclick="return confirm('Supprimer cet ingrédient ?');">🗑️ Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr id="no-results" style="display:none;">
                        <td colspan="7" style="text-align:center;color:#aaa;padding:20px;">Aucun résultat.</td>
                    </tr>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <!-- ══════════════════════════════════════════════
         ONGLET 2 — TABLEAU NUTRITION (supprimé)
    ══════════════════════════════════════════════ -->

</div><!-- /.main-content -->

<!-- ══════════════════════════════════════════════
     MODAL MODIFICATION INGRÉDIENT
══════════════════════════════════════════════ --><div id="edit-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;padding:32px;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,.2);position:relative;">

        <button onclick="closeEditModal()"
                style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.4rem;cursor:pointer;color:#94a3b8;">✕</button>

        <h2 style="margin:0 0 6px;font-size:1.1rem;color:#1e293b;" data-i18n="nut_modal_title">✏️ Modifier l'Ingrédient</h2>
        <p id="modal-subtitle" style="margin:0 0 24px;color:#94a3b8;font-size:.85rem;"></p>

        <form id="modal-edit-form" method="POST" action="nutrition/controller/IngredientController.php?action=update">
            <input type="hidden" name="idIngredient" id="modal-id">

            <div style="margin-bottom:16px;">
                <label data-i18n="nut_modal_nom" style="display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:6px;">Nom de l'aliment</label>
                <input type="text" name="nom" id="modal-nom" required
                       style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;box-sizing:border-box;outline:none;">
            </div>

            <p data-i18n="nut_label_per100" style="font-size:.78rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 12px;">
                Valeurs nutritionnelles (pour 100g)
            </p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px;">
                <div>
                    <label data-i18n="nut_modal_cals" style="display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:6px;">Calories (kcal)</label>
                    <input type="number" name="calories" id="modal-calories" step="0.1" min="0"
                           style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;box-sizing:border-box;outline:none;">
                </div>
                <div>
                    <label data-i18n="nut_modal_prot" style="display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:6px;">Protéines (g)</label>
                    <input type="number" name="proteines" id="modal-proteines" step="0.1" min="0"
                           style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;box-sizing:border-box;outline:none;">
                </div>
                <div>
                    <label data-i18n="nut_modal_gluc" style="display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:6px;">Glucides (g)</label>
                    <input type="number" name="glucides" id="modal-glucides" step="0.1" min="0"
                           style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;box-sizing:border-box;outline:none;">
                </div>
                <div>
                    <label data-i18n="nut_modal_lip" style="display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:6px;">Lipides (g)</label>
                    <input type="number" name="lipides" id="modal-lipides" step="0.1" min="0"
                           style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;box-sizing:border-box;outline:none;">
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" data-i18n="nut_modal_save" class="btn-add" style="flex:2;padding:12px;">💾 Sauvegarder</button>
                <button type="button" data-i18n="nut_modal_cancel" onclick="closeEditModal()"
                        style="flex:1;padding:12px;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;font-weight:600;cursor:pointer;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, nom, calories, proteines, glucides, lipides) {
    document.getElementById('modal-id').value       = id;
    document.getElementById('modal-nom').value      = nom;
    document.getElementById('modal-calories').value = calories;
    document.getElementById('modal-proteines').value= proteines;
    document.getElementById('modal-glucides').value = glucides;
    document.getElementById('modal-lipides').value  = lipides;
    document.getElementById('modal-subtitle').textContent = 'Modification de : ' + nom;

    var modal = document.getElementById('edit-modal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}

// Fermer en cliquant sur le fond
document.getElementById('edit-modal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

// Tri des colonnes
var sortState = { col: -1, asc: true };

function sortTable(colIndex, type) {
    var table  = document.getElementById('ing-table');
    var tbody  = table.querySelector('tbody');
    var rows   = Array.from(tbody.querySelectorAll('tr:not(#no-results)'));
    var ths    = table.querySelectorAll('thead th');

    // Inverser si même colonne
    if (sortState.col === colIndex) {
        sortState.asc = !sortState.asc;
    } else {
        sortState.col = colIndex;
        sortState.asc = true;
    }

    // Mettre à jour les icônes
    ths.forEach(function(th, i) {
        var icon = th.querySelector('.sort-icon');
        if (!icon) return;
        if (i === colIndex) {
            icon.textContent = sortState.asc ? '↑' : '↓';
            icon.style.color = '#2D6A4F';
        } else {
            icon.textContent = '↕';
            icon.style.color = '#cbd5e1';
        }
    });

    rows.sort(function(a, b) {
        var aVal = a.querySelectorAll('td')[colIndex]?.textContent.trim() || '';
        var bVal = b.querySelectorAll('td')[colIndex]?.textContent.trim() || '';

        if (type === 'num') {
            aVal = parseFloat(aVal) || 0;
            bVal = parseFloat(bVal) || 0;
            return sortState.asc ? aVal - bVal : bVal - aVal;
        } else {
            return sortState.asc
                ? aVal.localeCompare(bVal, 'fr', { sensitivity: 'base' })
                : bVal.localeCompare(aVal, 'fr', { sensitivity: 'base' });
        }
    });

    rows.forEach(function(row) { tbody.appendChild(row); });
    // Remettre la ligne "no-results" à la fin
    var noRes = document.getElementById('no-results');
    if (noRes) tbody.appendChild(noRes);
}

// Recherche ingrédients
var searchInput = document.getElementById('search-ing');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        var rows = document.querySelectorAll('#ing-table tbody tr:not(#no-results)');
        var found = 0;
        rows.forEach(function(row) {
            var nom = row.querySelector('td:nth-child(2)');
            if (!nom) return;
            if (nom.textContent.toLowerCase().includes(q)) { row.style.display = ''; found++; }
            else row.style.display = 'none';
        });
        var noRes = document.getElementById('no-results');
        if (noRes) noRes.style.display = found === 0 ? '' : 'none';
    });
}

// Auto-hide alerts
setTimeout(function() {
    document.querySelectorAll('.alert-ok, .alert-err').forEach(function(a) {
        a.style.transition = 'opacity .5s';
        a.style.opacity = '0';
        setTimeout(function() { a.remove(); }, 500);
    });
}, 4000);
</script>

</body>
</html>
