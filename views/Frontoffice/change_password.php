<?php  ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer Mot de Passe - SmartFood</title>
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
            padding: 25px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            padding: 0 25px 35px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 25px;
        }

        .logo h1 {
            color: var(--green);
            font-size: 2rem;
        }

        .logo span { color: var(--orange); }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li { margin-bottom: 6px; }

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

        .nav-menu a i { margin-right: 12px; font-size: 1.2rem; }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 35px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header h1 {
            color: var(--green);
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 3px solid var(--green);
        }

        .card {
            background: var(--white);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 600px;
        }

        h2 {
            color: var(--green);
            margin-bottom: 30px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--light-green);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
        }

        input:focus {
            border-color: var(--green);
            outline: none;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.12);
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-green {
            background: var(--green);
            color: white;
        }

        .btn-green:hover {
            background: #1f4a38;
        }

        .btn-cancel {
            background: #ccc;
            color: #333;
            margin-left: 12px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><h1>Smart<span>Food</span></h1></div>
        <ul class="nav-menu">
            <li><a href="index.php?action=dashboard_user"><i>🏠</i> Tableau de bord</a></li>
            <li><a href="index.php?action=profile"><i>👤</i> Mon Profil</a></li>
            <li><a href="index.php?action=edit_profile"><i>✏️</i> Modifier Profil</a></li>
            <li><a href="index.php?action=change_password" class="active"><i>🔑</i> Changer Mot de Passe</a></li>
            <li><a href="index.php?action=logout"><i>🚪</i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="card">
            <h2>Changer mon mot de passe</h2>
           <!-- Dans change_password.php, remplacer la ligne du formulaire -->
            <form method="POST" action="index.php?action=update_password">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" required><br><br>
                
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" required><br><br>
                
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" required><br><br>
                
                <button type="submit" style="background:var(--green); color:white; padding:14px 28px; border:none; border-radius:10px;">Changer le mot de passe</button>
            </form>
        </div>
    </div>
    <script src="views/js/validation.js"></script>
</body>
</html>