<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer mot de passe - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil">👤 Mon Profil</a></li>
        <li><a href="#">📋 Recettes</a></li>
        <li><a href="#">🥗 Nutrition</a></li>
        <li><a href="index.php?action=profil" class="active">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span>Bonjour, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-two-col">
    <!-- Parameter Sidebar -->
    <div class="param-sidebar">
        <div class="param-sidebar-card">
            <h3>Paramètres</h3>
            <ul>
                <li><a href="index.php?action=profil"           data-i18n="sidebar_profile">👤 Mon Profil</a></li>
                <li><a href="index.php?action=edit_profile"     data-i18n="sidebar_edit">✏️ Modifier Profil</a></li>
                <li><a href="index.php?action=face_register"    data-i18n="sidebar_faceid">🪪 Face ID</a></li>
                <li><a href="index.php?action=change_password" class="active" data-i18n="sidebar_password">🔑 Mot de passe</a></li>
                <li><a href="index.php?action=settings"         data-i18n="sidebar_appearance">🌐 Apparence & Langue</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="param-content">
        <div class="card">
            <h2>Changer mon mot de passe</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=update_password" novalidate>
                <div class="form-group">
                    <label>Mot de passe actuel</label>
                    <input type="password" name="current_password" id="cur-pwd" autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new-pwd" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label>Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm-pwd" autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary">Changer</button>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>
