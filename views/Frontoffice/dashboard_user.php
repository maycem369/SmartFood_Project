<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=dashboard_user" class="active">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil">👤 Mon Profil</a></li>
        <li><a href="index.php?action=edit_profile">✏️ Modifier Profil</a></li>
        <li><a href="index.php?action=change_password">🔑 Changer mot de passe</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="header">
        <h1>Tableau de bord</h1>
        <div class="user-avatar">
            <img src="assets/uploads/<?= htmlspecialchars($_SESSION['user_photo'] ?? 'default-avatar.png') ?>" alt="Avatar">
            <div>
                <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></strong>
                <small>Utilisateur</small>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="welcome">Bienvenue, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?> ! 👋</h2>
        <p class="subtitle">Nous sommes heureux de vous revoir sur SmartFood.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>IMC Actuel</h3>
                <?php if ($imc): ?>
                    <div class="stat-number"><?= htmlspecialchars($imc) ?></div>
                    <p><?= htmlspecialchars($imcLabel) ?></p>
                <?php else: ?>
                    <div class="stat-number">--</div>
                    <p>Profil incomplet</p>
                <?php endif; ?>
            </div>
            <div class="stat-card">
                <h3>Objectif</h3>
                <div class="stat-number" style="font-size:1.2rem;">
                    <?= htmlspecialchars($profilData['objectif'] ?? 'Non défini') ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Activité</h3>
                <div class="stat-number" style="font-size:1.2rem;">
                    <?= htmlspecialchars($profilData['niveau_activite'] ?? 'Non défini') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-30">
        <button onclick="location.href='index.php?action=profil'" class="btn btn-orange">Voir mon profil complet</button>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>