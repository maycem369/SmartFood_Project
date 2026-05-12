<?php
// ── Sécurité ─────────────────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Nutrition – SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f0f4f3; margin: 0; }
        .front-main-content { margin-top: 0; padding: 0; height: 100vh; display: flex; flex-direction: column; }
        #nutrition-front-frame {
            flex: 1;
            width: 100%;
            border: none;
            height: calc(100vh - 64px);
        }
    </style>
</head>
<body>

<!-- ── NAVBAR USER ── -->
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil" data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="index.php?action=recettes_front" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="index.php?action=nutrition_front" class="active" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<!-- Contenu : iframe vers le frontoffice nutrition existant -->
<div class="front-main-content">
    <iframe id="nutrition-front-frame" src="nutrition/view/frontoffice/index.php"></iframe>
</div>

<script>
// Synchroniser thème et langue avec l'iframe nutrition
function syncNutritionIframe() {
    var frame = document.getElementById('nutrition-front-frame');
    if (!frame || !frame.contentWindow) return;
    var theme = localStorage.getItem('sf_theme') || 'light';
    var lang  = localStorage.getItem('sf_lang')  || 'fr';
    frame.contentWindow.postMessage({ type: 'sf_theme', value: theme }, '*');
    frame.contentWindow.postMessage({ type: 'sf_lang',  value: lang  }, '*');
}
document.getElementById('nutrition-front-frame').addEventListener('load', syncNutritionIframe);

// Réémettre si le thème/langue change
var _origSetItem = localStorage.setItem.bind(localStorage);
localStorage.setItem = function(key, value) {
    _origSetItem(key, value);
    if (key === 'sf_theme' || key === 'sf_lang') syncNutritionIframe();
};
</script>

</body>
</html>
