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
    <title>Mon Profil - SmartFood</title>
    <style>
        :root {
            --green: #2D6A4F;
            --orange: #FF8C00;
            --bg: #F4F7F6;
            --white: #FFFFFF;
            --text: #333333;
            --border: #E0E0E0;
            --light-green: #E9F5EF;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--white);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .logo {
            padding: 25px;
            font-size: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }
        .logo span { color: var(--orange); }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: var(--light-green);
            color: var(--green);
            border-left: 4px solid var(--green);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
        }
        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 900px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 35px;
        }
        .profile-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 6px solid var(--green);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            object-fit: cover;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }
        .info-item {
            background: #f8f9f8;
            padding: 16px 20px;
            border-radius: 12px;
        }
        .info-item strong {
            display: block;
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Smart<span>Food</span></div>
        <div class="nav-menu">
            <a href="index.php?action=dashboard_user.php">🏠 Tableau de bord</a>
            <a href="index.php?action=profil" class="active">👤 Mon Profil</a>
            <a href="index.php?action=edit_profile">✏️ Modifier Profil</a>
            <a href="#">📊 Mes Régimes</a>
            <a href="#">🍽️ Recettes</a>
            <a href="index.php?action=change_password.php">🔑 Changer Mot de Passe</a>
            <a href="../../index.php?action=logout">🚪 Déconnexion</a>
        </div>
    </div>

    <div class="main-content">
        <h1>Mon Profil</h1>

        <div class="card">
            <!-- Header Profil -->
            <div class="profile-header">
                <img src="../assets/uploads/default-avatar.png" alt="Photo de profil" class="profile-img">
                <div>
                    <h2><?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Maycem') ?> <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Ben Ammar') ?></h2>
                    <p style="color:#666; font-size:1.1rem;"><?= htmlspecialchars($_SESSION['user_email'] ?? 'maycem@example.com') ?></p>
                    <span style="background:var(--light-green); color:var(--green); padding:5px 14px; border-radius:30px; font-size:0.9rem;">Utilisateur</span>
                </div>
            </div>

            <!-- Informations santé -->
            <h3 style="margin-bottom:20px; color:var(--green);">Informations santé & nutrition</h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <strong>Âge</strong>
                    <span style="font-size:1.4rem; font-weight:600;">28 ans</span>
                </div>
                <div class="info-item">
                    <strong>Sexe</strong>
                    <span style="font-size:1.4rem; font-weight:600;">Homme</span>
                </div>
                <div class="info-item">
                    <strong>Poids</strong>
                    <span style="font-size:1.4rem; font-weight:600;">72 kg</span>
                </div>
                <div class="info-item">
                    <strong>Taille</strong>
                    <span style="font-size:1.4rem; font-weight:600;">175 cm</span>
                </div>
                <div class="info-item">
                    <strong>Objectif</strong>
                    <span style="font-size:1.4rem; font-weight:600;">Perte de poids</span>
                </div>
                <div class="info-item">
                    <strong>Niveau d’activité</strong>
                    <span style="font-size:1.4rem; font-weight:600;">Modéré</span>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <strong>Allergies / Intolérances</strong>
                    <span style="font-size:1.1rem;">Aucune</span>
                </div>
            </div>

            <div style="margin-top:40px;">
                <a href="edit_profile.php" 
                   style="background:var(--orange); color:white; padding:14px 32px; border-radius:12px; text-decoration:none; display:inline-block;">
                    ✏️ Modifier mes informations
                </a>
            </div>
        </div>
    </div>
    <script src="views/js/validation.js"></script>
</body>
</html>