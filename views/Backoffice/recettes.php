<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') { header("Location: index.php?action=login"); exit(); }
$recetteBase = __DIR__ . '/../../recette';
require_once $recetteBase . '/config/database.php';
require_once $recetteBase . '/controllers/RecetteController.php';
require_once $recetteBase . '/controllers/IngredientController.php';
RecetteController::migrate();
$c = new RecetteController();
$ic = new IngredientController();
$c->handleRequest();
$ic->handleRequest();
$recettes = $c->getAll();
$ingredients = $ic->getAll();
$categories = RecetteController::CATEGORIES;
$catIcons = ['Italien'=>'pizza','Healthy'=>'salad','Fast Food'=>'burger','Dessert'=>'cake','Tunisien'=>'flag','Autre'=>'fork'];
$filterCat = $_GET['cat'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterSearch = $_GET['q'] ?? '';
$filtered = array_filter($recettes, function($r) use ($filterCat, $filterStatus, $filterSearch) {
    if ($filterCat && $r['categorie'] !== $filterCat) return false;
    if ($filterStatus && trim($r['status']) !== $filterStatus) return false;
    if ($filterSearch && stripos($r['nom'], $filterSearch) === false) return false;
    return true;
});
$uploadsUrl = 'recette/uploads/recettes/';
?>
<!DOCTYPE html><html lang="fr"><head>
<script src="assets/js/settings.js"></script>
<meta charset="UTF-8"><title>Recettes Admin - SmartFood</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
body{background:#f0f4f3;}
.main-content{margin-left:var(--sidebar-w);padding:0 30px 30px;}
.sidebar{width:var(--sidebar-w);}
.ing-pill{display:inline-block;background:#e8f5ee;color:#2D6A4F;padding:2px 9px;border-radius:20px;font-size:.75rem;margin:2px;}
.table-photo{width:50px;height:50px;object-fit:cover;border-radius:8px;border:2px solid #ddd;}
.smart-table{width:100%;border-collapse:collapse;}
.smart-table th{background:#f8faf9;padding:12px 14px;text-align:left;font-size:.78rem;text-transform:uppercase;color:#888;border-bottom:2px solid #eee;}
.smart-table td{padding:12px 14px;border-bottom:1px solid #f0f0f0;vertical-align:middle;font-size:.88rem;}
.btn-sm{padding:5px 12px;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;border:none;}
.btn-edit{background:#e8f4fd;color:#2980b9;} .btn-delete{background:#fdecea;color:#c0392b;} .btn-view{background:#e9f5ef;color:#27ae60;}
.ingredient-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:8px;margin-top:8px;}
.ingredient-chip{display:flex;align-items:center;gap:7px;padding:8px 12px;border:2px solid #ddd;border-radius:10px;cursor:pointer;font-size:.83rem;background:white;}
.ingredient-chip.checked{border-color:#2D6A4F;background:#e8f5ee;color:#2D6A4F;}
.photo-preview{width:100%;height:160px;object-fit:cover;border-radius:12px;margin-top:10px;border:2px solid #ddd;display:none;}
.add-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:30px;}
</style>
</head><body>
<div class="sidebar admin-sidebar">
<div class="logo"><h1 style="color:white;">Smart<span>Food</span></h1></div>
<ul class="nav-menu">
<li><a href="index.php?action=admin_dashboard"><i class="fas fa-chart-line"></i> <span>Vue d'ensemble</span></a></li>
<li><a href="index.php?action=users_list"><i class="fas fa-user-shield"></i> <span>Utilisateurs</span></a></li>
<li><a href="index.php?action=recettes_admin" class="active"><i class="fas fa-scroll"></i> <span>Recettes</span></a></li>
<li><a href="index.php?action=admin_configuration"><i class="fas fa-cog"></i> <span>Configuration</span></a></li>
</ul>
<div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:20px;">
<a href="index.php?action=logout" style="color:var(--text-sidebar);display:flex;align-items:center;gap:8px;padding:12px 16px;"><i class="fas fa-sign-out-alt"></i> Deconnexion</a>
</div></div>
<div class="main-content">
<div class="admin-top-nav">
<div class="search-box"><i class="fas fa-search"></i><input type="text" placeholder="Rechercher..."></div>
<div class="admin-profile-info">
<div style="text-align:right;"><div style="font-weight:600;font-size:.9rem;"><?= htmlspecialchars($_SESSION['user_prenom'].' '.$_SESSION['user_nom']) ?></div><div style="font-size:.75rem;color:#888;">Administrateur</div></div>
<div class="admin-avatar"><?= strtoupper(substr($_SESSION['user_prenom'],0,1)) ?></div>
</div></div>
<div style="margin-bottom:24px;"><h2 style="margin:0;color:#2D6A4F;">Gestion des Recettes</h2><p style="color:#888;margin:4px 0 0;">Ajoutez, modifiez ou supprimez les recettes.</p></div>
<div class="stats-cards" style="margin-bottom:28px;">
<div class="stat-card" style="border-left:4px solid #2D6A4F;"><div class="stat-number"><?= count($recettes) ?></div><div class="stat-label">Total Recettes</div></div>
<div class="stat-card" style="border-left:4px solid #FF8C00;"><div class="stat-number"><?= count($ingredients) ?></div><div class="stat-label">Total Ingredients</div></div>
<div class="stat-card" style="border-left:4px solid #3498db;"><div class="stat-number"><?= count(array_filter($recettes, fn($r) => trim($r['status']) === 'Validée')) ?></div><div class="stat-label">Validees</div></div>
</div>
<div class="add-grid">
<div class="innovative-card">
<h3>Ajouter une Recette</h3>
<form method="POST" action="index.php?action=recettes_admin" enctype="multipart/form-data">
<div class="form-group"><label>Nom</label><input type="text" name="nom" placeholder="Nom de la recette"></div>
<div class="form-group"><label>Description</label><textarea name="description" rows="2" placeholder="Description..."></textarea></div>
<div class="form-group"><label>Categorie</label><select name="categorie"><?php foreach($categories as $cat): ?><option value="<?= $cat ?>"><?= $cat ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Photo</label>
<label for="photoAdd" style="display:flex;flex-direction:column;align-items:center;padding:20px;border:2px dashed #ddd;border-radius:12px;cursor:pointer;margin-top:8px;">
<span style="font-size:1.8rem;">📸</span><span style="font-size:.85rem;color:#888;">Cliquez pour ajouter une photo</span></label>
<input type="file" name="photo" id="photoAdd" accept="image/*" style="display:none;" onchange="previewPhoto(this,'previewAdd')">
<img id="previewAdd" class="photo-preview"></div>
<div class="form-group"><label>Ingredients</label>
<div class="ingredient-grid"><?php foreach($ingredients as $i): ?>
<label class="ingredient-chip"><input type="checkbox" name="ingredients[]" value="<?= $i['idingredient'] ?>"><?= htmlspecialchars($i['nom']) ?></label>
<?php endforeach; ?></div></div>
<button class="btn btn-secondary btn-full" name="add" style="margin-top:8px;">Ajouter la recette</button>
</form></div>
<div class="innovative-card">
<h3>Ajouter un Ingredient</h3>
<form method="POST" action="index.php?action=recettes_admin">
<div class="form-group"><label>Nom</label><input type="text" name="nomIngredient" placeholder="Ex: Tomate, Poulet..."></div>
<button class="btn btn-primary btn-full" name="addIngredient" style="margin-top:8px;">Ajouter l'ingredient</button>
</form>
<div style="margin-top:24px;"><b style="font-size:.85rem;color:#888;">Ingredients existants (<?= count($ingredients) ?>)</b>
<div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px;">
<?php foreach($ingredients as $i): ?><span class="ing-pill"><?= htmlspecialchars($i['nom']) ?></span><?php endforeach; ?>
</div></div></div></div>
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
<h3 style="margin:0;">Liste des Recettes</h3>
<span style="background:#e8f5ee;color:#2D6A4F;padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:700;"><?= count($filtered) ?> / <?= count($recettes) ?></span>
</div>
<form method="GET" action="index.php" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
<input type="hidden" name="action" value="recettes_admin">
<input type="text" name="q" placeholder="Rechercher..." value="<?= htmlspecialchars($filterSearch) ?>" style="padding:10px 14px;border:1px solid #ddd;border-radius:8px;">
<select name="cat" style="padding:10px 14px;border:1px solid #ddd;border-radius:8px;"><option value="">Toutes categories</option><?php foreach($categories as $cat): ?><option value="<?= $cat ?>" <?= $filterCat===$cat?'selected':'' ?>><?= $cat ?></option><?php endforeach; ?></select>
<select name="status" style="padding:10px 14px;border:1px solid #ddd;border-radius:8px;"><option value="">Tous statuts</option><option value="Validée" <?= $filterStatus==='Validée'?'selected':'' ?>>Validee</option><option value="Non validée" <?= $filterStatus==='Non validée'?'selected':'' ?>>Non validee</option></select>
<button class="btn btn-secondary" type="submit">Filtrer</button>
<a href="index.php?action=recettes_admin" class="btn btn-cancel">Reset</a>
</form>
<table class="smart-table"><thead><tr><th>Photo</th><th>ID</th><th>Nom</th><th>Categorie</th><th>Ingredients</th><th>Statut</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($filtered as $r):
$ings = $r['ingredients'] ? explode(',', $r['ingredients']) : [];
$isValidee = trim($r['status']) === 'Validée';
$cat = $r['categorie'] ?? 'Autre';
?>
<tr>
<td><?php if(!empty($r['photo'])): ?><img src="<?= $uploadsUrl.htmlspecialchars($r['photo']) ?>" class="table-photo"><?php else: ?><div style="width:50px;height:50px;border-radius:8px;background:#e8f5ee;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">🍽️</div><?php endif; ?></td>
<td style="font-family:monospace;color:#aaa;">#<?= $r['idrecette'] ?></td>
<td><b><?= htmlspecialchars($r['nom']) ?></b></td>
<td><span style="background:#e8f5ee;color:#2D6A4F;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;"><?= htmlspecialchars($cat) ?></span></td>
<td><?php foreach($ings as $ing): ?><span class="ing-pill"><?= htmlspecialchars(trim($ing)) ?></span><?php endforeach; ?></td>
<td><span style="padding:4px 10px;border-radius:20px;font-size:.75rem;font-weight:700;background:<?= $isValidee?'#d1fae5':'#ffedd5' ?>;color:<?= $isValidee?'#065f46':'#92400e' ?>;"><?= htmlspecialchars($r['status']) ?></span></td>
<td style="display:flex;gap:6px;flex-wrap:wrap;">
<button class="btn-sm btn-edit" onclick="openPopup(<?= $r['idrecette'] ?>,`<?= htmlspecialchars($r['nom'],ENT_QUOTES) ?>`,`<?= htmlspecialchars($r['description'],ENT_QUOTES) ?>`,`<?= htmlspecialchars($r['ingredients']??'',ENT_QUOTES) ?>`,`<?= htmlspecialchars($cat,ENT_QUOTES) ?>`,`<?= htmlspecialchars($r['photo']??'',ENT_QUOTES) ?>`)">Modifier</button>
<?php if(!$isValidee): ?><form method="POST" action="index.php?action=recettes_admin" style="display:inline;"><input type="hidden" name="id" value="<?= $r['idrecette'] ?>"><button class="btn-sm btn-view" name="validate">Valider</button></form><?php endif; ?>
<form method="POST" action="index.php?action=recettes_admin" style="display:inline;"><input type="hidden" name="id" value="<?= $r['idrecette'] ?>"><button class="btn-sm btn-delete" name="delete" onclick="return confirm('Supprimer?')">Supprimer</button></form>
</td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
<div id="popup" class="modal" style="display:none;align-items:flex-start;padding-top:30px;">
<div class="modal-content" style="max-width:620px;max-height:88vh;overflow-y:auto;padding:24px 28px;">
<span class="close-modal" onclick="closePopup()">x</span>
<h2 style="font-size:1.1rem;margin:0 0 16px;color:#2D6A4F;">Modifier la Recette</h2>
<form method="POST" action="index.php?action=recettes_admin" enctype="multipart/form-data">
<input type="hidden" name="id" id="pid">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
<div class="form-group" style="margin-bottom:0;"><label style="font-size:.8rem;">Nom</label><input type="text" name="nom" id="pnom"></div>
<div class="form-group" style="margin-bottom:0;"><label style="font-size:.8rem;">Categorie</label><select name="categorie" id="pcat"><?php foreach($categories as $cat): ?><option value="<?= $cat ?>"><?= $cat ?></option><?php endforeach; ?></select></div>
</div>
<div class="form-group" style="margin-bottom:12px;"><label style="font-size:.8rem;">Description</label><textarea name="description" id="pdesc" rows="2"></textarea></div>
<div class="form-group" style="margin-bottom:12px;"><label style="font-size:.8rem;">Photo</label>
<div style="display:flex;gap:12px;align-items:center;margin-top:6px;">
<div id="currentPhotoWrap" style="display:none;"><img id="currentPhoto" src="" style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:2px solid #ddd;"></div>
<label for="photoEdit" style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;border:2px dashed #ddd;border-radius:10px;cursor:pointer;font-size:.83rem;color:#888;">📸 Changer la photo</label>
<input type="file" name="photo" id="photoEdit" accept="image/*" style="display:none;" onchange="previewPhoto(this,'previewEdit')">
</div><img id="previewEdit" class="photo-preview" style="margin-top:8px;"></div>
<div class="form-group" style="margin-bottom:14px;"><label style="font-size:.8rem;">Ingredients</label>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:6px;margin-top:6px;">
<?php foreach($ingredients as $i): ?>
<label class="ingredient-chip" style="padding:6px 10px;font-size:.8rem;border-radius:8px;"><input type="checkbox" name="ingredients[]" value="<?= $i['idingredient'] ?>" class="ping"><?= htmlspecialchars($i['nom']) ?></label>
<?php endforeach; ?></div></div>
<div style="display:flex;justify-content:flex-end;gap:10px;">
<button type="button" class="btn btn-cancel" onclick="closePopup()">Fermer</button>
<button class="btn btn-primary" name="update">Enregistrer</button>
</div></form></div></div>
<script>
document.querySelectorAll('.ingredient-chip input').forEach(cb=>{cb.addEventListener('change',function(){this.closest('.ingredient-chip').classList.toggle('checked',this.checked);});});
function previewPhoto(input,previewId){const p=document.getElementById(previewId);if(input.files&&input.files[0]){const r=new FileReader();r.onload=e=>{p.src=e.target.result;p.style.display='block';};r.readAsDataURL(input.files[0]);}}
function openPopup(id,nom,desc,ing,cat,photo){
document.getElementById("popup").style.display="flex";
document.getElementById("pid").value=id;document.getElementById("pnom").value=nom;document.getElementById("pdesc").value=desc;
const cs=document.getElementById("pcat");for(let o of cs.options)o.selected=o.value===cat;
const pw=document.getElementById("currentPhotoWrap");const pi=document.getElementById("currentPhoto");
if(photo){pi.src='<?= $uploadsUrl ?>'+photo;pw.style.display='block';}else{pw.style.display='none';}
document.querySelectorAll(".ping").forEach(b=>{b.checked=false;b.closest('.ingredient-chip').classList.remove('checked');});
if(ing){const list=ing.split(',').map(s=>s.trim());document.querySelectorAll(".ping").forEach(b=>{if(list.includes(b.parentNode.textContent.trim())){b.checked=true;b.closest('.ingredient-chip').classList.add('checked');}});}
}
function closePopup(){document.getElementById("popup").style.display="none";}
document.getElementById("popup").addEventListener("click",function(e){if(e.target===this)closePopup();});
</script>
</body></html>