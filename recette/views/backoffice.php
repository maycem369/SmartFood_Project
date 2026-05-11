<?php
require_once __DIR__ . "/../controllers/RecetteController.php";
require_once __DIR__ . "/../controllers/IngredientController.php";

// Auto migrate DB
RecetteController::migrate();

$c  = new RecetteController();
$ic = new IngredientController();

$c->handleRequest();
$ic->handleRequest();

$recettes    = $c->getAll();
$ingredients = $ic->getAll();
$categories  = RecetteController::CATEGORIES;

$catIcons = ['Italien'=>'🍕','Healthy'=>'🥗','Fast Food'=>'🍔','Dessert'=>'🍰','Tunisien'=>'🇹🇳','Autre'=>'🍴'];

/* ===== FILTER ===== */
$filterCat    = $_GET['cat']    ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterSearch = $_GET['q']      ?? '';

$filtered = array_filter($recettes, function($r) use ($filterCat, $filterStatus, $filterSearch) {
    if ($filterCat    && $r['categorie'] !== $filterCat)                          return false;
    if ($filterStatus && trim($r['status']) !== $filterStatus)                    return false;
    if ($filterSearch && stripos($r['nom'], $filterSearch) === false)             return false;
    return true;
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SmartFood – Gestion des Recettes</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { margin: 0; padding: 20px; background: #f0f4f3; font-family: 'Poppins', sans-serif; }
        .ingredient-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; margin-top: 8px; }
        .ingredient-chip { display: flex; align-items: center; gap: 7px; padding: 8px 12px; border: 2px solid var(--border-color); border-radius: 10px; cursor: pointer; font-size: .83rem; font-weight: 500; color: var(--text-dark); background: white; transition: all .2s; user-select: none; }
        .ingredient-chip:hover { border-color: var(--front-accent); background: var(--green-tint); color: var(--front-accent); }
        .ingredient-chip input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--front-accent); cursor: pointer; flex-shrink: 0; margin: 0; padding: 0; border: none; box-shadow: none; }
        .ingredient-chip.checked { border-color: var(--front-accent); background: var(--green-tint); color: var(--front-accent); font-weight: 600; }
        .ing-pill { display: inline-block; background: var(--green-tint); color: var(--front-accent); padding: 2px 9px; border-radius: 20px; font-size: .75rem; font-weight: 500; margin: 2px; }
        .status-validee   { background: #d1fae5; color: #065f46; }
        .status-nonvalide { background: #ffedd5; color: #92400e; }
        .cat-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: var(--green-tint); color: var(--front-accent); }
        .stat-card:nth-child(1) { border-top: 3px solid var(--front-accent); }
        .stat-card:nth-child(2) { border-top: 3px solid var(--front-orange); }
        .stat-card:nth-child(3) { border-top: 3px solid #0ea5e9; }
        .add-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px; }
        @media (max-width: 860px) { .add-grid { grid-template-columns: 1fr; } }
        .photo-preview { width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-top: 10px; border: 2px solid var(--border-color); display: none; }
        .photo-placeholder { width: 100%; height: 100px; border-radius: 12px; border: 2px dashed var(--border-color); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: all .2s; margin-top: 8px; background: #fafafa; }
        .photo-placeholder:hover { border-color: var(--front-accent); background: var(--green-tint); }
        .photo-placeholder span { font-size: .85rem; color: var(--text-muted); font-weight: 500; }
        .table-photo { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid var(--border-color); }
        .table-photo-placeholder { width: 50px; height: 50px; border-radius: 8px; background: var(--green-tint); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 20px; }
        .filter-row input, .filter-row select { max-width: 200px; padding: 10px 14px; font-size: .85rem; }
    </style>
</head>
<body>

        <div class="nutrition-header">
            <h1>🍽️ Gestion des Recettes</h1>
            <p>Ajoutez, modifiez ou supprimez les recettes de la plateforme.</p>
        </div>

        <!-- ── STAT CARDS ── -->
        <div class="stats-grid" style="margin-bottom:30px;">
            <div class="stat-card">
                <h3>Total Recettes</h3>
                <div class="stat-number"><?= count($recettes) ?></div>
                <div class="stat-label">dans la base</div>
            </div>
            <div class="stat-card">
                <h3>Total Ingrédients</h3>
                <div class="stat-number"><?= count($ingredients) ?></div>
                <div class="stat-label">disponibles</div>
            </div>
            <div class="stat-card">
                <h3>Validées</h3>
                <div class="stat-number">
                    <?= count(array_filter($recettes, fn($r) => trim($r['status']) === 'Validée')) ?>
                </div>
                <div class="stat-label">recettes actives</div>
            </div>
        </div>

        <!-- ── ADD ZONE ── -->
        <div class="add-grid">

            <!-- ADD RECETTE -->
            <div class="card">
                <h3>➕ Ajouter une Recette</h3>
                <form method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" placeholder="Nom de la recette">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2" placeholder="Description..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="categorie">
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat ?>"><?= $catIcons[$cat] ?? '' ?> <?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Photo</label>
                        <label class="photo-placeholder" for="photoAdd">
                            <span style="font-size:1.8rem;">📸</span>
                            <span>Cliquez pour ajouter une photo</span>
                            <span style="font-size:.75rem;color:#aaa;">JPG, PNG, WEBP</span>
                        </label>
                        <input type="file" name="photo" id="photoAdd" accept="image/*" style="display:none;"
                               onchange="previewPhoto(this, 'previewAdd')">
                        <img id="previewAdd" class="photo-preview">
                    </div>

                    <div class="form-group">
                        <label>Ingrédients</label>
                        <div class="ingredient-grid">
                            <?php foreach($ingredients as $i): ?>
                            <label class="ingredient-chip">
                                <input type="checkbox" name="ingredients[]" value="<?= $i['idingredient'] ?>">
                                <?= htmlspecialchars($i['nom']) ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button class="btn btn-secondary btn-full" name="add" style="margin-top:8px;">
                        ➕ Ajouter la recette
                    </button>
                </form>
            </div>

            <!-- ADD INGREDIENT -->
            <div class="card">
                <h3>🥦 Ajouter un Ingrédient</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Nom de l'ingrédient</label>
                        <input type="text" name="nomIngredient" placeholder="Ex: Tomate, Poulet...">
                    </div>
                    <button class="btn btn-primary btn-full" name="addIngredient" style="margin-top:8px;">
                        ➕ Ajouter l'ingrédient
                    </button>
                </form>

                <div style="margin-top:24px;">
                    <div class="table-header">
                        <b style="font-size:.85rem;color:#888;text-transform:uppercase;letter-spacing:.04em;">Ingrédients existants</b>
                        <span class="badge-total"><?= count($ingredients) ?></span>
                    </div>
                    <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px;">
                        <?php foreach($ingredients as $i): ?>
                        <span class="ing-pill" style="font-size:.8rem;padding:4px 12px;">
                            <?= htmlspecialchars($i['nom']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── TABLE RECETTES ── -->
        <div class="section-card">
            <div class="table-header">
                <h3>📋 Liste des Recettes</h3>
                <span class="badge-total"><?= count($filtered) ?> / <?= count($recettes) ?></span>
            </div>

            <!-- Filter row -->
            <form method="GET" action="index.php" class="filter-row">
                <input type="hidden" name="action" value="recettes_admin">
                <input type="text" name="q" placeholder="🔍 Rechercher..." value="<?= htmlspecialchars($filterSearch) ?>">
                <select name="cat">
                    <option value="">Toutes catégories</option>
                    <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $filterCat === $cat ? 'selected' : '' ?>>
                        <?= $catIcons[$cat] ?? '' ?> <?= $cat ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select name="status">
                    <option value="">Tous statuts</option>
                    <option value="Validée"     <?= $filterStatus === 'Validée'     ? 'selected' : '' ?>>✅ Validée</option>
                    <option value="Non validée" <?= $filterStatus === 'Non validée' ? 'selected' : '' ?>>⏳ Non validée</option>
                </select>
                <button class="btn btn-secondary" type="submit">Filtrer</button>
                <a href="index.php?action=recettes_admin" class="btn btn-cancel">Reset</a>
            </form>

            <table class="smart-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Ingrédients</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($filtered as $r):
                        $ings      = $r['ingredients'] ? explode(',', $r['ingredients']) : [];
                        $isValidee = trim($r['status']) === 'Validée';
                        $cat       = $r['categorie'] ?? 'Autre';
                    ?>
                    <tr>
                        <td>
                            <?php if(!empty($r['photo'])): ?>
                            <img src="../uploads/recettes/<?= htmlspecialchars($r['photo']) ?>"
                                 class="table-photo" alt="">
                            <?php else: ?>
                            <div class="table-photo-placeholder"><?= $catIcons[$cat] ?? '🍽️' ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="db-id">#<?= $r['idrecette'] ?></td>
                        <td><b><?= htmlspecialchars($r['nom']) ?></b></td>
                        <td><span class="cat-badge"><?= $catIcons[$cat] ?? '🍴' ?> <?= htmlspecialchars($cat) ?></span></td>
                        <td>
                            <?php foreach($ings as $ing): ?>
                            <span class="ing-pill"><?= htmlspecialchars(trim($ing)) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <span class="badge-status <?= $isValidee ? 'status-validee' : 'status-nonvalide' ?>">
                                <?= $isValidee ? '✅' : '⏳' ?> <?= htmlspecialchars($r['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <button class="btn-sm btn-edit" onclick="openPopup(
                                    <?= $r['idrecette'] ?>,
                                    `<?= htmlspecialchars($r['nom'], ENT_QUOTES) ?>`,
                                    `<?= htmlspecialchars($r['description'], ENT_QUOTES) ?>`,
                                    `<?= htmlspecialchars($r['ingredients'] ?? '', ENT_QUOTES) ?>`,
                                    `<?= htmlspecialchars($cat, ENT_QUOTES) ?>`,
                                    `<?= htmlspecialchars($r['photo'] ?? '', ENT_QUOTES) ?>`
                                )">✏️ Modifier</button>

                                <?php if(!$isValidee): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $r['idrecette'] ?>">
                                    <button class="btn-sm btn-view" name="validate">✅ Valider</button>
                                </form>
                                <?php endif; ?>

                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $r['idrecette'] ?>">
                                    <button class="btn-sm btn-delete" name="delete"
                                        onclick="return confirm('Supprimer « <?= htmlspecialchars($r['nom'], ENT_QUOTES) ?> » ?')">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<!-- ── MODAL MODIFIER ── -->
<div id="popup" class="modal" style="display:none; align-items:flex-start; padding-top: 30px;">
    <div class="modal-content" style="max-width:620px; max-height:88vh; overflow-y:auto; padding: 24px 28px;">
        <span class="close-modal" onclick="closePopup()">×</span>

        <!-- Compact header -->
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid var(--border-color);">
            <span style="font-size:1.3rem;">✏️</span>
            <div>
                <h2 style="font-size:1.1rem; margin:0; color:var(--front-accent); font-family:'Poppins',sans-serif;">Modifier la Recette</h2>
                <p style="font-size:.78rem; color:var(--text-muted); margin:0;">Smart<span style="color:var(--front-orange);">Food</span> Back Office</p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="pid">

            <!-- Row 1: Nom + Catégorie -->
            <div class="two-columns" style="gap:12px; margin-bottom:12px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label style="font-size:.8rem;">Nom</label>
                    <input type="text" name="nom" id="pnom" style="padding:9px 12px; font-size:.88rem;">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="font-size:.8rem;">Catégorie</label>
                    <select name="categorie" id="pcat" style="padding:9px 12px; font-size:.88rem;">
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat ?>"><?= $catIcons[$cat] ?? '' ?> <?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Row 2: Description -->
            <div class="form-group" style="margin-bottom:12px;">
                <label style="font-size:.8rem;">Description</label>
                <textarea name="description" id="pdesc" rows="2" style="padding:9px 12px; font-size:.88rem;"></textarea>
            </div>

            <!-- Row 3: Photo (compact) -->
            <div class="form-group" style="margin-bottom:12px;">
                <label style="font-size:.8rem;">Photo</label>
                <div style="display:flex; gap:12px; align-items:center; margin-top:6px;">
                    <div id="currentPhotoWrap" style="display:none; flex-shrink:0;">
                        <img id="currentPhoto" src=""
                             style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:2px solid var(--border-color);">
                    </div>
                    <label for="photoEdit"
                           style="flex:1; display:flex; align-items:center; gap:8px; padding:10px 14px; border:2px dashed var(--border-color); border-radius:10px; cursor:pointer; background:#fafafa; font-size:.83rem; color:var(--text-muted); transition:all .2s;"
                           onmouseover="this.style.borderColor='var(--front-accent)';this.style.background='var(--green-tint)'"
                           onmouseout="this.style.borderColor='var(--border-color)';this.style.background='#fafafa'">
                        📸 <span>Changer la photo</span>
                    </label>
                    <input type="file" name="photo" id="photoEdit" accept="image/*" style="display:none;"
                           onchange="previewPhoto(this, 'previewEdit')">
                </div>
                <img id="previewEdit" class="photo-preview" style="margin-top:8px;">
            </div>

            <!-- Row 4: Ingrédients (compact grid) -->
            <div class="form-group" style="margin-bottom:14px;">
                <label style="font-size:.8rem;">Ingrédients</label>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(110px,1fr)); gap:6px; margin-top:6px;">
                    <?php foreach($ingredients as $i): ?>
                    <label class="ingredient-chip" style="padding:6px 10px; font-size:.8rem; border-radius:8px;">
                        <input type="checkbox" name="ingredients[]"
                               value="<?= $i['idingredient'] ?>" class="ping"
                               style="width:14px;height:14px;">
                        <?= htmlspecialchars($i['nom']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex" style="justify-content:flex-end; gap:10px;">
                <button type="button" class="btn btn-cancel" onclick="closePopup()" style="padding:10px 20px;">Fermer</button>
                <button class="btn btn-primary" name="update" style="padding:10px 20px;">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
/* ── Chip highlight ── */
document.querySelectorAll('.ingredient-chip input').forEach(cb => {
    cb.addEventListener('change', function(){
        this.closest('.ingredient-chip').classList.toggle('checked', this.checked);
    });
});

/* ── Photo preview ── */
function previewPhoto(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ── Open modal ── */
function openPopup(id, nom, desc, ing, cat, photo) {
    document.getElementById("popup").style.display = "flex";
    document.getElementById("pid").value   = id;
    document.getElementById("pnom").value  = nom;
    document.getElementById("pdesc").value = desc;

    // Set category
    const catSelect = document.getElementById("pcat");
    for(let opt of catSelect.options) opt.selected = opt.value === cat;

    // Show current photo
    const photoWrap = document.getElementById("currentPhotoWrap");
    const photoImg  = document.getElementById("currentPhoto");
    if (photo) {
        photoImg.src = '../uploads/recettes/' + photo;
        photoWrap.style.display = 'block';
    } else {
        photoWrap.style.display = 'none';
    }

    // Reset chips
    document.querySelectorAll(".ping").forEach(b => {
        b.checked = false;
        b.closest('.ingredient-chip').classList.remove('checked');
    });

    // Re-check matching ingredients
    if (ing) {
        const list = ing.split(',').map(s => s.trim());
        document.querySelectorAll(".ping").forEach(b => {
            const label = b.parentNode.textContent.trim();
            if (list.includes(label)) {
                b.checked = true;
                b.closest('.ingredient-chip').classList.add('checked');
            }
        });
    }
}

/* ── Close modal ── */
function closePopup() { document.getElementById("popup").style.display = "none"; }
document.getElementById("popup").addEventListener("click", function(e) { if(e.target===this) closePopup(); });
</script>

</body>
</html>