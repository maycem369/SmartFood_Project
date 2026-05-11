<?php
if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
$recetteBase = __DIR__ . '/../../recette';
require_once $recetteBase . '/config/database.php';
require_once $recetteBase . '/controllers/RecetteController.php';
require_once $recetteBase . '/controllers/IngredientController.php';
RecetteController::migrate();
$c = new RecetteController();
$ic = new IngredientController();
$recettes = $c->getAll();
$ingredients = $ic->getAll();
$activeCategory = $_GET['categorie'] ?? '';
if (!empty($activeCategory)) { $recettes = $c->getByCategory($activeCategory); }
if (isset($_GET['search']) && !empty($_GET['ingredients'])) { $recettes = $c->searchMultiple($_GET['ingredients']); }
if (isset($_GET['smart']) && !empty($_GET['ingredients'])) { $recettes = $c->recommend($_GET['ingredients']); }
$categories = RecetteController::CATEGORIES;
$catIcons = ['Italien'=>'pizza','Healthy'=>'salad','Fast Food'=>'burger','Dessert'=>'cake','Tunisien'=>'flag','Autre'=>'fork'];
$uploadsUrl = 'recette/uploads/recettes/';
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Recettes - SmartFood</title>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/settings.js"></script></head><body>
<nav class="front-navbar">
<div class="logo">Smart<span>Food</span></div>
<ul class="front-nav-links">
<li><a href="index.php?action=dashboard_user">Tableau de bord</a></li>
<li><a href="index.php?action=profil">Mon Profil</a></li>
<li><a href="index.php?action=recettes_front" class="active">Recettes</a></li>
<li><a href="index.php?action=settings">Parametres</a></li>
</ul>
<div class="front-user-menu">
<span>Bonjour, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
<a href="index.php?action=logout" class="btn btn-danger btn-sm">Deconnexion</a>
</div></nav>
<div class="front-main-content">
<div class="header"><h1>Recettes intelligentes</h1></div>
<div class="card" style="margin-bottom:20px;padding:20px;">
<div style="display:flex;flex-wrap:wrap;gap:10px;">
<a href="index.php?action=recettes_front" style="padding:8px 18px;border-radius:30px;border:2px solid #ccc;text-decoration:none;font-weight:600;<?= empty($activeCategory)?'background:#2D6A4F;color:white;':'' ?>">Toutes</a>
<?php foreach($categories as $cat): ?>
<a href="index.php?action=recettes_front&categorie=<?= urlencode($cat) ?>" style="padding:8px 18px;border-radius:30px;border:2px solid #ccc;text-decoration:none;font-weight:600;<?= $activeCategory===$cat?'background:#2D6A4F;color:white;':'' ?>"><?= $cat ?></a>
<?php endforeach; ?>
</div></div>
<div class="card" style="margin-bottom:20px;">
<h3>Choisir vos ingredients</h3>
<form method="GET" action="index.php">
<input type="hidden" name="action" value="recettes_front">
<?php if(!empty($activeCategory)): ?><input type="hidden" name="categorie" value="<?= htmlspecialchars($activeCategory) ?>"><?php endif; ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-bottom:16px;">
<?php foreach($ingredients as $i): $checked = !empty($_GET['ingredients']) && in_array($i['nom'], $_GET['ingredients']); ?>
<label style="display:flex;align-items:center;gap:8px;padding:10px;border:2px solid <?= $checked?'#2D6A4F':'#ddd' ?>;border-radius:12px;cursor:pointer;background:<?= $checked?'#e8f5ee':'white' ?>;">
<input type="checkbox" name="ingredients[]" value="<?= htmlspecialchars($i['nom']) ?>" <?= $checked?'checked':'' ?>>
<?= htmlspecialchars($i['nom']) ?>
</label>
<?php endforeach; ?>
</div>
<div style="display:flex;gap:10px;">
<button class="btn btn-secondary" name="search">Recherche exacte</button>
<button class="btn btn-primary" name="smart">Recherche intelligente</button>
<a href="index.php?action=recettes_front" class="btn btn-cancel">Reset</a>
</div></form></div>
<p style="color:#888;margin:10px 0;"><?= count($recettes) ?> recette(s)</p>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
<?php foreach($recettes as $r):
$ings = $r['ingredients'] ? explode(',', $r['ingredients']) : [];
$cat = $r['categorie'] ?? 'Autre';
?>
<div class="card" style="padding:0;overflow:hidden;">
<?php if(!empty($r['photo'])): ?>
<img src="<?= $uploadsUrl . htmlspecialchars($r['photo']) ?>" style="width:100%;height:180px;object-fit:cover;" alt="">
<?php else: ?>
<div style="width:100%;height:180px;background:linear-gradient(135deg,#e8f5ee,#d1fae5);display:flex;align-items:center;justify-content:center;font-size:3rem;">🍽️</div>
<?php endif; ?>
<div style="padding:20px;">
<div style="display:flex;justify-content:space-between;margin-bottom:8px;">
<span style="background:#e8f5ee;color:#2D6A4F;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;"><?= htmlspecialchars($cat) ?></span>
<span style="padding:4px 10px;border-radius:20px;font-size:.75rem;font-weight:700;background:<?= trim($r['status'])==='Validée'?'#d1fae5':'#ffedd5' ?>;color:<?= trim($r['status'])==='Validée'?'#065f46':'#92400e' ?>;"><?= htmlspecialchars($r['status']) ?></span>
</div>
<h3 style="margin:0 0 8px;"><?= htmlspecialchars($r['nom']) ?></h3>
<p style="color:#888;font-size:.88rem;margin-bottom:12px;"><?= htmlspecialchars($r['description']) ?></p>
<div><?php foreach($ings as $ing): ?><span style="display:inline-block;background:#e8f5ee;color:#2D6A4F;padding:2px 10px;border-radius:20px;font-size:.78rem;margin:2px;"><?= htmlspecialchars(trim($ing)) ?></span><?php endforeach; ?></div>
<?php if(isset($r['match'])): ?>
<div style="margin-top:12px;padding-top:12px;border-top:1px solid #eee;">
<span style="padding:6px 14px;border-radius:20px;font-size:.85rem;font-weight:700;background:<?= $r['match']>=80?'#d1fae5':($r['match']>=50?'#ffedd5':'#fee2e2') ?>;color:<?= $r['match']>=80?'#065f46':($r['match']>=50?'#92400e':'#991b1b') ?>;">Match: <?= $r['match'] ?>%</span>
</div>
<?php endif; ?>
</div></div>
<?php endforeach; ?>
</div></div>
</body></html>