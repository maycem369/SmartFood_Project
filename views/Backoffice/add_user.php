<?php 
// views/Backoffice/add_user.php
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
    <title>Ajouter Utilisateur - SmartFood</title>
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
    .card {
        background: var(--white);
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.07);
        max-width: 600px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text);
    }
    input, select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 1rem;
    }
    input:focus, select:focus {
        border-color: var(--green);
        outline: none;
        box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.15);
    }
    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 1rem;
    }
    .btn-green { background: var(--green); color: white; }
    .btn-green:hover { background: var(--dark-green); transform: translateY(-2px); }
    .btn-cancel { background: #ccc; color: #333; text-decoration: none; display: inline-block; }
    .btn-cancel:hover { background: #bbb; }
    .message {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
    }
    .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><h1>Smart<span>Food</span></h1></div>
        <ul class="nav-menu">
            <li><a href="index.php?action=admin_dashboard">📊 Dashboard Admin</a></li>
            <li><a href="index.php?action=users_list" class="active">👥 Gestion Utilisateurs</a></li>
            <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h1 style="color:var(--green); margin-bottom: 30px;">Ajouter un nouvel utilisateur</h1>
        
        <div class="card">
            <form method="POST" action="index.php?action=add_user">
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" required>
                </div>
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe *</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Rôle *</label>
                    <select name="role" required>
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>

                <div style="margin-top: 35px; display: flex; gap: 15px;">
                    <button type="submit" class="btn btn-green">➕ Ajouter l'utilisateur</button>
                    <a href="index.php?action=users_list" class="btn btn-cancel">❌ Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>