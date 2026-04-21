<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login");
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("SELECT * FROM utilisateur ORDER BY idUser DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion utilisateurs - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1><p>Administration</p></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">📊 Dashboard</a></li>
        <li><a href="index.php?action=users_list" class="active">👥 Utilisateurs</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
    <?php if(isset($_SESSION['success'])): ?>
        <div class="message success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="justify-between"><h1 style="color:var(--green);">Gestion des utilisateurs</h1><a href="index.php?action=add_user" class="btn btn-green">+ Ajouter</a></div>
    <div class="card"><table><thead><tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Rôle</th><th>Date</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($users as $u): ?>
        <tr>
            <td><?= $u['idUser'] ?></td>
            <td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="role-badge <?= $u['role']=='admin'?'role-admin':'role-user' ?>"><?= $u['role'] ?></span></td>
            <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
            <td><a href="index.php?action=user_details&id=<?= $u['idUser'] ?>" class="btn-sm btn-view">Voir</a> <a href="index.php?action=edit_user&id=<?= $u['idUser'] ?>" class="btn-sm btn-edit">Modifier</a> <a href="index.php?action=delete_user&id=<?= $u['idUser'] ?>" class="btn-sm btn-delete" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>