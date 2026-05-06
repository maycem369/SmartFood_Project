<?php
require_once __DIR__."/../config/Database.php";

class IngredientController{

function handleRequest(){

if(isset($_POST['addIngredient']) && !empty($_POST['nomIngredient'])){
$this->add($_POST['nomIngredient']);
}

}

function add($nom){

$db=Database::connect();

$db->prepare("INSERT INTO ingredient(nom) VALUES(?)")
->execute([$nom]);

}

function getAll(){

$db=Database::connect();

return $db->query("SELECT * FROM ingredient")->fetchAll();

}

}