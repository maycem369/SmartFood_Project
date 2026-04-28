<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="register-container">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <h2>Créer un compte</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=register" novalidate>
        <div class="two-columns">
            <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
            <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
        </div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" required></div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    <div class="links"><p>Déjà inscrit ? <a href="index.php">Se connecter</a></p></div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>