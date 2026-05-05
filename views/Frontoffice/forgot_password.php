<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
</head>
<body>
<div class="login-container">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <h2 data-i18n="reset_title">Réinitialisation</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=forgot_password" novalidate>
        <div class="form-group">
            <label data-i18n="auth_email">Email</label>
            <input type="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary" data-i18n="send_link_btn">Envoyer le lien</button>
    </form>
    <div class="links">
        <a href="index.php" data-i18n="back_to_login">Retour à la connexion</a>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>