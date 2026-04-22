<?php

require_once __DIR__."/../config/Database.php";
require_once __DIR__."/../models/Ingredient.php";

class IngredientController{

function getAll(){

$db=Database::connect();

$sql="SELECT * FROM ingredient";

return $db->query($sql)->fetchAll();
}

function add($nom){

$db=Database::connect();

$sql="INSERT INTO ingredient(nom) VALUES(?)";

$stmt=$db->prepare($sql);
$stmt->execute([$nom]);
}

}