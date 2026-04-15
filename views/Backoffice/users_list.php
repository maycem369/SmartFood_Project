<?php 
// views/Backoffice/users_list.php
// NE PAS mettre session_start() ici car déjà fait dans index.php
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
    <title>Gestion Utilisateurs - SmartFood</title>
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
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 18px 15px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }
    th {
        background: var(--light-green);
        color: var(--green);
        font-weight: 600;
    }
    .btn-green {
        background: var(--green);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-green:hover { background: var(--dark-green); transform: translateY(-2px); }
    .btn-edit { 
        background: var(--orange); 
        color: white; 
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        margin-right: 8px;
    }
    .btn-delete { 
        background: #e74c3c; 
        color: white; 
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
    }
    .message {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
    }
    .success { background: #d4edda; color: #155724; }
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="color:var(--green);">Gestion des Utilisateurs</h1>
            <a href="index.php?action=add_user" class="btn-green">+ Ajouter un utilisateur</a>
        </div>

        <div class="card">
            <?php if (isset($users) && $users->rowCount() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['idUser']) ?></td>
                        <td><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <span style="background: <?= $row['role'] === 'admin' ? '#FF8C00' : '#2D6A4F' ?>; color:white; padding:5px 10px; border-radius:20px; font-size:0.8rem;">
                                <?= $row['role'] === 'admin' ? 'Admin' : 'Utilisateur' ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['date_creation'])) ?></td>
                        <td>
                            <a href="index.php?action=edit_user&id=<?= $row['idUser'] ?>" class="btn-edit">✏️ Modifier</a>
                            <a href="index.php?action=delete_user&id=<?= $row['idUser'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ? Cette action est irréversible.')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <?php else: ?>
                <p style="text-align: center; padding: 40px;">Aucun utilisateur trouvé.</p>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>