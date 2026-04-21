<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();

$email = 'admin@smartfood.com';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Supprimer l'ancien admin s'il existe
$db->prepare("DELETE FROM utilisateur WHERE email = ?")->execute([$email]);

// Insérer le nouvel admin
$stmt = $db->prepare("INSERT INTO utilisateur (nom, prenom, email, motDePasse, role) VALUES ('Admin', 'SmartFood', ?, ?, 'admin')");
$stmt->execute([$email, $hash]);

// Créer un profil vide pour cet admin
$userId = $db->lastInsertId();
$db->prepare("INSERT INTO profil (id_user, photo) VALUES (?, 'default-avatar.png')")->execute([$userId]);

echo "Admin créé avec succès ! Email: admin@smartfood.com, Mot de passe: admin123";
?>