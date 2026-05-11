<?php

class Ingredient{

private $idingredient;
private $nom;

function __construct($id=null,$nom=null){
$this->idingredient=$id;
$this->nom=$nom;
}

function getId(){
return $this->idingredient;
}

function getNom(){
return $this->nom;
}

}