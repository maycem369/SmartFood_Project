<!DOCTYPE html>
<html lang="fr" data-theme="light" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Apparence & Langue – SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- settings.js chargé EN PREMIER pour éviter le flash de thème -->
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
</head>
<body>
<nav class="front-navbar">
    <div class="logo">Smart<span>Food</span></div>
    <ul class="front-nav-links">
        <li><a href="index.php?action=dashboard_user" data-i18n="nav_dashboard">🏠 Tableau de bord</a></li>
        <li><a href="index.php?action=profil"         data-i18n="nav_profile">👤 Mon Profil</a></li>
        <li><a href="index.php?action=recettes_front" data-i18n="nav_recipes">📋 Recettes</a></li>
        <li><a href="index.php?action=nutrition_front" data-i18n="nav_nutrition">🥗 Nutrition</a></li>
        <li><a href="index.php?action=settings" class="active" data-i18n="nav_settings">⚙️ Paramètres</a></li>
    </ul>
    <div class="front-user-menu">
        <span><span data-i18n="nav_hello">Bonjour</span>, <?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-danger btn-sm" data-i18n="nav_logout">🚪 Déconnexion</a>
    </div>
</nav>

<div class="front-two-col">
    <!-- Sidebar paramètres -->
    <div class="param-sidebar">
        <div class="param-sidebar-card">
            <h3 data-i18n="sidebar_title">Paramètres</h3>
            <ul>
                <li><a href="index.php?action=profil"           data-i18n="sidebar_profile">👤 Mon Profil</a></li>
                <li><a href="index.php?action=edit_profile"     data-i18n="sidebar_edit">✏️ Modifier Profil</a></li>
                <li><a href="index.php?action=face_register"    data-i18n="sidebar_faceid">🪪 Face ID</a></li>
                <li><a href="index.php?action=change_password"  data-i18n="sidebar_password">🔑 Mot de passe</a></li>
                <li><a href="index.php?action=settings" class="active" data-i18n="sidebar_appearance">🌐 Apparence & Langue</a></li>
            </ul>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="param-content">
        <div style="max-width:660px;">

            <!-- En-tête -->
            <div style="margin-bottom:28px;">
                <h2 style="font-family:'Poppins',sans-serif;color:var(--front-accent);margin-bottom:6px;" data-i18n="settings_title">
                    Apparence & Langue
                </h2>
                <p style="color:var(--text-muted);font-size:.9rem;" data-i18n="settings_sub">
                    Personnalisez votre expérience SmartFood
                </p>
            </div>

            <!-- ── SECTION MODE NUIT ──────────────────────────── -->
            <div class="settings-card">
                <div class="settings-section-title">
                    🌙 <span data-i18n="dark_mode_title">Mode Nuit</span>
                </div>
                <p class="settings-section-desc" data-i18n="dark_mode_desc">
                    Réduisez la fatigue oculaire avec un thème sombre
                </p>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-icon" id="themeIcon">🌙</div>
                        <div>
                            <div class="toggle-label" data-i18n="dark_mode_title">Mode Nuit</div>
                            <div class="toggle-sub"   data-i18n="dark_mode_desc">
                                Réduisez la fatigue oculaire avec un thème sombre
                            </div>
                        </div>
                    </div>

                    <label class="sf-toggle" id="themeToggleLabel">
                        <input type="checkbox" id="darkModeToggle">
                        <div class="sf-toggle-track">
                            <div class="sf-toggle-thumb"></div>
                        </div>
                        <span class="sf-toggle-state" id="darkModeLabel">Désactivé</span>
                    </label>
                </div>

                <!-- Prévisualisation live -->
                <div id="themePreview" style="
                    margin-top:16px; border-radius:14px; padding:16px 20px;
                    background:var(--bg-light); border:1px solid var(--border-color);
                    display:flex; align-items:center; gap:12px; font-size:.85rem; color:var(--text-muted);
                ">
                    <span style="font-size:1.4rem;" id="previewEmoji">☀️</span>
                    <span id="previewText">Mode clair actif — interface lumineuse</span>
                </div>
            </div>

            <!-- ── SECTION LANGUE ─────────────────────────────── -->
            <div class="settings-card">
                <div class="settings-section-title">
                    🌐 <span data-i18n="lang_title">Langue de l'interface</span>
                </div>
                <p class="settings-section-desc" data-i18n="lang_desc">
                    Choisissez la langue d'affichage
                </p>

                <div class="lang-grid">
                    <button class="lang-btn" data-lang="fr" onclick="selectLang('fr')" id="lang-fr">
                        <span class="lang-flag">🇫🇷</span>
                        <span class="lang-name" data-i18n="lang_fr">Français</span>
                        <span class="lang-native">Français</span>
                    </button>
                    <button class="lang-btn" data-lang="en" onclick="selectLang('en')" id="lang-en">
                        <span class="lang-flag">🇬🇧</span>
                        <span class="lang-name" data-i18n="lang_en">English</span>
                        <span class="lang-native">English</span>
                    </button>
                    <button class="lang-btn" data-lang="ar" onclick="selectLang('ar')" id="lang-ar">
                        <span class="lang-flag">🇸🇦</span>
                        <span class="lang-name" data-i18n="lang_ar">العربية</span>
                        <span class="lang-native">العربية</span>
                    </button>
                    <button class="lang-btn" data-lang="zh" onclick="selectLang('zh')" id="lang-zh">
                        <span class="lang-flag">🇨🇳</span>
                        <span class="lang-name" data-i18n="lang_zh">中文</span>
                        <span class="lang-native">中文</span>
                    </button>
                </div>
            </div>

            <!-- ── BOUTON SAUVEGARDER ─────────────────────────── -->
            <div style="display:flex; align-items:center; gap:16px; margin-top:4px;">
                <button class="btn-save-settings" onclick="saveSettings()">
                    <span data-i18n="save_btn">💾 Sauvegarder</span>
                </button>
                <span class="save-feedback" id="saveFeedback" data-i18n="saved_msg">
                    ✅ Paramètres sauvegardés !
                </span>
            </div>

        </div>
    </div>
</div>

<script>
// ════════════════════════════════════════════════════════════════
// STATE — lu depuis localStorage via SmartFoodSettings
// ════════════════════════════════════════════════════════════════
let currentLang  = SmartFoodSettings.getCurrentLang();
let currentTheme = SmartFoodSettings.getCurrentTheme();

// ════════════════════════════════════════════════════════════════
// INIT — synchronise l'interface avec l'état réel
// ════════════════════════════════════════════════════════════════
window.addEventListener('DOMContentLoaded', () => {

    // ── Thème ──
    const toggle = document.getElementById('darkModeToggle');
    toggle.checked = (currentTheme === 'dark');   // ← correction : reflète l'état réel
    updateThemeUI(currentTheme);

    toggle.addEventListener('change', () => {
        currentTheme = toggle.checked ? 'dark' : 'light';
        SmartFoodSettings.applyTheme(currentTheme);
        updateThemeUI(currentTheme);
    });

    // ── Langue ──
    updateLangButtons(currentLang);
    // La langue est déjà appliquée par settings.js au chargement,
    // on met juste à jour les boutons actifs.
});

// ════════════════════════════════════════════════════════════════
// UI THÈME
// ════════════════════════════════════════════════════════════════
function updateThemeUI(theme) {
    const t = SmartFoodSettings.TRANSLATIONS[currentLang] || SmartFoodSettings.TRANSLATIONS.fr;

    const icon     = document.getElementById('themeIcon');
    const emoji    = document.getElementById('previewEmoji');
    const preview  = document.getElementById('previewText');
    const label    = document.getElementById('darkModeLabel');

    const previewTexts = {
        dark: {
            fr: 'Mode nuit actif — interface sombre',
            en: 'Dark mode active — dark interface',
            ar: 'الوضع الليلي نشط — واجهة داكنة',
            zh: '夜间模式已启用 — 深色界面'
        },
        light: {
            fr: 'Mode clair actif — interface lumineuse',
            en: 'Light mode active — bright interface',
            ar: 'الوضع الفاتح نشط — واجهة مضيئة',
            zh: '浅色模式已启用 — 明亮界面'
        }
    };

    if (theme === 'dark') {
        icon.textContent    = '🌙';
        emoji.textContent   = '🌙';
        preview.textContent = (previewTexts.dark[currentLang] || previewTexts.dark.fr);
        label.textContent   = t.dark_mode_on;
    } else {
        icon.textContent    = '☀️';
        emoji.textContent   = '☀️';
        preview.textContent = (previewTexts.light[currentLang] || previewTexts.light.fr);
        label.textContent   = t.dark_mode_off;
    }
}

// ════════════════════════════════════════════════════════════════
// UI LANGUE
// ════════════════════════════════════════════════════════════════
function selectLang(lang) {
    currentLang = lang;
    SmartFoodSettings.applyLanguage(lang);
    updateLangButtons(lang);
    updateThemeUI(currentTheme);   // re-render le texte de preview dans la bonne langue
}

function updateLangButtons(lang) {
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('lang-btn--active', btn.dataset.lang === lang);
    });
}

// ════════════════════════════════════════════════════════════════
// SAUVEGARDE
// ════════════════════════════════════════════════════════════════
function saveSettings() {
    SmartFoodSettings.applyTheme(currentTheme);
    SmartFoodSettings.applyLanguage(currentLang);

    const fb = document.getElementById('saveFeedback');
    const t  = SmartFoodSettings.TRANSLATIONS[currentLang] || SmartFoodSettings.TRANSLATIONS.fr;
    fb.textContent = t.saved_msg || '✅ Paramètres sauvegardés !';
    fb.classList.add('show');
    setTimeout(() => fb.classList.remove('show'), 3000);
}
</script>
</body>
</html>