<?php
// ── Sécurité ─────────────────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// ── Chargement des contrôleurs recette ───────────────────────────────────────
if (!class_exists('RecetteDatabase')) {
    require_once __DIR__ . '/../../recette/config/database.php';
}
if (!class_exists('RecetteController')) {
    require_once __DIR__ . '/../../recette/controllers/RecetteController.php';
}
if (!class_exists('IngredientController')) {
    require_once __DIR__ . '/../../recette/controllers/IngredientController.php';
}

RecetteController::migrate();

$c  = new RecetteController();
$ic = new IngredientController();

$recettes    = $c->getAll();
$ingredients = $ic->getAll();

// ── Filtres ──────────────────────────────────────────────────────────────────
$activeCategory = $_GET['categorie'] ?? '';
if (!empty($activeCategory)) {
    $recettes = $c->getByCategory($activeCategory);
}
if (isset($_GET['search']) && !empty($_GET['ingredients'])) {
    $recettes = $c->searchMultiple($_GET['ingredients']);
}
if (isset($_GET['smart']) && !empty($_GET['ingredients'])) {
    $recettes = $c->recommend($_GET['ingredients']);
}

$categories = RecetteController::CATEGORIES;
$catIcons   = ['Italien'=>'🍕','Healthy'=>'🥗','Fast Food'=>'🍔','Dessert'=>'🍰','Tunisien'=>'🇹🇳','Autre'=>'🍴'];
$uploadsUrl = 'recette/uploads/recettes/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Recettes – SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/chatbot.js" defer></script>
    <style>
        .ingredient-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:10px; margin-bottom:24px; }
        .ingredient-chip { display:flex; align-items:center; gap:8px; padding:10px 14px; border:2px solid var(--border-color); border-radius:12px; cursor:pointer; font-size:.88rem; font-weight:500; color:var(--text-dark); background:white; transition:all .2s; user-select:none; }
        .ingredient-chip:hover { border-color:var(--front-accent); background:var(--green-tint); color:var(--front-accent); }
        .ingredient-chip input[type="checkbox"] { width:16px; height:16px; accent-color:var(--front-accent); cursor:pointer; flex-shrink:0; margin:0; padding:0; border:none; box-shadow:none; }
        .ingredient-chip.checked { border-color:var(--front-accent); background:var(--green-tint); color:var(--front-accent); font-weight:600; }

        .category-filter { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:24px; }
        .cat-pill { display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:30px; border:2px solid var(--border-color); background:white; color:var(--text-dark); font-size:.85rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; }
        .cat-pill:hover { border-color:var(--front-accent); background:var(--green-tint); color:var(--front-accent); }
        .cat-pill.active { background:var(--front-accent); border-color:var(--front-accent); color:white; }
        .cat-pill-all.active { background:var(--front-orange); border-color:var(--front-orange); color:white; }

        .recipe-photo { width:100%; height:180px; object-fit:cover; border-radius:16px; margin-bottom:16px; }
        .recipe-photo-placeholder { width:100%; height:180px; border-radius:16px; background:linear-gradient(135deg,var(--green-tint),#e8f5ee); display:flex; align-items:center; justify-content:center; font-size:3rem; margin-bottom:16px; }
        .cat-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:700; background:var(--green-tint); color:var(--front-accent); }
        .status-validee   { background:#d1fae5; color:#065f46; }
        .status-nonvalide { background:#ffedd5; color:#92400e; }
        .match-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:20px; font-size:.85rem; font-weight:700; }
        .match-green  { background:#d1fae5; color:#065f46; }
        .match-orange { background:#ffedd5; color:#92400e; }
        .match-red    { background:#fee2e2; color:#991b1b; }
        .missing-tag  { display:inline-flex; align-items:center; background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:500; }
        .missing-wrap { display:flex; flex-wrap:wrap; gap:6px; margin-top:6px; }
        .card-footer  { margin-top:16px; padding-top:14px; border-top:1px solid var(--border-color); display:flex; flex-direction:column; gap:10px; }
        .ing-pill  { display:inline-block; background:var(--green-tint); color:var(--front-accent); padding:2px 10px; border-radius:20px; font-size:.78rem; font-weight:500; margin:2px; }
        .ing-pills { margin-top:6px; }
        .bento-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:20px; margin-top:16px; }
    </style>
</head>
<body>

<!-- ── NAVBAR USER ── -->
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="index.php?action=recettes_front" class="active" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="index.php?action=nutrition_front" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<!-- ── CONTENU ── -->
<div class="front-main-content">

    <div class="header">
        <h1 data-i18n="rec_front_title">📋 Recettes intelligentes</h1>
    </div>

    <!-- Filtres catégories -->
    <div class="card" style="padding:20px 24px;margin-bottom:20px;">
        <div class="category-filter">
            <a href="index.php?action=recettes_front"
               class="cat-pill cat-pill-all <?= empty($activeCategory) ? 'active' : '' ?>">
                🍽️ Toutes
            </a>
            <?php foreach($categories as $cat): ?>
            <a href="index.php?action=recettes_front&categorie=<?= urlencode($cat) ?>"
               class="cat-pill <?= $activeCategory === $cat ? 'active' : '' ?>">
                <?= $catIcons[$cat] ?? '🍴' ?> <?= $cat ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire ingrédients -->
    <div class="card" style="margin-bottom:20px;">
        <h3 data-i18n="rec_choose_ing">🥦 Choisir vos ingrédients</h3>
        <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:16px;" data-i18n="rec_choose_desc">
            Cochez les ingrédients que vous avez à disposition.
        </p>
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="recettes_front">
            <?php if(!empty($activeCategory)): ?>
            <input type="hidden" name="categorie" value="<?= htmlspecialchars($activeCategory) ?>">
            <?php endif; ?>
            <div class="ingredient-grid">
                <?php foreach($ingredients as $i):
                    $nom     = htmlspecialchars($i['nom']);
                    $checked = !empty($_GET['ingredients']) && in_array($i['nom'], $_GET['ingredients']);
                ?>
                <label class="ingredient-chip <?= $checked ? 'checked' : '' ?>">
                    <input type="checkbox" name="ingredients[]" value="<?= $nom ?>" <?= $checked ? 'checked' : '' ?>>
                    <?= $nom ?>
                </label>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button class="btn btn-secondary" name="search" value="1" data-i18n="rec_search_exact">🔍 Recherche exacte</button>
                <button class="btn btn-primary"   name="smart"  value="1" data-i18n="rec_search_smart">✨ Recherche intelligente</button>
                <a href="index.php?action=recettes_front" class="btn btn-cancel">↺ Reset</a>
            </div>
        </form>
    </div>

    <!-- Résultats -->
    <p style="color:var(--text-muted);margin:10px 0;">
        <?php if(!empty($activeCategory)): ?>
            <?= $catIcons[$activeCategory] ?? '' ?> <b><?= htmlspecialchars($activeCategory) ?></b> —
        <?php endif; ?>
        <?= count($recettes) ?> recette(s)
    </p>

    <!-- Grille de cartes -->
    <div class="bento-grid">
        <?php foreach($recettes as $r):
            $matchClass = ''; $matchIcon = '';
            if(isset($r['match'])){
                if($r['match'] >= 80)     { $matchClass = 'match-green';  $matchIcon = '🟢'; }
                elseif($r['match'] >= 50) { $matchClass = 'match-orange'; $matchIcon = '🟠'; }
                else                      { $matchClass = 'match-red';    $matchIcon = '🔴'; }
            }
            $ings = $r['ingredients'] ? explode(',', $r['ingredients']) : [];
            $cat  = $r['categorie'] ?? 'Autre';
        ?>
        <div class="card" style="padding:0;overflow:hidden;">
            <?php if(!empty($r['photo'])): ?>
            <img src="<?= $uploadsUrl . htmlspecialchars($r['photo']) ?>"
                 alt="<?= htmlspecialchars($r['nom']) ?>" class="recipe-photo">
            <?php else: ?>
            <div class="recipe-photo-placeholder"><?= $catIcons[$cat] ?? '🍽️' ?></div>
            <?php endif; ?>
            <div style="padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span class="cat-badge"><?= $catIcons[$cat] ?? '🍴' ?> <?= htmlspecialchars($cat) ?></span>
                    <span class="badge-status <?= trim($r['status']) === 'Validée' ? 'status-validee' : 'status-nonvalide' ?>">
                        <?= trim($r['status']) === 'Validée' ? '✅' : '⏳' ?> <?= htmlspecialchars($r['status']) ?>
                    </span>
                </div>
                <h3 style="margin:0 0 8px;"><?= htmlspecialchars($r['nom']) ?></h3>
                <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:12px;"><?= htmlspecialchars($r['description']) ?></p>
                <div>
                    <b style="font-size:.78rem;color:#999;text-transform:uppercase;letter-spacing:.05em;">🧂 Ingrédients</b>
                    <div class="ing-pills">
                        <?php foreach($ings as $ing): ?>
                        <span class="ing-pill"><?= htmlspecialchars(trim($ing)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if(isset($r['match'])): ?>
                <div class="card-footer">
                    <span class="match-badge <?= $matchClass ?>"><?= $matchIcon ?> Match : <?= $r['match'] ?>%</span>
                    <?php if(!empty($r['missing'])): ?>
                    <div>
                        <span style="font-size:.78rem;color:#888;font-weight:600;">❌ Manquants :</span>
                        <div class="missing-wrap">
                            <?php foreach(explode(',', $r['missing']) as $m): ?>
                            <span class="missing-tag">− <?= htmlspecialchars(trim($m)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <span style="font-size:.82rem;color:#065f46;font-weight:600;">✅ Vous avez tout !</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
document.querySelectorAll('.ingredient-chip input').forEach(cb => {
    cb.addEventListener('change', function(){
        this.closest('.ingredient-chip').classList.toggle('checked', this.checked);
    });
});
</script>
</body>
</html>
