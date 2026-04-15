<?php 
// views/Backoffice/dashboard_admin.php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SmartFood</title>
    <style>
    :root {
        --green: #2D6A4F;
        --orange: #FF8C00;
        --bg: #F4F7F6;
        --white: #FFFFFF;
        --text: #333333;
        --border: #E0E0E0;
        --light-green: #E9F5EF;
        --dark-green: #1f4a38;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg);
        display: flex;
        min-height: 100vh;
    }
    .sidebar {
        width: 280px;
        background: var(--white);
        box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    .logo {
        padding: 0 30px 40px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 30px;
    }
    .logo h1 { color: var(--green); font-size: 2.2rem; font-weight: 700; }
    .logo span { color: var(--orange); }

    .nav-menu {
        list-style: none;
    }
    .nav-menu li { margin-bottom: 8px; }
    .nav-menu a {
        display: flex;
        align-items: center;
        padding: 16px 30px;
        color: var(--text);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .nav-menu a:hover,
    .nav-menu a.active {
        background: var(--light-green);
        color: var(--green);
        border-left: 5px solid var(--green);
    }

    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 40px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--green);
    }
    .stat-label {
        color: #666;
        margin-top: 10px;
    }
    .btn-green {
        background: var(--green);
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-green:hover { background: var(--dark-green); transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><h1>Smart<span>Food</span></h1></div>
        <ul class="nav-menu">
            <li><a href="index.php?action=admin_dashboard" class="active">📊 Dashboard Admin</a></li>
            <li><a href="index.php?action=users_list">👥 Gestion Utilisateurs</a></li>
            <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1 style="color:var(--green);">Bonjour, <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Admin') ?> ! 👋</h1>
        <p style="font-size:1.1rem; color:#555; margin-top: 10px;">Bienvenue dans votre espace d'administration SmartFood.</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">👥</div>
                <div class="stat-label">Utilisateurs inscrits</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">🍽️</div>
                <div class="stat-label">Recettes disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">📊</div>
                <div class="stat-label">Régimes actifs</div>
            </div>
        </div>

        <div style="margin-top: 40px;">
            <a href="index.php?action=users_list" class="btn-green">
                👥 Gérer les utilisateurs
            </a>
        </div>
    </div>
</body>
</html>