<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=dashboard_user">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil">👤 Mon Profil</a></li>
        <li><a href="index.php?action=edit_profile" class="active">✏️ Modifier Profil</a></li>
        <li><a href="index.php?action=change_password">🔑 Changer mot de passe</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
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
                    <input type="text" name="nom" value="<?= htmlspecialchars($userData['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($userData['prenom'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>">
            </div>

            <h3>Informations santé</h3>
            <div class="two-columns">
                <div class="form-group">
                    <label>Âge</label>
                    <input type="number" name="age" value="<?= htmlspecialchars($profilData['age'] ?? '') ?>">
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
                    <input type="number" step="0.1" name="poids" value="<?= htmlspecialchars($profilData['poids'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Taille (cm)</label>
                    <input type="number" step="0.1" name="taille" value="<?= htmlspecialchars($profilData['taille'] ?? '') ?>">
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