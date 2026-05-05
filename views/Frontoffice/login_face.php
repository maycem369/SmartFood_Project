<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion par visage - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        body { background: #f5f5f5; }
        .face-login-wrap {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 20px;
        }
        .face-login-card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 460px; width: 100%;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            text-align: center;
        }
        .face-login-card h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem; margin-bottom: 6px; color: #1a1a2e;
        }
        .face-login-card p.sub {
            color: #888; font-size: .9rem; margin-bottom: 24px;
        }
        .camera-circle {
            position: relative;
            width: 260px; height: 260px;
            margin: 0 auto 20px;
            border-radius: 50%; overflow: hidden;
            border: 4px solid #2d6a4f;
            box-shadow: 0 0 0 8px rgba(45,106,79,.12), 0 8px 32px rgba(45,106,79,.2);
            background: #111;
        }
        #loginVideo  { width:100%; height:100%; object-fit:cover; transform:scaleX(-1); display:none; }
        #loginCanvas { position:absolute; top:0; left:0; width:100%; height:100%; transform:scaleX(-1); }
        .camera-placeholder {
            display:flex; align-items:center; justify-content:center;
            width:100%; height:100%; font-size:3rem;
        }
        .scan-ring {
            position:absolute; inset:-4px; border-radius:50%;
            border:3px solid transparent;
            border-top-color:#f4a261;
            animation:spin 1.2s linear infinite;
            pointer-events:none; display:none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Progress dots */
        .capture-dots { display:flex; justify-content:center; gap:12px; margin:12px 0; }
        .capture-dot {
            width:12px; height:12px; border-radius:50%;
            background:#ddd; transition:background .3s, transform .3s;
        }
        .capture-dot.done { background:#2d6a4f; transform:scale(1.2); }

        .status-box {
            background:#f8f9fa; border-radius:12px;
            padding:12px 16px; margin:14px 0;
            font-size:.88rem; color:#555; min-height:44px;
            display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .status-box.ok   { background:#e8f5e9; color:#2d6a4f; }
        .status-box.warn { background:#fff3e0; color:#e07b00; }
        .status-box.err  { background:#fde8e8; color:#c0392b; }

        .btn-start {
            background:#2d6a4f; color:#fff; border:none;
            padding:13px 32px; border-radius:40px;
            font-size:.95rem; font-weight:700; cursor:pointer;
            box-shadow:0 4px 14px rgba(45,106,79,.3);
            transition:background .2s, transform .2s;
            display:inline-flex; align-items:center; gap:8px;
        }
        .btn-start:hover { background:#1b4332; transform:translateY(-2px); }
        .btn-start:disabled { background:#ccc; box-shadow:none; cursor:not-allowed; transform:none; }

        .back-link { display:block; margin-top:18px; color:#888; font-size:.85rem; }
        .back-link:hover { color:#2d6a4f; }

        .progress-bar-wrap {
            height:6px; background:#eee; border-radius:4px; margin:10px 0;
            overflow:hidden; display:none;
        }
        .progress-bar-fill {
            height:100%; background:#2d6a4f;
            border-radius:4px; width:0%; transition:width .4s;
        }
    </style>
</head>
<body>
<div class="face-login-wrap">
    <div class="face-login-card">
        <div style="font-size:2.5rem;margin-bottom:10px;">🪪</div>
        <h2>Connexion Face ID</h2>
        <p class="sub">Placez votre visage devant la caméra</p>

        <div class="camera-circle">
            <div class="camera-placeholder" id="camPlaceholder">📷</div>
            <video id="loginVideo" autoplay muted playsinline></video>
            <canvas id="loginCanvas"></canvas>
            <div class="scan-ring" id="scanRing"></div>
        </div>

        <!-- Progression des captures -->
        <div class="capture-dots" id="captureDots" style="display:none;">
            <div class="capture-dot" id="cdot0"></div>
            <div class="capture-dot" id="cdot1"></div>
            <div class="capture-dot" id="cdot2"></div>
        </div>
        <div class="progress-bar-wrap" id="progressWrap">
            <div class="progress-bar-fill" id="progressFill"></div>
        </div>

        <div class="status-box" id="statusBox">⏳ Chargement des modèles IA...</div>

        <button class="btn-start" id="startBtn" disabled>
            📷 Démarrer la reconnaissance
        </button>

        <a class="back-link" href="index.php?action=login">← Retour à la connexion classique</a>
    </div>
</div>

<script>
const MODELS_PATH   = 'assets/models';
const CAPTURES_NEED = 3;   // captures à moyenner

let modelsLoaded = false;
let stream       = null;
let scanning     = false;
let capturesDone = 0;
let descriptors  = [];

const video       = document.getElementById('loginVideo');
const canvas      = document.getElementById('loginCanvas');
const statusBox   = document.getElementById('statusBox');
const startBtn    = document.getElementById('startBtn');
const scanRing    = document.getElementById('scanRing');
const captureDots = document.getElementById('captureDots');
const progressWrap= document.getElementById('progressWrap');
const progressFill= document.getElementById('progressFill');
const placeholder = document.getElementById('camPlaceholder');

function setStatus(msg, type = '') {
    statusBox.textContent = msg;
    statusBox.className   = 'status-box ' + type;
}

function markDot(i) {
    const d = document.getElementById('cdot' + i);
    if (d) d.classList.add('done');
}

function setProgress(pct) {
    progressFill.style.width = pct + '%';
}

// ── Chargement modèles ─────────────────────────────────────
async function loadModels() {
    try {
        setStatus('Chargement modèles (1/3)...');
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODELS_PATH);
        setStatus('Chargement modèles (2/3)...');
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODELS_PATH);
        setStatus('Chargement modèles (3/3)...');
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODELS_PATH);
        modelsLoaded = true;
        setStatus('✅ Modèles prêts ! Cliquez sur Démarrer.', 'ok');
        startBtn.disabled = false;
    } catch (err) {
        setStatus('❌ Erreur modèles : ' + err.message, 'err');
        console.error(err);
    }
}

// ── Démarrage caméra ───────────────────────────────────────
startBtn.addEventListener('click', async () => {
    if (!modelsLoaded) return;
    startBtn.disabled = true;
    setStatus('⏳ Activation de la caméra...', 'warn');
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { width: 320, height: 320, facingMode: 'user' }
        });
        video.srcObject = stream;
        video.style.display = 'block';
        placeholder.style.display = 'none';
        await video.play();
        scanRing.style.display   = 'block';
        captureDots.style.display= 'flex';
        progressWrap.style.display = 'block';
        setStatus('👤 Regardez la caméra, analyse en cours...', 'warn');
        startScanLoop();
    } catch (err) {
        setStatus('❌ Caméra inaccessible : ' + err.message, 'err');
        startBtn.disabled = false;
    }
});

// ── Boucle de scan — moyenne de CAPTURES_NEED descripteurs ─
async function startScanLoop() {
    if (scanning) return;
    scanning = true;
    capturesDone = 0;
    descriptors  = [];

    const size = { width: video.videoWidth || 320, height: video.videoHeight || 320 };
    faceapi.matchDimensions(canvas, size);

    const loop = async () => {
        if (!scanning) return;

        const det = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
            .withFaceLandmarks()
            .withFaceDescriptor();

        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (det) {
            faceapi.draw.drawDetections(canvas, faceapi.resizeResults(det, size));
            descriptors.push(Array.from(det.descriptor));
            capturesDone++;
            markDot(capturesDone - 1);
            setProgress((capturesDone / CAPTURES_NEED) * 100);
            setStatus(`📸 Capture ${capturesDone}/${CAPTURES_NEED} — restez immobile...`, 'ok');

            if (capturesDone >= CAPTURES_NEED) {
                scanning = false;
                stopCamera();
                setStatus('🔍 Vérification en cours...', 'warn');
                await authenticate();
                return;
            }
            // Pause entre captures pour varier légèrement
            await new Promise(r => setTimeout(r, 700));
        } else {
            setStatus('👤 Aucun visage — centrez votre visage dans le cercle.', 'warn');
        }

        requestAnimationFrame(loop);
    };

    loop();
}

// ── Moyenne + envoi ────────────────────────────────────────
async function authenticate() {
    // Calculer le descripteur moyen
    const averaged = new Array(128).fill(0);
    descriptors.forEach(d => d.forEach((v, i) => { averaged[i] += v; }));
    const finalDescriptor = averaged.map(v => v / descriptors.length);

    try {
        const resp = await fetch('index.php?action=face_login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ descriptor: finalDescriptor })
        });
        const data = await resp.json();

        if (data.success) {
            setStatus('✅ Bonjour ' + (data.prenom || '') + ' ! Connexion réussie...', 'ok');
            setTimeout(() => { window.location.href = data.redirect; }, 1200);
        } else {
            setStatus('❌ Visage non reconnu. Réessayez ou utilisez votre mot de passe.', 'err');
            // Permettre de réessayer
            setTimeout(() => resetScan(), 2500);
        }
    } catch (err) {
        setStatus('❌ Erreur réseau : ' + err.message, 'err');
        setTimeout(() => resetScan(), 2500);
    }
}

// ── Reset pour nouvel essai ────────────────────────────────
async function resetScan() {
    capturesDone = 0;
    descriptors  = [];
    // Remettre les dots à zéro
    for (let i = 0; i < CAPTURES_NEED; i++) {
        const d = document.getElementById('cdot' + i);
        if (d) d.classList.remove('done');
    }
    setProgress(0);

    // Relancer la caméra si elle s'est arrêtée
    if (!stream || stream.getTracks().every(t => t.readyState === 'ended')) {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 320, height: 320, facingMode: 'user' }
            });
            video.srcObject = stream;
            video.style.display = 'block';
            await video.play();
        } catch(e) {
            setStatus('❌ Caméra inaccessible.', 'err');
            startBtn.disabled = false;
            return;
        }
    }
    scanRing.style.display = 'block';
    setStatus('👤 Réessai — regardez la caméra.', 'warn');
    startScanLoop();
}

function stopCamera() {
    scanRing.style.display = 'none';
    if (stream) stream.getTracks().forEach(t => t.stop());
}

loadModels();
</script>
</body>
</html>
