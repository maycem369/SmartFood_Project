<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
</head>
<body>
<div class="login-container">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <h2 data-i18n="auth_login">Connexion</h2>
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
        <div class="form-group">
            <label data-i18n="auth_email">Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label data-i18n="auth_pwd">Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" data-i18n="auth_login">Se connecter</button>
    </form>
    <div class="links">
        <p><a href="index.php?action=login_face" data-i18n="auth_faceid">🔐 Connexion par visage</a></p>
        <p><a href="index.php?action=forgot_password" data-i18n="auth_forgot">Mot de passe oublié ?</a></p>
        <p><a href="index.php?action=register" data-i18n="auth_no_account">Pas de compte ? S'inscrire</a></p>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>