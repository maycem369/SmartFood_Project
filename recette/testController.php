<?php
require_once "controllers/RecetteController.php";

$c = new RecetteController();

$c->ajouter("Salade", "Healthy");

echo "Inserted";
?>