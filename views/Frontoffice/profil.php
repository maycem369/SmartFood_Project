<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" class="active" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="#" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="index.php?action=nutrition_front" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-main-content">
    <h1 style="color:var(--front-accent); margin-bottom:25px;" data-i18n="profile_title">Mon Profil</h1>
    <div class="card">
        <div class="profile-header">
            <img src="assets/uploads/<?= htmlspecialchars($profilData['photo'] ?? 'default-avatar.png') ?>"
                 alt="Photo" class="profile-img"
                 onerror="this.src='assets/uploads/default-avatar.png'">
            <div>
                <h2><?= htmlspecialchars($userData['prenom'] ?? '') ?> <?= htmlspecialchars($userData['nom'] ?? '') ?></h2>
                <p><?= htmlspecialchars($userData['email'] ?? '') ?></p>
                <span class="role-badge role-user" data-i18n="role_user">Utilisateur</span>
                <?php if ($imc): ?>
                    <div style="margin-top:8px;">
                        <span data-i18n="imc_current">IMC</span> : <strong><?= htmlspecialchars($imc) ?></strong>
                        <span class="imc-badge"><?= htmlspecialchars($imcLabel) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h3 data-i18n="health_info_title">Informations santé &amp; nutrition</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong data-i18n="edit_age">Âge</strong>
                <span class="info-value">
                    <?= $profilData['age'] ? htmlspecialchars($profilData['age']) . ' ans' : '<span data-i18n="not_filled">Non renseigné</span>' ?>
                </span>
            </div>
            <div class="info-item">
                <strong data-i18n="edit_sex">Sexe</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['sexe'] ?? '') ?><span style="display:<?= empty($profilData['sexe']) ? 'inline' : 'none' ?>" data-i18n="not_filled">Non renseigné</span></span>
            </div>
            <div class="info-item">
                <strong data-i18n="weight_label">Poids</strong>
                <span class="info-value">
                    <?= $profilData['poids'] ? htmlspecialchars($profilData['poids']) . ' kg' : '-' ?>
                </span>
            </div>
            <div class="info-item">
                <strong data-i18n="height_label">Taille</strong>
                <span class="info-value">
                    <?= $profilData['taille'] ? htmlspecialchars($profilData['taille']) . ' cm' : '-' ?>
                </span>
            </div>
            <div class="info-item">
                <strong data-i18n="objective_label">Objectif</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['objectif'] ?? '') ?><span style="display:<?= empty($profilData['objectif']) ? 'inline' : 'none' ?>" data-i18n="not_filled">Non renseigné</span></span>
            </div>
            <div class="info-item">
                <strong data-i18n="activity_label">Activité</strong>
                <span class="info-value"><?= htmlspecialchars($profilData['niveau_activite'] ?? '') ?><span style="display:<?= empty($profilData['niveau_activite']) ? 'inline' : 'none' ?>" data-i18n="not_filled">Non renseigné</span></span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <strong data-i18n="allergies_label">Allergies</strong>
                <span class="info-value">
                    <?= $profilData['allergies'] ? htmlspecialchars($profilData['allergies']) : '<span data-i18n="no_allergies">Aucune</span>' ?>
                </span>
            </div>
        </div>

        <div style="margin-top:40px;">
            <h3>🔐 Face ID</h3>
            <div style="padding: 20px; border-radius: 12px; <?= $hasFaceId ? 'background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3);' : 'background: rgba(255, 152, 0, 0.1); border: 1px solid rgba(255, 152, 0, 0.3);' ?>">
                <p style="margin: 0 0 12px 0;">
                    <span data-i18n="<?= $hasFaceId ? 'face_id_status_active' : 'face_id_status_inactive' ?>">
                        <?= $hasFaceId ? '✅ Face ID activé - Vous pouvez vous connecter avec votre visage !' : '⚠️ Aucun Face ID enregistré' ?>
                    </span>
                </p>
                <a href="index.php?action=face_register" class="btn btn-orange" data-i18n="<?= $hasFaceId ? 'manage_face' : 'enroll_face' ?>">
                    <?= $hasFaceId ? '🪪 Gérer Face ID' : '📷 Enregistrer mon visage' ?>
                </a>
            </div>
        </div>

        <div style="margin-top:40px;">
            <a href="index.php?action=edit_profile" class="btn btn-orange">✏️ <span data-i18n="edit_profile_title">Modifier mes informations</span></a>
        </div>
    </div>
</div>
<script src="assets/js/validation.js"></script>
</body>
</html>