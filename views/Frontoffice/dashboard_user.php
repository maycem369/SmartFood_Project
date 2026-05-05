<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" class="active" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="#" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="#" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-main-content">
    <div class="header">
<<<<<<< HEAD
        <h1 data-i18n="nav_dashboard">Tableau de bord</h1>
=======
        <h1 data-i18n="dash_title">Tableau de bord</h1>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
        <div class="user-avatar">
            <img src="assets/uploads/<?= htmlspecialchars($_SESSION['user_photo'] ?? 'default-avatar.png') ?>" alt="Avatar">
            <div>
                <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></strong>
<<<<<<< HEAD
                <small data-i18n="role_user">Utilisateur</small>
=======
                <small data-i18n="dash_user_role">Utilisateur</small>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
            </div>
        </div>
    </div>

    <div class="card">
<<<<<<< HEAD
        <h2 class="welcome"><span data-i18n="dashboard_welcome">Bienvenue</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?> ! 👋</h2>
        <p class="subtitle" data-i18n="dashboard_sub">Nous sommes heureux de vous revoir sur SmartFood.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <h3 data-i18n="imc_current">IMC Actuel</h3>
=======
        <h2 class="welcome"><span data-i18n="dash_welcome">Bienvenue,</span> <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?> ! 👋</h2>
        <p class="subtitle" data-i18n="dash_subtitle">Nous sommes heureux de vous revoir sur SmartFood.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <h3 data-i18n="dash_imc">IMC Actuel</h3>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
                <?php if ($imc): ?>
                    <div class="stat-number"><?= htmlspecialchars($imc) ?></div>
                    <p><?= htmlspecialchars($imcLabel) ?></p>
                <?php else: ?>
                    <div class="stat-number">--</div>
<<<<<<< HEAD
                    <p data-i18n="profile_incomplete">Profil incomplet</p>
                <?php endif; ?>
            </div>
            <div class="stat-card">
                <h3 data-i18n="objective_label">Objectif</h3>
=======
                    <p data-i18n="dash_incomplete">Profil incomplet</p>
                <?php endif; ?>
            </div>
            <div class="stat-card">
                <h3 data-i18n="dash_goal">Objectif</h3>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
                <div class="stat-number" style="font-size:1.2rem;">
                    <?= htmlspecialchars($profilData['objectif'] ?? 'Non défini') ?>
                </div>
            </div>
            <div class="stat-card">
<<<<<<< HEAD
                <h3 data-i18n="activity_label">Activité</h3>
=======
                <h3 data-i18n="dash_activity">Activité</h3>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
                <div class="stat-number" style="font-size:1.2rem;">
                    <?= htmlspecialchars($profilData['niveau_activite'] ?? 'Non défini') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-30">
<<<<<<< HEAD
        <button onclick="location.href='index.php?action=profil'" class="btn btn-orange" data-i18n="view_full_profile">Voir mon profil complet</button>
=======
        <button onclick="location.href='index.php?action=profil'" class="btn btn-orange" data-i18n="dash_profile_btn">Voir mon profil complet</button>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>


