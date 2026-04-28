<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<nav class="top-navbar">
    <div class="logo-area"><h1>Smart<span>Food</span></h1></div>
    <div class="nav-links">
        <a href="index.php?action=admin_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php?action=users_list" class="active"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-utensils"></i> Recettes</a>
        <a href="#"><i class="fas fa-carrot"></i> Ingrédients</a>
    </div>
    <div class="user-menu"><span><?= htmlspecialchars($_SESSION['user_prenom']) ?></span><a href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></div>
</nav>
<div class="backoffice-container">
    <h1 class="page-title">Ajouter un utilisateur</h1>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="section-card">
        <form method="POST" action="index.php?action=add_user">
            <div class="two-columns">
                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
                <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
            </div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required minlength="6"></div>
            <div class="form-group"><label>Rôle</label><select name="role"><option value="user">Utilisateur</option><option value="admin">Administrateur</option></select></div>
            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </div>
</div>
</body>
</html>