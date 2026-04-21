<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar"><div class="logo"><h1>Smart<span>Food</span></h1></div><ul class="nav-menu"><li><a href="index.php?action=admin_dashboard">📊 Dashboard</a></li><li><a href="index.php?action=users_list" class="active">👥 Utilisateurs</a></li><li><a href="index.php?action=logout">🚪 Déconnexion</a></li></ul></div>
<div class="main-content">
    <h1 style="color:var(--green);">Ajouter un utilisateur</h1>
    <?php if(isset($_SESSION['error'])): ?><div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card"><form method="POST" action="index.php?action=add_user" novalidate>
        <div class="two-columns"><div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div><div class="form-group"><label>Nom</label><input type="text" name="nom" required></div></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required minlength="6"></div>
        <div class="form-group"><label>Rôle</label><select name="role"><option value="user">Utilisateur</option><option value="admin">Administrateur</option></select></div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form></div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>