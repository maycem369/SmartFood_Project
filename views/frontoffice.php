<?php
require_once __DIR__ . "/../controllers/RecetteController.php";
require_once __DIR__ . "/../controllers/IngredientController.php";

$c = new RecetteController();
$ic = new IngredientController();

$recettes = $c->getAll();
$ingredients = $ic->getAll();

/* ================= SEARCH ================= */
if(isset($_GET['search']) && !empty($_GET['ingredients'])) {

    $selected = $_GET['ingredients'];
    $recettes = [];

    foreach($selected as $ingredient) {
        $results = $c->search($ingredient);

        foreach($results as $r) {
            $recettes[$r['idrecette']] = $r;
        }
    }
}

/* ================= SHOW ALL ================= */
if(isset($_GET['all'])) {
    $recettes = $c->getAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>SmartFood - Front Office</title>
</head>

<body bgcolor="#F4F7F6">

<table width="100%" cellspacing="0">
<tr>

<td width="220" bgcolor="#2D6A4F" valign="top">

<h2 style="color:white; padding-left:15px;">
Smart<span style="color:#FF8C00;">Food</span>
</h2>

<p style="color:white; padding-left:15px;">Tableau de bord</p>
<p style="color:white; padding-left:15px;"><b>Gestion Recettes</b></p>
<p style="color:white; padding-left:15px;">Gestion Calories</p>
<p style="color:white; padding-left:15px;">Paramètres</p>

</td>

<td valign="top" style="padding:20px;">

<h2 style="color:#2D6A4F;">Front Office - Recettes</h2>

<form method="GET">

<h3>Rechercher par ingrédients</h3>

<?php foreach($ingredients as $i){ ?>

<label>
<input type="checkbox" 
name="ingredients[]" 
value="<?= $i['nom'] ?>">
<?= $i['nom'] ?>
</label><br>

<?php } ?>

<br>

<button type="submit" name="search"
style="padding:8px;background:#2D6A4F;color:white;">
Rechercher
</button>

</form>

<br>

<form method="GET">

<button type="submit" name="all"
style="padding:8px;background:#FF8C00;color:white;">
Consulter toutes les recettes
</button>

</form>

<br><br>

<table border="1" width="100%" cellpadding="8">

<tr style="background:#2D6A4F;color:white;">
<th>ID</th>
<th>Nom</th>
<th>Description</th>
<th>Ingredients</th>
<th>Status</th>
</tr>

<?php foreach($recettes as $r){ ?>
<tr>
<td><?= $r['idrecette'] ?></td>
<td><?= $r['nom'] ?></td>
<td><?= $r['description'] ?></td>
<td><?= $r['ingredients'] ?></td>
<td><?= $r['status'] ?></td>
</tr>
<?php } ?>

</table>

</td>
</tr>
</table>

</body>
</html>