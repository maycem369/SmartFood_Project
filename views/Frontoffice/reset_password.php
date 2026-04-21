<?php
$token = $_GET['token'] ?? '';
if (!$token) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-container">
    <h2>Nouveau mot de passe</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=reset_password&token=<?= urlencode($token) ?>" novalidate>
        <div class="form-group"><label>Nouveau mot de passe</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" required></div>
        <button type="submit" class="btn btn-primary">Changer</button>
    </form>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>