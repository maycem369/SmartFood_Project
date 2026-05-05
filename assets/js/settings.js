/**
 * SmartFood — Settings Manager
 * Gère le mode nuit et la langue (FR / EN / AR / ZH)
 * Appliqué sur toutes les pages via localStorage
 */

// ═══════════════════════════════════════════════
// DICTIONNAIRE DE TRADUCTIONS
// ═══════════════════════════════════════════════
const TRANSLATIONS = {
  fr: {
    // Navbar
    nav_dashboard:   "🏠 Tableau de bord",
    nav_profile:     "👤 Mon Profil",
    nav_recipes:     "📋 Recettes",
    nav_nutrition:   "🥗 Nutrition",
    nav_settings:    "⚙️ Paramètres",
    nav_logout:      "🚪 Déconnexion",
    nav_hello:       "Bonjour",
    // Sidebar paramètres
    sidebar_title:   "Paramètres",
    sidebar_profile: "👤 Mon Profil",
    sidebar_edit:    "✏️ Modifier Profil",
    sidebar_faceid:  "🪪 Face ID",
    sidebar_password:"🔑 Mot de passe",
    sidebar_appearance:"🌐 Apparence & Langue",
    sidebar_language:"🌐 Langue",
    // Page settings
    settings_title:       "Apparence & Langue",
    settings_sub:         "Personnalisez votre expérience SmartFood",
    dark_mode_title:      "Mode Nuit",
    dark_mode_desc:       "Réduisez la fatigue oculaire avec un thème sombre",
    dark_mode_on:         "Activé",
    dark_mode_off:        "Désactivé",
    lang_title:           "Langue de l'interface",
    lang_desc:            "Choisissez la langue d'affichage",
    lang_fr:              "Français",
    lang_en:              "English",
    lang_ar:              "العربية",
    lang_zh:              "中文",
    save_btn:             "💾 Sauvegarder",
    saved_msg:            "✅ Paramètres sauvegardés !",
  },
  en: {
    nav_dashboard:   "🏠 Dashboard",
    nav_profile:     "👤 My Profile",
    nav_recipes:     "📋 Recipes",
    nav_nutrition:   "🥗 Nutrition",
    nav_settings:    "⚙️ Settings",
    nav_logout:      "🚪 Logout",
    nav_hello:       "Hello",
    sidebar_title:   "Settings",
    sidebar_profile: "👤 My Profile",
    sidebar_edit:    "✏️ Edit Profile",
    sidebar_faceid:  "🪪 Face ID",
    sidebar_password:"🔑 Password",
    sidebar_appearance:"🌐 Appearance & Language",
    sidebar_language:"🌐 Language",
    settings_title:       "Appearance & Language",
    settings_sub:         "Customize your SmartFood experience",
    dark_mode_title:      "Dark Mode",
    dark_mode_desc:       "Reduce eye strain with a dark theme",
    dark_mode_on:         "Enabled",
    dark_mode_off:        "Disabled",
    lang_title:           "Interface Language",
    lang_desc:            "Choose your display language",
    lang_fr:              "French",
    lang_en:              "English",
    lang_ar:              "Arabic",
    lang_zh:              "Chinese",
    save_btn:             "💾 Save Settings",
    saved_msg:            "✅ Settings saved!",
  },
  ar: {
    nav_dashboard:   "🏠 لوحة التحكم",
    nav_profile:     "👤 ملفي الشخصي",
    nav_recipes:     "📋 الوصفات",
    nav_nutrition:   "🥗 التغذية",
    nav_settings:    "⚙️ الإعدادات",
    nav_logout:      "🚪 تسجيل الخروج",
    nav_hello:       "مرحباً",
    sidebar_title:   "الإعدادات",
    sidebar_profile: "👤 ملفي الشخصي",
    sidebar_edit:    "✏️ تعديل الملف",
    sidebar_faceid:  "🪪 التعرف على الوجه",
    sidebar_password:"🔑 كلمة المرور",
    sidebar_appearance:"🌐 المظهر واللغة",
    sidebar_language:"🌐 اللغة",
    settings_title:       "المظهر واللغة",
    settings_sub:         "خصص تجربة SmartFood الخاصة بك",
    dark_mode_title:      "الوضع الليلي",
    dark_mode_desc:       "قلل إجهاد العين مع النمط الداكن",
    dark_mode_on:         "مفعّل",
    dark_mode_off:        "معطّل",
    lang_title:           "لغة الواجهة",
    lang_desc:            "اختر لغة العرض",
    lang_fr:              "الفرنسية",
    lang_en:              "الإنجليزية",
    lang_ar:              "العربية",
    lang_zh:              "الصينية",
    save_btn:             "💾 حفظ الإعدادات",
    saved_msg:            "✅ تم حفظ الإعدادات!",
  },
  zh: {
    nav_dashboard:   "🏠 控制台",
    nav_profile:     "👤 我的主页",
    nav_recipes:     "📋 食谱",
    nav_nutrition:   "🥗 营养",
    nav_settings:    "⚙️ 设置",
    nav_logout:      "🚪 退出登录",
    nav_hello:       "你好",
    sidebar_title:   "设置",
    sidebar_profile: "👤 我的主页",
    sidebar_edit:    "✏️ 编辑资料",
    sidebar_faceid:  "🪪 人脸识别",
    sidebar_password:"🔑 密码",
    sidebar_appearance:"🌐 外观与语言",
    sidebar_language:"🌐 语言",
    settings_title:       "外观与语言",
    settings_sub:         "自定义您的 SmartFood 体验",
    dark_mode_title:      "夜间模式",
    dark_mode_desc:       "使用深色主题减少眼睛疲劳",
    dark_mode_on:         "已启用",
    dark_mode_off:        "已禁用",
    lang_title:           "界面语言",
    lang_desc:            "选择显示语言",
    lang_fr:              "法语",
    lang_en:              "英语",
    lang_ar:              "阿拉伯语",
    lang_zh:              "中文",
    save_btn:             "💾 保存设置",
    saved_msg:            "✅ 设置已保存！",
  }
};

// ═══════════════════════════════════════════════
// INITIALISATION AU CHARGEMENT
// ═══════════════════════════════════════════════
(function init() {
  applyTheme(localStorage.getItem('sf_theme') || 'light');
  applyLanguage(localStorage.getItem('sf_lang') || 'fr');
})();

// ═══════════════════════════════════════════════
// THÈME (DARK / LIGHT)
// ═══════════════════════════════════════════════
function applyTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('sf_theme', theme);
  // Met à jour le toggle si présent sur la page
  const toggle = document.getElementById('darkModeToggle');
  if (toggle) toggle.checked = (theme === 'dark');
  const label = document.getElementById('darkModeLabel');
  if (label) {
    const lang = localStorage.getItem('sf_lang') || 'fr';
    const t = TRANSLATIONS[lang] || TRANSLATIONS.fr;
    label.textContent = (theme === 'dark') ? t.dark_mode_on : t.dark_mode_off;
  }
}

function toggleTheme() {
  const current = localStorage.getItem('sf_theme') || 'light';
  applyTheme(current === 'dark' ? 'light' : 'dark');
}

// ═══════════════════════════════════════════════
// LANGUE
// ═══════════════════════════════════════════════
function applyLanguage(lang) {
  if (!TRANSLATIONS[lang]) lang = 'fr';
  localStorage.setItem('sf_lang', lang);
  const t = TRANSLATIONS[lang];

  // Direction RTL pour l'arabe
  document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', lang);

  // Traduit tous les éléments qui ont data-i18n
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) el.textContent = t[key];
  });

  // Met à jour les boutons de langue actif
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('lang-btn--active', btn.dataset.lang === lang);
  });

  // Met à jour le label du dark mode
  const toggle = document.getElementById('darkModeToggle');
  const label  = document.getElementById('darkModeLabel');
  if (label && toggle) {
    label.textContent = toggle.checked ? t.dark_mode_on : t.dark_mode_off;
  }
}

// ═══════════════════════════════════════════════
// EXPOSITION GLOBALE
// ═══════════════════════════════════════════════
window.SmartFoodSettings = { applyTheme, toggleTheme, applyLanguage, TRANSLATIONS };
