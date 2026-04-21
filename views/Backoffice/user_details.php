<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login");
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT u.*, p.age, p.sexe, p.poids, p.taille, p.objectif, p.niveau_activite, p.allergies FROM utilisateur u LEFT JOIN profil p ON u.idUser = p.id_user WHERE u.idUser = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { $_SESSION['error'] = "Utilisateur non trouvé"; header("Location: index.php?action=users_list"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar"><div class="logo"><h1>Smart<span>Food</span></h1></div><ul class="nav-menu"><li><a href="index.php?action=admin_dashboard">📊 Dashboard</a></li><li><a href="index.php?action=users_list" class="active">👥 Utilisateurs</a></li><li><a href="index.php?action=logout">🚪 Déconnexion</a></li></ul></div>
<div class="main-content">
    <h1 style="color:var(--green);">Détails de l'utilisateur</h1>
    <div class="card">
        <div class="info-group"><span class="info-label">Nom complet :</span> <?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></div>
        <div class="info-group"><span class="info-label">Email :</span> <?= htmlspecialchars($user['email']) ?></div>
        <div class="info-group"><span class="info-label">Rôle :</span> <span class="role-badge <?= $user['role']=='admin'?'role-admin':'role-user' ?>"><?= $user['role'] ?></span></div>
        <div class="info-group"><span class="info-label">Inscription :</span> <?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></div>
        <div class="info-group"><span class="info-label">Âge :</span> <?= $user['age'] ?? 'Non renseigné' ?> ans</div>
        <div class="info-group"><span class="info-label">Sexe :</span> <?= $user['sexe'] ?? 'Non renseigné' ?></div>
        <div class="info-group"><span class="info-label">Poids / Taille :</span> <?= $user['poids'] ?? '-' ?> kg / <?= $user['taille'] ?? '-' ?> cm</div>
        <div class="info-group"><span class="info-label">Objectif :</span> <?= $user['objectif'] ?? 'Non renseigné' ?></div>
        <div class="info-group"><span class="info-label">Allergies :</span> <?= $user['allergies'] ?: 'Aucune' ?></div>
        <div class="flex mt-30"><a href="index.php?action=edit_user&id=<?= $user['idUser'] ?>" class="btn btn-orange">✏️ Modifier</a> <a href="index.php?action=users_list" class="btn btn-cancel">← Retour</a></div>
    </div>
</div>
</body>
</html>