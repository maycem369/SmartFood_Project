<?php

require_once __DIR__ . "/../config/Database.php";

class IngredientController {

/* ================= HANDLE REQUEST ================= */

function handleRequest(){

if(isset($_POST['addIngredient'])){
$this->add($_POST['nomIngredient']);
}

}

/* ================= ADD INGREDIENT ================= */

function add($nom){

$db = Database::connect();

$sql = "INSERT INTO ingredient(nom) VALUES (?)";

$stmt = $db->prepare($sql);
$stmt->execute([$nom]);

}

/* ================= GET ALL ================= */

function getAll(){

$db = Database::connect();

$sql = "SELECT * FROM ingredient";

return $db->query($sql)->fetchAll();

}

}