<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=dashboard_user">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" class="active">👤 Mon Profil</a></li>
        <li><a href="index.php?action=edit_profile">✏️ Modifier Profil</a></li>
        <li><a href="index.php?action=change_password">🔑 Changer mot de passe</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
    <h1 style="color:var(--green); margin-bottom:25px;">Mon Profil</h1>
    <div class="card">
        <div class="profile-header">
            <img src="assets/uploads/<?= htmlspecialchars($profilData['photo'] ?? 'default-avatar.png') ?>"
                 alt="Photo" class="profile-img"
                 onerror="this.src='assets/uploads/default-avatar.png'">
            <div>
                <h2><?= htmlspecialchars($userData['prenom'] ?? '') ?> <?= htmlspecialchars($userData['nom'] ?? '') ?></h2>
                <p><?= htmlspecialchars($userData['email'] ?? '') ?></p>
                <span class="role-badge role-user">Utilisateur</span>
                <?php if ($imc): ?>
                    <div style="margin-top:8px;">
                        IMC : <strong><?= htmlspecialchars($imc) ?></strong>
                        <span class="imc-badge"><?= htmlspecialchars($imcLabel) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h3>Informations santé &amp; nutrition</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Âge</strong>
                <span class="info-value">
                    <?= $profilData['age'] ? htmlspecialchars($profilData['age']) . ' ans' : 'Non renseigné' ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Sexe</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['sexe'] ?? 'Non renseigné') ?></span>
            </div>
            <div class="info-item">
                <strong>Poids</strong>
                <span class="info-value">
                    <?= $profilData['poids'] ? htmlspecialchars($profilData['poids']) . ' kg' : '-' ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Taille</strong>
                <span class="info-value">
                    <?= $profilData['taille'] ? htmlspecialchars($profilData['taille']) . ' cm' : '-' ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Objectif</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['objectif'] ?? 'Non renseigné') ?></span>
            </div>
            <div class="info-item">
                <strong>Activité</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['niveau_activite'] ?? 'Non renseigné') ?></span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <strong>Allergies</strong>
                <span class="info-value">
                    <?= $profilData['allergies'] ? htmlspecialchars($profilData['allergies']) : 'Aucune' ?>
                </span>
            </div>
        </div>

        <div style="margin-top:40px;">
            <a href="index.php?action=edit_profile" class="btn btn-orange">✏️ Modifier mes informations</a>
        </div>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>