<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Modifier utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
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
        <li><a href="#">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes & Menus</span>
        </a></li>
        <li><a href="#">
            <i class="fas fa-database"></i> <span data-i18n="admin_nav_ingredients">Ingrédients</span>
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
    <h1 class="page-title">Modifier utilisateur</h1>
    <div class="section-card">
        <form method="POST" action="index.php?action=update_user">
            <input type="hidden" name="id" value="<?= $user['idUser'] ?>">
            <div class="two-columns">
                <div class="form-group"><label>PrÃ©nom</label><input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>"></div>
                <div class="form-group"><label>Nom</label><input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>"></div>
            </div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"></div>
            <div class="form-group"><label>RÃ´le</label><select name="role"><option value="user" <?= $user['role']=='user'?'selected':'' ?>>Utilisateur</option><option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Administrateur</option></select></div>
            <button type="submit" class="btn btn-green">Enregistrer</button>
            <a href="index.php?action=users_list" class="btn btn-cancel">Annuler</a>
        </form>
    </div>
</div>
</body>
</html>










