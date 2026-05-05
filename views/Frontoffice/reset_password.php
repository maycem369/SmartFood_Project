<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<div class="login-container">
    <h2>Nouveau mot de passe</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=reset_password&token=<?= urlencode($token) ?>" novalidate>
        <div class="form-group"><label>Nouveau mot de passe</label><input type="password" name="password" id="reset-pwd" autocomplete="new-password"></div>
        <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" id="reset-confirm" autocomplete="new-password"></div>
        <button type="submit" class="btn btn-primary">Changer</button>
    </form>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>




