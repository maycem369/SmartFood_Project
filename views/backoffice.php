<?php
require_once __DIR__ . "/../controllers/RecetteController.php";
require_once __DIR__ . "/../controllers/IngredientController.php";

$c = new RecetteController();
$ic = new IngredientController();

$c->handleRequest();

$recettes = $c->getAll();
$ingredients = $ic->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>SmartFood - Back Office</title>
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

<h2 style="color:#2D6A4F;">Back Office - Gestion Recettes</h2>

<h3>Ajouter Recette</h3>

<form method="POST">
Nom : <input type="text" name="nom"><br><br>

Description : <br>
<textarea name="description"></textarea><br><br>

Ingrédients : <br>

<?php foreach($ingredients as $i){ ?>
<label>
<input type="checkbox"
name="ingredients[]"
value="<?= $i['idingredient'] ?>">
<?= $i['nom'] ?>
</label><br>
<?php } ?>

<br>

<button name="add"
style="background:#2D6A4F;color:white;padding:8px;">
Ajouter
</button>

</form>

<hr>

<h3>Modifier Recette</h3>

<form method="POST">
ID : <input type="number" name="id"><br><br>
Nouveau Nom : <input type="text" name="nom"><br><br>

<button name="update"
style="background:#FF8C00;color:white;padding:8px;">
Modifier
</button>
</form>

<hr>

<h3>Supprimer / Valider</h3>

<form method="POST">
ID : <input type="number" name="id"><br><br>

<button name="delete"
style="background:red;color:white;padding:8px;">
Supprimer
</button>

<button name="validate"
style="background:#2D6A4F;color:white;padding:8px;">
Valider
</button>

</form>

<hr>

<h3>Liste des Recettes</h3>

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