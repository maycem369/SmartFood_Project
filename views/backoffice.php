<?php
require_once __DIR__ . "/../controllers/RecetteController.php";

$c = new RecetteController();

/* ======================
   ACTIONS (CRUD)
====================== */

if(isset($_POST['add'])){
    $c->add($_POST['nom'], $_POST['description'], $_POST['ingredients']);
}

if(isset($_POST['delete'])){
    $c->delete($_POST['id']);
}

if(isset($_POST['update'])){
    $c->update($_POST['id'], $_POST['nom']);
}

if(isset($_POST['validate'])){
    $c->validate($_POST['id']);
}

$recettes = $c->getAll();
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

<!-- SIDEBAR -->
<td width="220" bgcolor="#2D6A4F" valign="top">

<h2 style="color:white; padding-left:15px;">
Smart<span style="color:#FF8C00;">Food</span>
</h2>

<p style="color:white; padding-left:15px;">Tableau de bord</p>
<p style="color:white; padding-left:15px;"><b>Gestion Recettes</b></p>
<p style="color:white; padding-left:15px;">Gestion Calories</p>
<p style="color:white; padding-left:15px;">Paramètres</p>

</td>

<!-- CONTENT -->
<td valign="top" style="padding:20px;">

<h2 style="color:#2D6A4F;">Back Office - Gestion Recettes</h2>

<!-- ================= ADD ================= -->
<h3>Ajouter Recette</h3>

<form method="POST">
Nom : <input type="text" name="nom" required><br><br>

Description : <br>
<textarea name="description" required></textarea><br><br>

Ingredients : <br>
<input type="text" name="ingredients"
placeholder="tomato cheese pasta"><br><br>

<button name="add"
style="background:#2D6A4F;color:white;padding:8px;">
Ajouter
</button>
</form>

<hr>

<!-- ================= UPDATE ================= -->
<h3>Modifier Recette</h3>

<form method="POST">
ID : <input type="number" name="id" required><br><br>

Nouveau Nom : <input type="text" name="nom" required><br><br>

<button name="update"
style="background:#FF8C00;color:white;padding:8px;">
Modifier
</button>
</form>

<hr>

<!-- ================= DELETE + VALIDATE ================= -->
<h3>Supprimer / Valider</h3>

<form method="POST">
ID : <input type="number" name="id" required><br><br>

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

<!-- ================= TABLE ================= -->
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
<td><?= $r['id'] ?></td>
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