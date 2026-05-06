<?php

class Database{

public static function connect(){

try{
return new PDO("mysql:host=localhost;dbname=smartfood","root","");
}catch(PDOException $e){
die("DB ERROR: ".$e->getMessage());
}

}

}