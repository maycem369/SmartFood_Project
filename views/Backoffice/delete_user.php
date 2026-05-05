<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
</head>
<body>
<div class="center-container" style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
    <div class="card" style="text-align:center; max-width:500px;">
        <h2 style="color:#e74c3c;">⚠️ Suppression</h2>
        <p>Voulez-vous vraiment supprimer
            <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong> ?
        </p>
        <p style="color:#e74c3c;">Cette action est irréversible !</p>
        <div class="flex" style="justify-content:center; margin-top:30px;">
            <a href="index.php?action=delete_user_confirm&id=<?= (int)$user['idUser'] ?>" class="btn btn-danger">Oui, supprimer</a>
            <a href="index.php?action=users_list" class="btn btn-cancel">Annuler</a>
        </div>
    </div>
</div>
</body>
</html>