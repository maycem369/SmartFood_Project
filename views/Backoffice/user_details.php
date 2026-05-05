<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<nav class="top-navbar">
    <div class="logo-area"><h1>Smart<span>Food</span></h1></div>
    <div class="nav-links">
        <a href="index.php?action=admin_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php?action=users_list" class="active"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-utensils"></i> Recettes</a>
        <a href="#"><i class="fas fa-carrot"></i> Ingrédients</a>
    </div>
    <div class="user-menu"><span><?= htmlspecialchars($_SESSION['user_prenom']) ?></span><a href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></div>
</nav>
<div class="backoffice-container">
    <h1 class="page-title">Détails de l'utilisateur</h1>
    <div class="section-card">
        <div class="info-group"><span class="info-label">Nom complet :</span> <?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></div>
        <div class="info-group"><span class="info-label">Email :</span> <?= htmlspecialchars($user['email']) ?></div>
        <div class="info-group"><span class="info-label">Rôle :</span> <span class="role-badge <?= $user['role']=='admin'?'role-admin':'role-user' ?>"><?= $user['role'] ?></span></div>
        <div class="info-group"><span class="info-label">Date d'inscription :</span> <?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></div>
        <div class="info-group"><span class="info-label">Âge :</span> <?= $user['age'] ?? 'Non renseigné' ?> ans</div>
        <div class="info-group"><span class="info-label">Sexe :</span> <?= $user['sexe'] ?? 'Non renseigné' ?></div>
        <div class="info-group"><span class="info-label">Poids / Taille :</span> <?= $user['poids'] ?? '-' ?> kg / <?= $user['taille'] ?? '-' ?> cm</div>
        <div class="info-group"><span class="info-label">Objectif :</span> <?= $user['objectif'] ?? 'Non renseigné' ?></div>
        <div class="info-group"><span class="info-label">Allergies :</span> <?= $user['allergies'] ?: 'Aucune' ?></div>
        <div class="flex mt-30">
            <a href="index.php?action=edit_user&id=<?= $user['idUser'] ?>" class="btn btn-orange">✏️ Modifier</a>
            <a href="index.php?action=users_list" class="btn btn-cancel">← Retour</a>
        </div>
    </div>
</div>
</body>
</html>