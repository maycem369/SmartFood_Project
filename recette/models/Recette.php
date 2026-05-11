<?php

class Recette {

private $idrecette;
private $nom;
private $description;
private $status;

function __construct($id=null,$nom=null,$description=null,$status="Non validée"){
$this->idrecette=$id;
$this->nom=$nom;
$this->description=$description;
$this->status=$status;
}

function getId(){
return $this->idrecette;
}

function getNom(){
return $this->nom;
}

function getDescription(){
return $this->description;
}

function getStatus(){
return $this->status;
}

function setNom($nom){
$this->nom=$nom;
}

function setDescription($d){
$this->description=$d;
}

function setStatus($s){
$this->status=$s;
}

}