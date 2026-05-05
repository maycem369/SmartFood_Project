<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Enregistrer Face ID – SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/settings.js"></script>
    <style>
        .faceid-container { max-width: 680px; padding: 0; }
        .faceid-card {
            background: var(--white);
            border: 1px solid rgba(45,106,79,0.08);
            border-radius: var(--border-radius-xl);
            padding: 36px;
            box-shadow: 0 4px 24px rgba(0,0,0,.04);
            text-align: center;
        }
        .faceid-icon {
            width: 72px; height: 72px;
            background: var(--front-accent-glow);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem; color: var(--front-accent);
        }
        .camera-wrapper {
            position: relative;
            width: 300px; height: 300px;
            margin: 24px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid var(--front-accent);
            box-shadow: 0 0 0 8px var(--front-accent-glow), 0 8px 32px rgba(45,106,79,.2);
            background: #000;
        }
        #faceVideo  { width:100%; height:100%; object-fit:cover; transform:scaleX(-1); }
        #faceCanvas { position:absolute; top:0; left:0; width:100%; height:100%; transform:scaleX(-1); }
        .scan-ring {
            position: absolute; inset: -4px; border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: var(--front-orange);
            animation: spin 1.5s linear infinite;
            pointer-events: none; display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .progress-dots { display:flex; justify-content:center; gap:10px; margin:16px 0; }
        .dot {
            width:14px; height:14px; border-radius:50%;
            background: var(--border-color);
            transition: background .3s, transform .3s;
        }
        .dot.captured { background: var(--front-accent); transform: scale(1.2); }

        .status-msg {
            font-size:.92rem; color:var(--text-muted);
            min-height:24px; margin:8px 0 20px; transition:color .3s;
        }
        .status-msg.ok   { color: var(--front-accent); }
        .status-msg.warn { color: var(--front-orange); }
        .status-msg.err  { color: #e74c3c; }

        .badge-active {
            display:inline-flex; align-items:center; gap:6px;
            background: var(--front-accent-glow); color: var(--front-accent);
            padding:5px 16px; border-radius:20px; font-size:.82rem; font-weight:700; margin-bottom:20px;
        }
        .badge-none {
            display:inline-flex; align-items:center; gap:6px;
            background:#FFF3E0; color:var(--front-orange);
            padding:5px 16px; border-radius:20px; font-size:.82rem; font-weight:700; margin-bottom:20px;
        }
        .btn-faceid {
            background: var(--front-accent); color:#fff; border:none;
            padding:13px 32px; border-radius:40px; font-size:.95rem; font-weight:700;
            cursor:pointer; box-shadow:0 4px 14px var(--front-accent-glow);
            transition:background .2s, transform .2s;
            display:inline-flex; align-items:center; gap:8px;
        }
        .btn-faceid:hover { background:var(--front-accent-dark); transform:translateY(-2px); }
        .btn-faceid:disabled { background:#ccc; box-shadow:none; cursor:not-allowed; transform:none; }
        .btn-delete-face {
            background:rgba(231,76,60,.1); color:#e74c3c;
            border:1px solid rgba(231,76,60,.2);
            padding:8px 20px; border-radius:40px; font-size:.85rem; font-weight:600;
            cursor:pointer; transition:background .2s;
            display:inline-flex; align-items:center; gap:6px; margin-top:12px;
        }
        .btn-delete-face:hover { background:rgba(231,76,60,.18); }
        .tips {
            background:var(--front-accent-glow); border-radius:var(--radius-md);
            padding:14px 18px; text-align:left; margin:20px 0 0;
        }
        .tips h4 { font-size:.82rem; color:var(--front-accent); font-weight:700; margin-bottom:6px; }
        .tips ul  { list-style:none; padding:0; }
        .tips ul li { font-size:.8rem; color:var(--front-accent-dark); padding:2px 0; display:flex; align-items:center; gap:6px; }
        .tips ul li::before { content:"✓"; font-weight:700; }

        /* Loader modèles */
        .model-loader {
            background:rgba(8,28,21,.06); border-radius:var(--radius-md);
            padding:12px 18px; margin-bottom:18px; font-size:.82rem;
            color:var(--text-muted); display:flex; align-items:center; gap:10px;
        }
        .model-loader .spinner {
            width:16px; height:16px; border-radius:50%;
            border:2px solid var(--border-color);
            border-top-color:var(--front-accent);
            animation:spin .8s linear infinite; flex-shrink:0;
        }
    </style>
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
                <li><a href="index.php?action=edit_profile"     data-i18n="sidebar_edit">✏️ Modifier Profil</a></li>
                <li><a href="index.php?action=face_register" class="active" data-i18n="sidebar_faceid">🪪 Face ID</a></li>
                <li><a href="index.php?action=change_password"  data-i18n="sidebar_password">🔑 Mot de passe</a></li>
                <li><a href="index.php?action=settings"         data-i18n="sidebar_appearance">🌐 Apparence & Langue</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="param-content">
        <div class="faceid-container">
            <div class="faceid-card">

                <div class="faceid-icon">🪪</div>
                <h2 style="font-family:'Poppins',sans-serif;margin-bottom:6px;">Reconnaissance faciale</h2>
                <p style="color:var(--text-muted);font-size:.9rem;margin-bottom:16px;">
                    Enregistrez votre visage pour vous connecter en un clin d'œil.
                </p>

                <?php if ($hasFaceId): ?>
                    <div class="badge-active">✓ Face ID actif</div>
                <?php else: ?>
                    <div class="badge-none">⚠ Aucun Face ID enregistré</div>
                <?php endif; ?>

                <!-- Statut chargement modèles -->
                <div class="model-loader" id="modelLoader">
                    <div class="spinner"></div>
                    <span id="modelStatus">Chargement des modèles IA en local...</span>
                </div>

                <!-- Webcam -->
                <div class="camera-wrapper" id="cameraWrapper" style="display:none;">
                    <video id="faceVideo" autoplay muted playsinline></video>
                    <canvas id="faceCanvas"></canvas>
                    <div class="scan-ring" id="scanRing"></div>
                </div>

                <!-- Progression -->
                <div class="progress-dots" id="progressDots" style="display:none;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="dot" id="dot<?= $i ?>"></div>
                    <?php endfor; ?>
                </div>

                <div class="status-msg" id="statusMsg">Chargement des modèles...</div>

                <!-- Boutons -->
                <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                    <button class="btn-faceid" id="startBtn" disabled>
                        📷 Démarrer l'enregistrement
                    </button>
                    <button class="btn-faceid" id="captureBtn"
                            style="display:none;background:var(--front-orange);" disabled>
                        🔍 Capturer le visage
                    </button>
                    <?php if ($hasFaceId): ?>
                    <button class="btn-delete-face" id="deleteBtn">
                        🗑 Supprimer le Face ID
                    </button>
                    <?php endif; ?>
                    <a href="index.php?action=profil"
                       style="color:var(--text-muted);font-size:.85rem;margin-top:4px;">
                        ← Retour au profil
                    </a>
                </div>

                <div class="tips">
                    <h4>Conseils pour une bonne reconnaissance</h4>
                    <ul>
                        <li>Bonne luminosité, visage bien éclairé</li>
                        <li>Regardez directement la caméra</li>
                        <li>Pas de lunettes de soleil, chapeau ou masque</li>
                        <li>5 captures sous des angles légèrement différents</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ── face-api.js depuis CDN (bibliothèque JS uniquement) ── -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
/* Chemin local vers les modèles dans assets/models/
   Structure attendue :
   assets/models/tiny_face_detector_model-weights_manifest.json
   assets/models/tiny_face_detector_model-shard1
   assets/models/face_landmark_68_model-weights_manifest.json
   assets/models/face_landmark_68_model-shard1
   assets/models/face_recognition_model-weights_manifest.json
   assets/models/face_recognition_model-shard1
*/
const MODELS_PATH  = 'assets/models';
const CAPTURES_MAX = 5;

let stream       = null;
let captureCount = 0;
let descriptors  = [];
let detecting    = false;
let modelsReady  = false;

const video      = document.getElementById('faceVideo');
const canvas     = document.getElementById('faceCanvas');
const startBtn   = document.getElementById('startBtn');
const captureBtn = document.getElementById('captureBtn');
const scanRing   = document.getElementById('scanRing');
const statusEl   = document.getElementById('statusMsg');
const cameraWrap = document.getElementById('cameraWrapper');
const dotsWrap   = document.getElementById('progressDots');
const modelLoader= document.getElementById('modelLoader');
const modelStatus= document.getElementById('modelStatus');

function setStatus(msg, type = '') {
    statusEl.textContent = msg;
    statusEl.className   = 'status-msg ' + type;
}

function markDot(i) {
    const d = document.getElementById('dot' + i);
    if (d) d.classList.add('captured');
}

// ── Chargement modèles depuis assets/models/ ──────────────
async function loadModels() {
    try {
        modelStatus.textContent = 'Chargement TinyFaceDetector...';
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODELS_PATH);

        modelStatus.textContent = 'Chargement FaceLandmark68...';
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODELS_PATH);

        modelStatus.textContent = 'Chargement FaceRecognition...';
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODELS_PATH);

        modelsReady = true;
        modelLoader.style.display = 'none';
        startBtn.disabled = false;
        setStatus('Modèles prêts. Cliquez sur "Démarrer".', 'ok');
    } catch (err) {
        modelStatus.textContent = 'Erreur chargement modèles : ' + err.message;
        modelLoader.style.background = 'rgba(231,76,60,.08)';
        setStatus('Impossible de charger les modèles IA.', 'err');
        console.error('[FaceID] Erreur modèles :', err);
    }
}

// Chargement automatique au chargement de la page
window.addEventListener('load', loadModels);

// ── Démarrage caméra ──────────────────────────────────────
startBtn.addEventListener('click', async () => {
    if (!modelsReady) { setStatus('Modèles pas encore prêts.', 'warn'); return; }
    startBtn.disabled = true;
    setStatus('Activation de la caméra...', 'warn');
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { width: 300, height: 300, facingMode: 'user' }
        });
        video.srcObject = stream;
        await video.play();

        cameraWrap.style.display = 'block';
        dotsWrap.style.display   = 'flex';
        scanRing.style.display   = 'block';
        captureBtn.style.display = 'inline-flex';
        setStatus('Positionnez votre visage dans le cercle.', 'warn');
        startDetectionLoop();
    } catch (err) {
        setStatus('Caméra inaccessible : ' . err.message, 'err');
        startBtn.disabled = false;
    }
});

// ── Boucle de détection temps réel ───────────────────────
async function startDetectionLoop() {
    if (detecting) return;
    detecting = true;
    const size = { width: video.videoWidth || 300, height: video.videoHeight || 300 };
    faceapi.matchDimensions(canvas, size);

    const loop = async () => {
        if (!detecting) return;
        const det = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        if (det) {
            faceapi.draw.drawDetections(canvas, faceapi.resizeResults(det, size));
            faceapi.draw.drawFaceLandmarks(canvas, faceapi.resizeResults(det, size));
            captureBtn.disabled = false;
            setStatus('Visage détecté ! Cliquez sur "Capturer".', 'ok');
        } else {
            captureBtn.disabled = true;
            setStatus('Aucun visage détecté — ajustez votre position.', 'warn');
        }
        requestAnimationFrame(loop);
    };
    loop();
}

// ── Capture d'un descripteur ──────────────────────────────
captureBtn.addEventListener('click', async () => {
    if (captureCount >= CAPTURES_MAX) return;
    captureBtn.disabled = true;
    setStatus('Analyse du visage...', 'warn');

    const det = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (!det) {
        setStatus('Visage non détecté. Réessayez.', 'err');
        captureBtn.disabled = false;
        return;
    }

    descriptors.push(Array.from(det.descriptor));
    markDot(captureCount);
    captureCount++;

    if (captureCount < CAPTURES_MAX) {
        setStatus(`Capture ${captureCount}/${CAPTURES_MAX} — bougez légèrement la tête.`, 'ok');
        captureBtn.disabled = false;
    } else {
        setStatus('5 captures effectuées. Enregistrement en cours...', 'ok');
        captureBtn.style.display = 'none';
        detecting = false;
        await saveDescriptors();
    }
});

// ── Calcul descripteur moyen + envoi POST ─────────────────
async function saveDescriptors() {
    const averaged = new Array(128).fill(0);
    descriptors.forEach(d => d.forEach((v, i) => { averaged[i] += v; }));
    const finalDescriptor = averaged.map(v => v / descriptors.length);

    try {
        const resp = await fetch('index.php?action=face_register_save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ descriptor: finalDescriptor })
        });
        const data = await resp.json();
        if (data.success) {
            setStatus('✓ Face ID enregistré avec succès !', 'ok');
            stopCamera();
            setTimeout(() => location.href = 'index.php?action=profil', 1800);
        } else {
            setStatus('Erreur : ' . data.message, 'err');
        }
    } catch (e) {
        setStatus('Erreur réseau : ' . e.message, 'err');
    }
}

// ── Suppression ───────────────────────────────────────────
const deleteBtn = document.getElementById('deleteBtn');
if (deleteBtn) {
    deleteBtn.addEventListener('click', async () => {
        if (!confirm('Supprimer votre Face ID ?')) return;
        const resp = await fetch('index.php?action=face_delete', { method: 'POST' });
        const data = await resp.json();
        if (data.success) location.reload();
        else alert('Erreur : ' . data.message);
    });
}

function stopCamera() {
    detecting = false;
    if (stream) stream.getTracks().forEach(t => t.stop());
    scanRing.style.display = 'none';
}
</script>
</body>
</html>
