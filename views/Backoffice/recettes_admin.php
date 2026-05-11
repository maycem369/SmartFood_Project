<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Recettes & Menus – SmartFood Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f0f4f3; }
        .main-content { margin-left: var(--sidebar-w); padding: 0; height: 100vh; display: flex; flex-direction: column; }
        .sidebar { width: var(--sidebar-w); }
        #recette-frame {
            flex: 1;
            width: 100%;
            border: none;
            height: calc(100vh);
        }
    </style>
</head>
<body>

<!-- Sidebar Admin identique aux autres pages -->
<div class="sidebar admin-sidebar">
    <div class="logo">
        <h1 style="color:white;">Smart<span>Food</span></h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">
            <i class="fas fa-chart-line"></i> <span data-i18n="admin_nav_dashboard">Vue d'ensemble</span>
        </a></li>
        <li><a href="index.php?action=users_list">
            <i class="fas fa-user-shield"></i> <span data-i18n="admin_nav_users">Utilisateurs</span>
        </a></li>
        <li><a href="index.php?action=recettes_admin" class="active">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes & Menus</span>
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

<!-- Contenu : iframe vers le backoffice recette existant -->
<div class="main-content">
    <iframe id="recette-frame" src="recette/views/backoffice.php"></iframe>
</div>

</body>
</html>
