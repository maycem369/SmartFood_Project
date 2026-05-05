<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-container">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <h2>Connexion</h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['info'])): ?>
        <div class="message success"><?= $_SESSION['info']; unset($_SESSION['info']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=login" novalidate>
        <div class="form-group"><label>Email</label><input type="text" name="email" id="login-email" autocomplete="email"></div>
        <div class="form-group"><label>Mot de passe</label><input type="password" name="password" id="login-pwd" autocomplete="current-password"></div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <div class="links">
        <p><a href="index.php?action=login_face">🔐 Connexion par visage</a></p>
        <p><a href="index.php?action=forgot_password">Mot de passe oublié ?</a></p>
        <p>Pas de compte ? <a href="index.php?action=register">S'inscrire</a></p>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>