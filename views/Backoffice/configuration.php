﻿<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration Admin – SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        body { background-color: #f0f4f3; }
        .main-content { margin-left: var(--sidebar-w); padding: 30px; }
        .sidebar { width: var(--sidebar-w); }
        .config-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .config-card { background: white; padding: 25px; border-radius: 20px; box-shadow: var(--shadow-md); }
        .config-card h3 { margin-bottom: 20px; color: var(--front-accent); display: flex; align-items: center; gap: 10px; }
        .setting-row { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--bg-light); }
        .setting-row:last-child { border-bottom: none; }
        .lang-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
        .btn-lang { padding: 10px; border: 1px solid var(--border-color); border-radius: 12px; background: white; cursor: pointer; transition: var(--t); text-align: center; }
        .btn-lang:hover, .btn-lang.active { background: var(--front-accent); color: white; border-color: var(--front-accent); }

        /* MODAL STYLE */
        .sf-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            align-items: center; justify-content: center;
            backdrop-filter: blur(5px);
        }
        .sf-modal-content {
            background: white;
            padding: 30px;
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .sf-modal-close {
            position: absolute;
            right: 20px; top: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }
        .face-capture-zone {
            width: 300px; height: 300px;
            margin: 20px auto;
            border-radius: 50%;
            border: 5px solid var(--front-accent);
            overflow: hidden;
            position: relative;
            background: #111;
        }
        #faceVideo { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        #faceCanvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: scaleX(-1); }
        
        .form-modal-group { text-align: left; margin-bottom: 15px; }
        .form-modal-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
        .form-modal-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; outline: none; }
        
        /* BUTTON FIXES */
        .btn { padding: 10px 20px; border-radius: 12px; border: none; cursor: pointer; font-weight: 600; transition: 0.3s; color: white !important; text-decoration: none; display: inline-block; }
        .btn-orange { background: #FF8C00 !important; }
        .btn-green { background: #2D6A4F !important; }
        .btn-sm { padding: 8px 15px; font-size: 0.85rem; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>

<!-- Sidebar Pro -->
<div class="sidebar admin-sidebar">
    <div class="logo">
        <h1 style="color:white;">Smart<span>Food</span> </h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">
            <i class="fas fa-chart-line"></i> <span data-i18n="admin_nav_dashboard">Vue d'ensemble</span>
        </a></li>
        <li><a href="index.php?action=users_list">
            <i class="fas fa-user-shield"></i> <span data-i18n="admin_nav_users">Utilisateurs</span>
        </a></li>
        <li><a href="index.php?action=recettes_admin">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes & Menus</span>
        </a></li>
        <li><a href="index.php?action=nutrition_admin">
            <i class="fas fa-apple-alt"></i> <span data-i18n="admin_nav_nutrition">Nutrition</span>
        </a></li>
        <li><a href="index.php?action=admin_configuration" class="active">
            <i class="fas fa-cog"></i> <span data-i18n="admin_nav_config">Configuration</span>
        </a></li>
    </ul>
    <div class="switch-mode" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <a href="index.php?action=logout" class="admin-link" style="color: var(--text-sidebar);">
            <i class="fas fa-sign-out-alt"></i> <span data-i18n="nav_logout">Déconnexion</span>
        </a>
    </div>
</div>

<div class="main-content">
    <h1 class="page-title" data-i18n="admin_nav_config">Paramètres Système</h1>

    <div class="config-grid">
        <div class="config-card">
            <h3 data-i18n="sidebar_appearance"><i class="fas fa-palette"></i> Apparence & Langue</h3>
            <div class="setting-row">
                <div>
                    <div style="font-weight:600;" data-i18n="dark_mode_title">Mode Nuit</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);" data-i18n="dark_mode_desc">Thème sombre pour l'interface</div>
                </div>
                <div class="toggle-switch" onclick="toggleDarkMode()" style="cursor:pointer; width:50px; height:25px; background:#ddd; border-radius:25px; position:relative; transition:.3s;" id="themeToggle">
                    <div style="width:21px; height:21px; background:white; border-radius:50%; position:absolute; top:2px; left:2px; transition:.3s;" id="themeCircle"></div>
                </div>
            </div>
            <div style="margin-top:20px;">
                <div style="font-weight:600;" data-i18n="lang_title">Langue de l'administration</div>
                <div class="lang-grid">
                    <div class="btn-lang" id="lang-fr" onclick="changeLang('fr')">Français</div>
                    <div class="btn-lang" id="lang-en" onclick="changeLang('en')">English</div>
                    <div class="btn-lang" id="lang-ar" onclick="changeLang('ar')">العربية</div>
                    <div class="btn-lang" id="lang-zh" onclick="changeLang('zh')">中文</div>
                </div>
            </div>
        </div>

        <div class="config-card">
            <h3 data-i18n="sidebar_password"><i class="fas fa-user-lock"></i> Sécurité Administrateur</h3>
            <div class="setting-row">
                <div>
                    <div style="font-weight:600;" data-i18n="sidebar_faceid">Authentification Faciale</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);" id="faceIdStatus">
                        <?= $hasFaceId ? '✅ Face ID configuré' : '❌ Non configuré' ?>
                    </div>
                </div>
                <button onclick="openFaceModal()" class="btn btn-orange btn-sm" style="min-width: 100px;">
                    <?= $hasFaceId ? 'Désactiver' : 'Activer' ?>
                </button>
            </div>
            <div class="setting-row">
                <div>
                    <div style="font-weight:600;" data-i18n="pwd_title">Mot de passe</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);" data-i18n="pwd_current">Changer votre clé d'accès</div>
                </div>
                <button onclick="openPwdModal()" class="btn btn-green btn-sm" style="min-width: 100px;">
                    Changer mot de passe
                </button>
            </div>
        </div>
    </div>
</div>

<!-- FACE ID MODAL -->
<div id="faceModal" class="sf-modal">
    <div class="sf-modal-content">
        <span class="sf-modal-close" onclick="closeFaceModal()">&times;</span>
        <h2>Enregistrement Face ID</h2>
        <div class="face-capture-zone">
            <video id="faceVideo" autoplay muted playsinline></video>
            <canvas id="faceCanvas"></canvas>
        </div>
        <div id="scanStatus">Initialisation...</div>
        <div style="margin-top:20px; display:none;" id="modalFaceActions">
            <button class="btn btn-danger btn-sm" onclick="deleteFaceId()">Supprimer Face ID</button>
        </div>
    </div>
</div>

<!-- PASSWORD MODAL -->
<div id="pwdModal" class="sf-modal">
    <div class="sf-modal-content">
        <span class="sf-modal-close" onclick="closePwdModal()">&times;</span>
        <h2 data-i18n="pwd_title">Changer le mot de passe</h2>
        <form id="pwdForm" style="margin-top:20px;">
            <div class="form-modal-group">
                <label data-i18n="pwd_current">Mot de passe actuel</label>
                <input type="password" name="current_password">
            </div>
            <div class="form-modal-group">
                <label data-i18n="pwd_new">Nouveau mot de passe</label>
                <input type="password" name="new_password">
            </div>
            <div class="form-modal-group">
                <label data-i18n="pwd_confirm">Confirmer</label>
                <input type="password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-orange w-100" style="width:100%; padding:12px;" data-i18n="pwd_change_btn">Changer</button>
        </form>
        <div id="pwdStatus" style="margin-top:15px; font-size:0.9rem;"></div>
    </div>
</div>

<script>
    // --- Dark Mode & Language ---
    function updateConfigUI() {
        const theme = localStorage.getItem('sf_theme') || 'light';
        const lang = localStorage.getItem('sf_lang') || 'fr';
        const toggle = document.getElementById('themeToggle');
        const circle = document.getElementById('themeCircle');
        if (theme === 'dark') { toggle.style.background = 'var(--front-accent)'; circle.style.left = '27px'; }
        else { toggle.style.background = '#ddd'; circle.style.left = '2px'; }
        document.querySelectorAll('.btn-lang').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('lang-' + lang);
        if (activeBtn) activeBtn.classList.add('active');
    }
    function toggleDarkMode() { SmartFoodSettings.applyTheme(localStorage.getItem('sf_theme') === 'light' ? 'dark' : 'light'); updateConfigUI(); }
    function changeLang(lang) { 
        SmartFoodSettings.applyLanguage(lang); 
        updateConfigUI(); 
    }
    window.addEventListener('DOMContentLoaded', updateConfigUI);

    // --- Face ID Modal ---
    let stream = null;
    async function openFaceModal() {
        document.getElementById('faceModal').style.display = 'flex';
        if ("<?= $hasFaceId ?>") document.getElementById('modalFaceActions').style.display = 'block';
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri('assets/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('assets/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('assets/models');
            stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            document.getElementById('faceVideo').srcObject = stream;
            startFaceDetection();
        } catch (err) { document.getElementById('scanStatus').textContent = "Erreur caméra."; }
    }
    function closeFaceModal() { 
        document.getElementById('faceModal').style.display = 'none'; 
        if (stream) stream.getTracks().forEach(t => t.stop()); 
    }
    let lastDescriptor = null;

    function startFaceDetection() {
        const video = document.getElementById('faceVideo');
        const canvas = document.getElementById('faceCanvas');
        const status = document.getElementById('scanStatus');
        const btnSave = document.getElementById('btnSaveFace');
        const displaySize = { width: 300, height: 300 };
        faceapi.matchDimensions(canvas, displaySize);
        
        status.textContent = "Positionnez votre visage...";
        btnSave.disabled = true;

        if (detectionInterval) clearInterval(detectionInterval);
        
        detectionInterval = setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
            
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

            if (detections.length === 1) {
                lastDescriptor = detections[0].descriptor;
                status.textContent = "✅ Visage détecté ! Prêt à enregistrer.";
                status.style.color = "green";
                btnSave.disabled = false;
            } else {
                status.textContent = detections.length > 1 ? "Trop de visages." : "Aucun visage détecté.";
                status.style.color = "inherit";
                btnSave.disabled = true;
            }
        }, 500);
    }

    async function manualSaveFace() {
        if (!lastDescriptor) return;
        const status = document.getElementById('scanStatus');
        status.textContent = "Sauvegarde en cours...";
        
        const resp = await fetch('index.php?action=face_register_save', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ descriptor: Array.from(lastDescriptor) })
        });
        const data = await resp.json();
        if (data.success) { 
            status.textContent = "✅ Face ID enregistré !"; 
            setTimeout(() => location.reload(), 1000); 
        } else {
            status.textContent = "❌ Erreur : " + data.message;
        }
    }
    async function deleteFaceId() { 
        const resp = await fetch('index.php?action=face_delete', {method:'POST'}); 
        const data = await resp.json();
        if (data.success) {
            document.getElementById('scanStatus').textContent = "Face ID supprimé.";
            setTimeout(() => location.reload(), 1000);
        }
    }

    // --- Password Modal ---
    function openPwdModal() { document.getElementById('pwdModal').style.display = 'flex'; }
    function closePwdModal() { document.getElementById('pwdModal').style.display = 'none'; }
    document.getElementById('pwdForm').onsubmit = async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const status = document.getElementById('pwdStatus');
        status.textContent = "Traitement...";
        const resp = await fetch('index.php?action=ajax_update_password', { method: 'POST', body: fd });
        const data = await resp.json();
        status.textContent = data.message;
        status.style.color = data.success ? 'green' : 'red';
        if (data.success) setTimeout(() => location.reload(), 1500);
    };
</script>
</body>
</html>