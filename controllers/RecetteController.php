<?php

require_once __DIR__."/../config/Database.php";

class RecetteController{

/* ================= HANDLE REQUEST ================= */

function handleRequest(){

if(isset($_POST['add'])){
$this->addRecette($_POST);
}

if(isset($_POST['delete'])){
$this->delete($_POST['id']);
}

if(isset($_POST['update'])){
$this->update($_POST['id'],$_POST['nom']);
}

if(isset($_POST['validate'])){
$this->validate($_POST['id']);
}

}

/* ================= ADD RECETTE ================= */

function addRecette($data){

$db=Database::connect();

$sql="INSERT INTO recettes(nom,description,status)
VALUES(?,?, 'Non validée')";

$stmt=$db->prepare($sql);
$stmt->execute([
$data['nom'],
$data['description']
]);

$idrecette = $db->lastInsertId();

if(isset($data['ingredients'])){
foreach($data['ingredients'] as $iding){
$this->addIngredientToRecette($idrecette,$iding);
}
}

}

/* ================= ADD RELATION ================= */

function addIngredientToRecette($idrecette,$idingredient){

$db=Database::connect();

$sql="INSERT INTO recette_ingredient
VALUES(?,?)";

$stmt=$db->prepare($sql);
$stmt->execute([$idrecette,$idingredient]);

}

/* ================= GET ALL ================= */

function getAll(){

$db=Database::connect();

$sql="
SELECT r.idrecette,r.nom,r.description,r.status,
GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
FROM recettes r
LEFT JOIN recette_ingredient ri ON r.idrecette=ri.idrecette
LEFT JOIN ingredient i ON ri.idingredient=i.idingredient
GROUP BY r.idrecette
";

return $db->query($sql)->fetchAll();
}

/* ================= SEARCH ================= */

function search($ingredient){

$db=Database::connect();

$sql="
SELECT r.idrecette,r.nom,r.description,r.status,
GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
FROM recettes r
JOIN recette_ingredient ri ON r.idrecette=ri.idrecette
JOIN ingredient i ON ri.idingredient=i.idingredient
WHERE r.idrecette IN (

SELECT r2.idrecette
FROM recettes r2
JOIN recette_ingredient ri2 ON r2.idrecette=ri2.idrecette
JOIN ingredient i2 ON ri2.idingredient=i2.idingredient
WHERE i2.nom=?

)

GROUP BY r.idrecette
";

$stmt=$db->prepare($sql);
$stmt->execute([$ingredient]);

return $stmt->fetchAll();
}

/* ================= DELETE ================= */

function delete($id){

$db=Database::connect();

$db->prepare("DELETE FROM recette_ingredient WHERE idrecette=?")
->execute([$id]);

$db->prepare("DELETE FROM recettes WHERE idrecette=?")
->execute([$id]);

}

/* ================= UPDATE ================= */

function update($id,$nom){

$db=Database::connect();

$sql="UPDATE recettes SET nom=? WHERE idrecette=?";

$stmt=$db->prepare($sql);
$stmt->execute([$nom,$id]);

}

/* ================= VALIDATE ================= */

function validate($id){

$db=Database::connect();

$sql="UPDATE recettes 
SET status='Validée'
WHERE idrecette=?";

$stmt=$db->prepare($sql);
$stmt->execute([$id]);

}

}