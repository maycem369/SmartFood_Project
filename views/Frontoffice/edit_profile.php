<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil">👤 Mon Profil</a></li>
        <li><a href="#">📋 Recettes</a></li>
        <li><a href="#">🥗 Nutrition</a></li>
        <li><a href="index.php?action=profil" class="active">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span>Bonjour, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-two-col">
    <!-- Parameter Sidebar -->
    <div class="param-sidebar">
        <div class="param-sidebar-card">
            <h3>Paramètres</h3>
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
        <h1 style="color:var(--green);">Modifier mon profil</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="index.php?action=update_profile" enctype="multipart/form-data" novalidate>

                <div class="photo-section">
                    <div class="photo-circle">
                        <img src="assets/uploads/<?= htmlspecialchars($profilData['photo'] ?? 'default-avatar.png') ?>"
                             id="preview"
                             onerror="this.src='assets/uploads/default-avatar.png'">
                    </div>
                    <div>
                        <h3>Photo de profil</h3>
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
                        <button type="button" onclick="document.getElementById('photoInput').click()" class="btn btn-orange">
                            Changer la photo
                        </button>
                    </div>
                </div>

                <div class="two-columns">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" id="ep-nom" value="<?= htmlspecialchars($userData['nom'] ?? '') ?>" autocomplete="family-name">
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom" id="ep-prenom" value="<?= htmlspecialchars($userData['prenom'] ?? '') ?>" autocomplete="given-name">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" id="ep-email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" autocomplete="email">
                </div>

                <h3>Informations santé</h3>
                <div class="two-columns">
                    <div class="form-group">
                        <label>Âge</label>
                        <input type="text" name="age" id="ep-age" value="<?= htmlspecialchars($profilData['age'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Sexe</label>
                        <select name="sexe">
                            <?php $sexe = $profilData['sexe'] ?? 'Homme'; ?>
                            <option value="Homme"  <?= $sexe === 'Homme'  ? 'selected' : '' ?>>Homme</option>
                            <option value="Femme"  <?= $sexe === 'Femme'  ? 'selected' : '' ?>>Femme</option>
                            <option value="Autre"  <?= $sexe === 'Autre'  ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Poids (kg)</label>
                        <input type="text" name="poids" id="ep-poids" value="<?= htmlspecialchars($profilData['poids'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Taille (cm)</label>
                        <input type="text" name="taille" id="ep-taille" value="<?= htmlspecialchars($profilData['taille'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Objectif</label>
                        <?php $objectif = $profilData['objectif'] ?? 'Perte de poids'; ?>
                        <select name="objectif">
                            <option value="Perte de poids" <?= $objectif === 'Perte de poids' ? 'selected' : '' ?>>Perte de poids</option>
                            <option value="Prise de masse" <?= $objectif === 'Prise de masse' ? 'selected' : '' ?>>Prise de masse</option>
                            <option value="Maintien"       <?= $objectif === 'Maintien'       ? 'selected' : '' ?>>Maintien</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Activité</label>
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
                    <label>Allergies</label>
                    <input type="text" name="allergies"
                           value="<?= htmlspecialchars($profilData['allergies'] ?? '') ?>"
                           placeholder="Ex: gluten, lactose...">
                </div>

                <div class="flex mt-30">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                    <button type="button" onclick="history.back()" class="btn btn-cancel">Annuler</button>
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
