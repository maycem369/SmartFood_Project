<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="assets/js/settings.js"></script>
    <title>Ajouter utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar admin-sidebar">
    <div class="logo">
        <h1 style="color:white;">Smart<span>Food</span></h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">
            <i class="fas fa-chart-line"></i> <span data-i18n="admin_nav_dashboard">Vue d'ensemble</span>
        </a></li>
        <li><a href="index.php?action=users_list" class="active">
            <i class="fas fa-user-shield"></i> <span data-i18n="admin_nav_users">Utilisateurs</span>
        </a></li>
        <li><a href="index.php?action=recettes_admin">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes &amp; Menus</span>
        </a></li>
        <li><a href="index.php?action=nutrition_admin">
            <i class="fas fa-apple-alt"></i> <span data-i18n="admin_nav_nutrition">Nutrition</span>
        </a></li>
        <li><a href="index.php?action=admin_configuration">
            <i class="fas fa-cog"></i> <span data-i18n="admin_nav_config">Configuration</span>
        </a></li>
    </ul>
    <div class="switch-mode" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <a href="index.php?action=logout" class="admin-link" style="color: var(--text-sidebar);">
            <i class="fas fa-sign-out-alt"></i> <span data-i18n="nav_logout">Déconnexion</span>
        </a>
    </div>
</div>
<div class="main-content">
    <h1 class="page-title" data-i18n="add_user_title">Ajouter un utilisateur</h1>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="section-card">
        <form method="POST" action="index.php?action=add_user">
            <div class="two-columns">
                <div class="form-group">
                    <label data-i18n="edit_firstname">Prénom</label>
                    <input type="text" name="prenom">
                </div>
                <div class="form-group">
                    <label data-i18n="edit_name">Nom</label>
                    <input type="text" name="nom">
                </div>
            </div>
            <div class="form-group">
                <label data-i18n="edit_email">Email</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label data-i18n="user_password">Mot de passe</label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label data-i18n="user_role">Rôle</label>
                <select name="role">
                    <option value="user" data-i18n="role_user">Utilisateur</option>
                    <option value="admin" data-i18n="admin_role">Administrateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-green" data-i18n="add_user">Ajouter</button>
        </form>
    </div>
</div>
</body>
</html>










