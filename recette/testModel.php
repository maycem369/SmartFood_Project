<?php
require_once "models/Recette.php";

$recettes = Recette::getAll();

print_r($recettes);
?>