<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login");
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT * FROM utilisateur WHERE idUser = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { $_SESSION['error'] = "Utilisateur non trouvé"; header("Location: index.php?action=users_list"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar"><div class="logo"><h1>Smart<span>Food</span></h1></div><ul class="nav-menu"><li><a href="index.php?action=admin_dashboard">📊 Dashboard</a></li><li><a href="index.php?action=users_list" class="active">👥 Utilisateurs</a></li><li><a href="index.php?action=logout">🚪 Déconnexion</a></li></ul></div>
<div class="main-content">
    <h1 style="color:var(--green);">Modifier utilisateur</h1>
    <div class="card"><form method="POST" action="index.php?action=update_user" novalidate>
        <input type="hidden" name="id" value="<?= $user['idUser'] ?>">
        <div class="two-columns"><div class="form-group"><label>Prénom</label><input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required></div><div class="form-group"><label>Nom</label><input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required></div></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></div>
        <div class="form-group"><label>Rôle</label><select name="role"><option value="user" <?= $user['role']=='user'?'selected':'' ?>>Utilisateur</option><option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Administrateur</option></select></div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form></div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>