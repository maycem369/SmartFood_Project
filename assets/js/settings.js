/**
 * SmartFood — Settings Manager (Global Version)
 */
const TRANSLATIONS = {
    fr: {
        admin_nav_dashboard: "🏠 Vue d'ensemble",
        admin_nav_users: "👤 Utilisateurs",
        admin_nav_recipes: "📋 Recettes",
        admin_nav_ingredients: "🥗 Ingrédients",
        admin_nav_config: "⚙️ Configuration",
        admin_nav_home: "🚪 Déconnexion",
        nav_logout: "🚪 Déconnexion",
        nav_home: "🏠 Accueil",
        nav_dashboard: "🏠 Tableau de bord",
        nav_recipes: "📋 Recettes",
        nav_nutrition: "🥗 Nutrition",
        nav_profile: "👤 Profil",
        nav_settings: "⚙️ Paramètres",
        nav_login: "🔑 Connexion",
        nav_register: "📝 Inscription",
        nav_hello: "Bonjour",
        sidebar_appearance: "🌐 Apparence & Langue",
        dark_mode_title: "Mode Nuit",
        dark_mode_desc: "Thème sombre pour l'interface",
        lang_title: "Langue de l'interface",
        sidebar_title: "Configuration",
        sidebar_faceid: "🪪 Face ID",
        sidebar_password: "🔑 Mot de passe",
        pwd_title: "Changer le mot de passe",
        pwd_current: "Mot de passe actuel",
        pwd_new: "Nouveau mot de passe",
        pwd_confirm: "Confirmer le mot de passe",
        pwd_change_btn: "Changer le mot de passe",
        btn_save: "Enregistrer",
        btn_cancel: "Annuler",
        btn_edit: "Modifier",
        btn_delete: "Supprimer"
    },
    en: {
        admin_nav_dashboard: "🏠 Overview",
        admin_nav_users: "👤 Users",
        admin_nav_recipes: "📋 Recipes",
        admin_nav_ingredients: "🥗 Ingredients",
        admin_nav_config: "⚙️ Configuration",
        admin_nav_home: "🚪 Logout",
        nav_logout: "🚪 Logout",
        nav_home: "🏠 Home",
        nav_dashboard: "🏠 Dashboard",
        nav_recipes: "📋 Recipes",
        nav_nutrition: "🥗 Nutrition",
        nav_profile: "👤 Profile",
        nav_settings: "⚙️ Settings",
        nav_login: "🔑 Login",
        nav_register: "📝 Register",
        nav_hello: "Hello",
        sidebar_appearance: "🌐 Appearance & Language",
        dark_mode_title: "Dark Mode",
        dark_mode_desc: "Dark theme for the interface",
        lang_title: "Interface Language",
        sidebar_title: "Settings",
        sidebar_faceid: "🪪 Face ID",
        sidebar_password: "🔑 Password",
        pwd_title: "Change Password",
        pwd_current: "Current Password",
        pwd_new: "New Password",
        pwd_confirm: "Confirm Password",
        pwd_change_btn: "Change Password",
        btn_save: "Save",
        btn_cancel: "Cancel",
        btn_edit: "Edit",
        btn_delete: "Delete"
    },
    ar: {
        admin_nav_dashboard: "🏠 نظرة عامة",
        admin_nav_users: "👤 المستخدمين",
        admin_nav_recipes: "📋 الوصفات",
        admin_nav_ingredients: "🥗 المكونات",
        admin_nav_config: "⚙️ الإعدادات",
        admin_nav_home: "🚪 تسجيل الخروج",
        nav_logout: "🚪 تسجيل الخروج",
        nav_home: "🏠 الرئيسية",
        nav_dashboard: "🏠 لوحة القيادة",
        nav_recipes: "📋 وصفات",
        nav_nutrition: "🥗 تغذية",
        nav_profile: "👤 الملف الشخصي",
        nav_settings: "⚙️ الإعدادات",
        nav_login: "🔑 تسجيل الدخول",
        nav_register: "📝 إنشاء حساب",
        nav_hello: "مرحباً",
        sidebar_appearance: "🌐 المظهر واللغة",
        dark_mode_title: "الوضع الليلي",
        dark_mode_desc: "المظهر الداكن للواجهة",
        lang_title: "لغة الواجهة",
        sidebar_title: "الإعدادات",
        sidebar_faceid: "🪪 بصمة الوجه",
        sidebar_password: "🔑 كلمة المرور",
        pwd_title: "تغيير كلمة المرور",
        pwd_current: "كلمة المرور الحالية",
        pwd_new: "كلمة المرور الجديدة",
        pwd_confirm: "تأكيد كلمة المرور",
        pwd_change_btn: "تغيير كلمة المرور",
        btn_save: "حفظ",
        btn_cancel: "إلغاء",
        btn_edit: "تعديل",
        btn_delete: "حذف"
    },
    zh: {
        admin_nav_dashboard: "🏠 概览",
        admin_nav_users: "👤 用户",
        admin_nav_recipes: "📋 食谱",
        admin_nav_ingredients: "🥗 配料",
        admin_nav_config: "⚙️ 配置",
        admin_nav_home: "🚪 登出",
        nav_logout: "🚪 登出",
        nav_home: "🏠 首页",
        nav_dashboard: "🏠 仪表板",
        nav_recipes: "📋 食谱",
        nav_nutrition: "🥗 营养",
        nav_profile: "👤 个人资料",
        nav_settings: "⚙️ 设置",
        nav_login: "🔑 登录",
        nav_register: "📝 注册",
        nav_hello: "您好",
        sidebar_appearance: "🌐 外观与语言",
        dark_mode_title: "夜间模式",
        dark_mode_desc: "界面的深色主题",
        lang_title: "界面语言",
        sidebar_title: "配置",
        sidebar_faceid: "🪪 面部识别",
        sidebar_password: "🔑 密码",
        pwd_title: "修改密码",
        pwd_current: "当前密码",
        pwd_new: "新密码",
        pwd_confirm: "确认密码",
        pwd_change_btn: "修改密码",
        btn_save: "保存",
        btn_cancel: "取消",
        btn_edit: "编辑",
        btn_delete: "删除"
    }
};

window.SmartFoodSettings = {
    applyTheme(theme) {
        try {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('sf_theme', theme);
        } catch(e) { console.error(e); }
    },
    applyLanguage(lang) {
        try {
            localStorage.setItem('sf_lang', lang);
            document.documentElement.lang = lang;
            document.documentElement.dir = (lang === 'ar') ? 'rtl' : 'ltr';
            const dict = TRANSLATIONS[lang] || TRANSLATIONS.fr;
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key]) {
                    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                        el.placeholder = dict[key];
                    } else {
                        el.textContent = dict[key];
                    }
                }
            });
        } catch(e) { console.error(e); }
    },
    init() {
        this.applyTheme(localStorage.getItem('sf_theme') || 'light');
        this.applyLanguage(localStorage.getItem('sf_lang') || 'fr');
    }
};
SmartFoodSettings.init();
document.addEventListener('DOMContentLoaded', () => SmartFoodSettings.init());
