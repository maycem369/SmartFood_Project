<?php
require_once __DIR__ . "/../controllers/RecetteController.php";

$c = new RecetteController();

$recettes = $c->getAll();

/* ================= SEARCH ================= */
if(isset($_GET['search']) && !empty($_GET['ingredients'])) {

    $selected = $_GET['ingredients'];
    $recettes = [];

    foreach($selected as $ingredient) {
        $results = $c->search($ingredient);

        foreach($results as $r) {
            $recettes[$r['id']] = $r;
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

<h2 style="color:#2D6A4F;">Front Office - Recettes</h2>

<!-- ================= CHECKBOX ================= -->
<form method="GET">

<h3>Rechercher par ingrédients</h3>

<label><input type="checkbox" name="ingredients[]" value="tomate"> Tomate</label><br>
<label><input type="checkbox" name="ingredients[]" value="fromage"> Fromage</label><br>
<label><input type="checkbox" name="ingredients[]" value="pâtes"> Pâtes</label><br>
<label><input type="checkbox" name="ingredients[]" value="poulet"> Poulet</label><br>
<label><input type="checkbox" name="ingredients[]" value="œuf"> Œuf</label><br>
<label><input type="checkbox" name="ingredients[]" value="pain"> Pain</label><br>
<label><input type="checkbox" name="ingredients[]" value="riz"> Riz</label><br>
<label><input type="checkbox" name="ingredients[]" value="carotte"> Carotte</label><br>
<label><input type="checkbox" name="ingredients[]" value="concombre"> Concombre</label><br>

<!-- ✅ ADDED ONLY THIS -->
<label><input type="checkbox" name="ingredients[]" value="steak haché"> Steak haché</label><br><br>

<button type="submit" name="search"
style="padding:8px;background:#2D6A4F;color:white;">
Rechercher
</button>

</form>

<br>

<!-- ================= SHOW ALL ================= -->
<form method="GET">

<button type="submit" name="all"
style="padding:8px;background:#FF8C00;color:white;">
Consulter toutes les recettes
</button>

</form>

<br><br>

<!-- ================= TABLE ================= -->
<table border="1" width="100%" cellpadding="8">

<tr style="background:#2D6A4F;color:white;">
<th>ID</th>
<th>Nom</th>
<th>Description</th>
<th>Ingrédients</th>
<th>Status</th>
</tr>

<?php
$map = [
    "tomate" => "Tomate",
    "fromage" => "Fromage",
    "pâtes" => "Pâtes",
    "poulet" => "Poulet",
    "œuf" => "Œuf",
    "pain" => "Pain",
    "riz" => "Riz",
    "carotte" => "Carotte",
    "concombre" => "Concombre",

    // ✅ ADDED ONLY THIS
    "steak haché" => "Steak haché"
];
?>

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