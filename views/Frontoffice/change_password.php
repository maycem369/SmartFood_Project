<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer mot de passe - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="#" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="#" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" class="active" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-two-col">
<<<<<<< HEAD
    <div class="param-sidebar">
        <div class="param-sidebar-card">
            <h3 data-i18n="sidebar_title">Paramètres</h3>
            <ul>
                <li><a href="index.php?action=profil" data-i18n="sidebar_profile">👤 Mon Profil</a></li>
                <li><a href="index.php?action=edit_profile" data-i18n="sidebar_edit">✏️ Modifier Profil</a></li>
                <li><a href="index.php?action=face_register" data-i18n="sidebar_faceid">🪪 Face ID</a></li>
                <li><a href="index.php?action=change_password" class="active" data-i18n="sidebar_password">🔑 Mot de passe</a></li>
                <li><a href="index.php?action=settings" data-i18n="sidebar_appearance">🌐 Apparence & Langue</a></li>
=======
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
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
            </ul>
        </div>
    </div>

<<<<<<< HEAD
    <div class="param-content">
        <div class="card">
            <h2 data-i18n="change_pwd_title">Changer mon mot de passe</h2>
=======
    <!-- Main Content -->
    <div class="param-content">
        <div class="card">
            <h2>Changer mon mot de passe</h2>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080

            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=update_password" novalidate>
                <div class="form-group">
<<<<<<< HEAD
                    <label data-i18n="current_pwd">Mot de passe actuel</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label data-i18n="new_pwd">Nouveau mot de passe</label>
                    <input type="password" name="new_password" required minlength="6">
                </div>
                <div class="form-group">
                    <label data-i18n="confirm_new_pwd">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary" data-i18n="change_pwd_btn">Changer</button>
=======
                    <label data-i18n="pwd_current">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="cur-pwd" autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label data-i18n="pwd_new">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new-pwd" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label data-i18n="pwd_confirm">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm-pwd" autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top:15px;" data-i18n="pwd_change_btn">Changer</button>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
            </form>
        </div>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>



