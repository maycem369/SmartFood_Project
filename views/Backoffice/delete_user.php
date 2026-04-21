<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login");
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT * FROM utilisateur WHERE idUser = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { $_SESSION['error'] = "Utilisateur non trouvé"; header("Location: index.php?action=users_list"); exit(); }
if ($id == $_SESSION['user_id']) { $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte"; header("Location: index.php?action=users_list"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="center-container" style="display:flex; justify-content:center; align-items:center; min-height:100vh;">
    <div class="card" style="text-align:center; max-width:500px;">
        <h2 style="color:#e74c3c;">⚠️ Suppression</h2>
        <p>Voulez-vous vraiment supprimer <strong><?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></strong> ?</p>
        <p style="color:#e74c3c;">Action irréversible !</p>
        <div class="flex" style="justify-content:center; margin-top:30px;">
            <a href="index.php?action=delete_user_confirm&id=<?= $id ?>" class="btn btn-danger">Oui, supprimer</a>
            <a href="index.php?action=users_list" class="btn btn-cancel">Annuler</a>
        </div>
    </div>
</div>
</body>
</html>