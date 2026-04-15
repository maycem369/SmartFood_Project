<?php 
 
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - SmartFood</title>
    <style>
        :root {
            --green: #2D6A4F;
            --orange: #FF8C00;
            --bg: #F8FAF9;
            --white: #FFFFFF;
            --text: #222222;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }
        
        .logo {
            padding: 0 25px 30px;
            font-size: 2.2rem;
            text-align: center;
        }
        .logo span { color: var(--orange); }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: #444;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: #E9F5EF;
            color: var(--green);
            border-left: 4px solid var(--green);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .user-avatar {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 3px solid var(--green);
        }

        .welcome {
            font-size: 2.1rem;
            color: var(--green);
            margin-bottom: 8px;
        }
        .subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            text-align: center;
        }
        .card h3 {
            font-size: 1.05rem;
            color: #555;
            margin-bottom: 12px;
        }
        .imc { font-size: 3.2rem; font-weight: 700; color: var(--green); }
        .calories { font-size: 3rem; font-weight: 700; color: var(--orange); }
        .objectif { font-size: 2.4rem; font-weight: 700; color: var(--green); }

        .btn-profile {
            background: var(--orange);
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(255,140,0,0.3);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Smart<span>Food</span></div>
        <div class="nav-menu">
            <a href="index.php?action=dashboard_user" class="active">🏠 Tableau de bord</a>
            <a href="index.php?action=profil">👤 Mon Profil</a>
            <a href="index.php?action=edit_profile">✏️ Modifier Profil</a>
            <a href="#">📊 Mes Régimes</a>
            <a href="#">🍽️ Recettes</a>
            <a href="index.php?action=change_password">🔑 Changer Mot de Passe</a>
            <a href="index.php?action=logout">🚪 Déconnexion</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div>
                <h1 class="welcome">Bienvenue, <?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Maycem') ?> ! 👋</h1>
                <p class="subtitle">Nous sommes heureux de vous revoir sur SmartFood.</p>
            </div>
            <div class="user-avatar">
                <img src="../assets/uploads/default-avatar.png" alt="Avatar">
                <div>
                    <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Maycem') ?></strong><br>
                    <small>Utilisateur</small>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card">
                <h3>IMC Actuel</h3>
                <div class="imc">22.8</div>
                <p style="color:#28a745; font-weight:600;">Normal</p>
            </div>

            <div class="card">
                <h3>Calories Aujourd'hui</h3>
                <div class="calories">1680 / 2200</div>
                <p style="color:#FF8C00; font-weight:600;">Il vous reste <strong>520 kcal</strong></p>
            </div>

            <div class="card">
                <h3>Objectif</h3>
                <div class="objectif">Perte de poids</div>
                <p style="margin-top:8px;">-0.8 kg cette semaine</p>
            </div>
        </div>

        <div style="text-align:center; margin-top:50px;">
            <button onclick="window.location.href='index.php?action=profil'" class="btn-profile">
                Voir mon profil complet
            </button>
        </div>
    </div>
    <script src="views/js/validation.js"></script>
</body>
</html>