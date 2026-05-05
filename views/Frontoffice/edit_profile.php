<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="#" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="#" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-two-col">
    <!-- Parameter Sidebar -->
    <div class="param-sidebar">
        <div class="param-sidebar-card">
            <h3 data-i18n="sidebar_title">Paramètres</h3>
            <ul>
                <li><a href="index.php?action=profil"           data-i18n="sidebar_profile">👤 Mon Profil</a></li>
                <li><a href="index.php?action=edit_profile" class="active" data-i18n="sidebar_edit">✏️ Modifier Profil</a></li>
                <li><a href="index.php?action=face_register"    data-i18n="sidebar_faceid">🪪 Face ID</a></li>
                <li><a href="index.php?action=change_password"  data-i18n="sidebar_password">🔑 Mot de passe</a></li>
                <li><a href="index.php?action=settings"         data-i18n="sidebar_appearance">🌐 Apparence & Langue</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="param-content">
        <h1 style="color:var(--front-accent);" data-i18n="sidebar_edit">Modifier mon profil</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="index.php?action=update_profile" enctype="multipart/form-data" novalidate>

                <div class="photo-section" style="display:flex; align-items:center; gap:20px; margin-bottom:30px;">
                    <div class="photo-circle" style="width:100px; height:100px; border-radius:50%; overflow:hidden; border:3px solid var(--front-accent);">
                        <img src="assets/uploads/<?= htmlspecialchars($profilData['photo'] ?? 'default-avatar.png') ?>"
                             id="preview"
                             style="width:100%; height:100%; object-fit:cover;"
                             onerror="this.src='assets/uploads/default-avatar.png'">
                    </div>
                    <div>
                        <h3 data-i18n="prof_photo">Photo de profil</h3>
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
                        <button type="button" onclick="document.getElementById('photoInput').click()" class="btn btn-orange" data-i18n="prof_photo_btn">
                            Changer la photo
                        </button>
                    </div>
                </div>

                <div class="two-columns">
                    <div class="form-group">
                        <label data-i18n="edit_nom">Nom</label>
                        <input type="text" name="nom" id="ep-nom" value="<?= htmlspecialchars($userData['nom'] ?? '') ?>" autocomplete="family-name">
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_prenom">Prénom</label>
                        <input type="text" name="prenom" id="ep-prenom" value="<?= htmlspecialchars($userData['prenom'] ?? '') ?>" autocomplete="given-name">
                    </div>
                </div>
                <div class="form-group">
                    <label data-i18n="edit_email">Email</label>
                    <input type="text" name="email" id="ep-email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" autocomplete="email">
                </div>

                <h3 data-i18n="edit_health">Informations santé</h3>
                <div class="two-columns">
                    <div class="form-group">
                        <label data-i18n="edit_age">Âge</label>
                        <input type="text" name="age" id="ep-age" value="<?= htmlspecialchars($profilData['age'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_sex">Sexe</label>
                        <select name="sexe">
                            <?php $sexe = $profilData['sexe'] ?? 'Homme'; ?>
                            <option value="Homme"  <?= $sexe === 'Homme'  ? 'selected' : '' ?>>Homme</option>
                            <option value="Femme"  <?= $sexe === 'Femme'  ? 'selected' : '' ?>>Femme</option>
                            <option value="Autre"  <?= $sexe === 'Autre'  ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_weight">Poids (kg)</label>
                        <input type="text" name="poids" id="ep-poids" value="<?= htmlspecialchars($profilData['poids'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_height">Taille (cm)</label>
                        <input type="text" name="taille" id="ep-taille" value="<?= htmlspecialchars($profilData['taille'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_goal">Objectif</label>
                        <?php $objectif = $profilData['objectif'] ?? 'Perte de poids'; ?>
                        <select name="objectif">
                            <option value="Perte de poids" <?= $objectif === 'Perte de poids' ? 'selected' : '' ?>>Perte de poids</option>
                            <option value="Prise de masse" <?= $objectif === 'Prise de masse' ? 'selected' : '' ?>>Prise de masse</option>
                            <option value="Maintien"       <?= $objectif === 'Maintien'       ? 'selected' : '' ?>>Maintien</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label data-i18n="edit_activity">Activité</label>
                        <?php $activite = $profilData['niveau_activite'] ?? 'Modéré'; ?>
                        <select name="niveau_activite">
                            <option value="Sédentaire" <?= $activite === 'Sédentaire' ? 'selected' : '' ?>>Sédentaire</option>
                            <option value="Léger"      <?= $activite === 'Léger'      ? 'selected' : '' ?>>Léger</option>
                            <option value="Modéré"     <?= $activite === 'Modéré'     ? 'selected' : '' ?>>Modéré</option>
                            <option value="Élevé"      <?= $activite === 'Élevé'      ? 'selected' : '' ?>>Élevé</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label data-i18n="edit_allergies">Allergies</label>
                    <input type="text" name="allergies"
                           value="<?= htmlspecialchars($profilData['allergies'] ?? '') ?>"
                           placeholder="Ex: gluten, lactose...">
                </div>

                <div style="display:flex; gap:15px; margin-top:30px;">
                    <button type="submit" class="btn btn-orange" data-i18n="edit_save">Enregistrer</button>
                    <button type="button" onclick="history.back()" class="btn btn-secondary" data-i18n="cancel_btn">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('photoInput').addEventListener('change', function (e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function (ev) { document.getElementById('preview').src = ev.target.result; };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
<script src="assets/js/validation.js"></script>
</body>
</html>

