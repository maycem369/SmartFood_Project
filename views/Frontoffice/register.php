<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
<<<<<<< HEAD
=======
    <script src="assets/js/chatbot.js" defer></script>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
</head>
<body>
<div class="register-container">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <h2 data-i18n="auth_register">Créer un compte</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=register" novalidate>
        <div class="two-columns">
<<<<<<< HEAD
            <div class="form-group">
                <label data-i18n="edit_name">Nom</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label data-i18n="edit_firstname">Prénom</label>
                <input type="text" name="prenom" required>
            </div>
        </div>
        <div class="form-group">
            <label data-i18n="auth_email">Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label data-i18n="auth_pwd">Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label data-i18n="auth_confirm">Confirmer</label>
            <input type="password" name="confirm_password" required>
        </div>
=======
            <div class="form-group"><label>Nom</label><input type="text" name="nom" id="reg-nom" autocomplete="family-name"></div>
            <div class="form-group"><label>Prénom</label><input type="text" name="prenom" id="reg-prenom" autocomplete="given-name"></div>
        </div>
        <div class="form-group"><label data-i18n="auth_email">Email</label><input type="text" name="email" id="reg-email" autocomplete="email"></div>
        <div class="form-group"><label data-i18n="auth_pwd">Mot de passe</label><input type="password" name="password" id="reg-password" autocomplete="new-password"></div>
        <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" id="reg-confirm" autocomplete="new-password"></div>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
        <button type="submit" class="btn btn-primary" data-i18n="auth_register">S'inscrire</button>
    </form>
    <div class="links">
        <p><a href="index.php" data-i18n="auth_has_account">Déjà inscrit ? Se connecter</a></p>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>


