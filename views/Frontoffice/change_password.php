<?php if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer mot de passe - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=dashboard_user">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil">👤 Mon Profil</a></li>
        <li><a href="index.php?action=edit_profile">✏️ Modifier Profil</a></li>
        <li><a href="index.php?action=change_password" class="active">🔑 Changer mot de passe</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="card">
        <h2>Changer mon mot de passe</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?action=update_password" novalidate>
            <div class="form-group"><label>Mot de passe actuel</label><input type="password" name="current_password" required></div>
            <div class="form-group"><label>Nouveau mot de passe</label><input type="password" name="new_password" required></div>
            <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn btn-primary">Changer</button>
        </form>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>