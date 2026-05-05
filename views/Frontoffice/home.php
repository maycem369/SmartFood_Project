<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood – L'intelligence au service de votre nutrition</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/chatbot.js" defer></script>
    <style>
        /* Hero section styles */
        .hero-home {
            padding: 80px 48px;
            background: linear-gradient(135deg, var(--front-accent-glow) 0%, var(--front-bg) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }
        .hero-content {
            flex: 1;
            max-width: 600px;
        }
        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--front-accent) 0%, var(--front-orange) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-content p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 32px;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
        }
        .hero-image {
            width: 400px;
            height: 350px;
            background: linear-gradient(135deg, var(--front-orange) 0%, var(--front-accent) 100%);
            border-radius: var(--border-radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            box-shadow: var(--shadow-lg);
        }
        .home-nav {
            background: var(--white);
            padding: 20px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-xs);
        }
        .home-nav .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--front-accent);
            font-family: 'Poppins', sans-serif;
        }
        .home-nav .logo span {
            color: var(--front-orange);
        }
        .home-nav-links {
            display: flex;
            gap: 12px;
        }
        .home-features {
            padding: 80px 48px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }
        .feature-card {
            background: var(--white);
            border: 1px solid rgba(45, 106, 79, 0.08);
            border-radius: var(--border-radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            text-align: center;
            transition: var(--t);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--front-accent-glow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            color: var(--front-accent);
        }
        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            color: var(--text-dark);
        }
        .feature-card p {
            color: var(--text-muted);
        }
        .modal-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 20px 0;
        }
        .modal-divider span {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
        }
        .modal-divider::before, .modal-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="home-nav">
    <div class="logo">
        <img src="assets/img/logo.png" alt="SmartFood Logo" style="height: 40px; margin-right: 12px; vertical-align: middle;">
        Smart<span>Food</span>
    </div>
    <div class="home-nav-links">
        <a href="#" id="loginBtn" class="btn btn-secondary" data-i18n="auth_login">Connexion</a>
        <a href="#" id="registerBtn" class="btn btn-orange" data-i18n="auth_register">Inscription</a>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-home">
    <div class="hero-content">
        <h1 data-i18n="home_hero_title">Mangez sain,<br>gaspillez moins</h1>
        <p data-i18n="home_hero_desc">SmartFood utilise l’intelligence artificielle pour vous proposer des recettes anti-gaspillage ultra-personnalisées et vous connecter en un clin d'œil avec Face ID.</p>
        <div class="hero-buttons">
            <a href="#" id="heroRegisterBtn" class="btn btn-orange"><span data-i18n="home_btn_start">Commencez gratuitement</span> <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
            <a href="#" id="heroLoginBtn" class="btn btn-secondary" data-i18n="auth_login">Se connecter</a>
        </div>
    </div>
    <div class="hero-image-wrapper" style="position: relative; animation: float 6s ease-in-out infinite;">
        <img src="assets/img/logo.png" alt="SmartFood" style="width: 350px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));">
        <div style="position: absolute; top: -20px; right: -20px; background: white; padding: 12px; border-radius: 50%; box-shadow: var(--shadow-md); font-size: 2rem;">🥗</div>
        <div style="position: absolute; bottom: -20px; left: -20px; background: white; padding: 12px; border-radius: 50%; box-shadow: var(--shadow-md); font-size: 2rem;">🥑</div>
    </div>
</section>

<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
</style>

<!-- Features Section -->
<section class="home-features">
    <div class="feature-card">
        <div class="feature-icon">🥗</div>
        <h3>Recettes personnalisées</h3>
        <p>À partir de vos ingrédients disponibles pour réduire le gaspillage.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3>Nutrition intelligente</h3>
        <p>Suivi des calories, protéines, glucides pour atteindre vos objectifs.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">🪪</div>
        <h3>Connexion Face ID</h3>
        <p>Connectez-vous rapidement et en toute sécurité avec votre visage.</p>
    </div>
</section>

<!-- MODALE CONNEXION -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="loginModal">&times;</span>
        <h2 data-i18n="auth_login">Connexion</h2>
        <form id="loginForm" method="POST" action="index.php?action=login">
            <div class="form-group"><label data-i18n="auth_email">Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label data-i18n="auth_pwd">Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary" data-i18n="auth_login">Se connecter</button>
        </form>
        
        <div class="modal-divider"><span>OU</span></div>
        
        <a href="index.php?action=login_face" class="btn btn-secondary" style="width: 100%; justify-content: center; margin-bottom: 20px;" data-i18n="auth_faceid">
            🪪 Connexion par visage
        </a>
        
        <div class="modal-footer">
            <a href="#" id="forgotPasswordLink" data-i18n="auth_forgot">Mot de passe oublié ?</a><br>
            <a href="#" id="switchToRegister" data-i18n="auth_no_account">Pas de compte ? S'inscrire</a>
        </div>
    </div>
</div>

<!-- MODALE INSCRIPTION -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="registerModal">&times;</span>
        <h2 data-i18n="auth_register">Inscription</h2>
        <form id="registerForm" method="POST" action="index.php?action=register">
            <div class="two-columns">
                <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
            </div>
            <div class="form-group"><label data-i18n="auth_email">Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label data-i18n="auth_pwd">Mot de passe</label><input type="password" name="password" required></div>
            <div class="form-group"><label data-i18n="auth_confirm">Confirmer</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn btn-primary" data-i18n="auth_register">S'inscrire</button>
        </form>
        
        <div class="modal-divider"><span>PLUS TARD</span></div>
        
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px; text-align: center;">
            Après l'inscription, vous pourrez enregistrer votre Face ID depuis votre profil !
        </p>
        
        <div class="modal-footer">
            <a href="#" id="switchToLogin" data-i18n="auth_has_account">Déjà inscrit ? Se connecter</a>
        </div>
    </div>
</div>

<!-- MODALE MOT DE PASSE OUBLIÉ -->
<div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="forgotModal">&times;</span>
        <h2>Réinitialisation</h2>
        <div id="forgotMessage" style="display:none;"></div>
        <form id="forgotForm">
            <div class="form-group"><label>Email</label><input type="email" name="email" id="forgotEmail" required></div>
            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
        </form>
        <div class="modal-footer">
            <a href="#" id="backToLoginFromForgot">Retour à la connexion</a>
        </div>
    </div>
</div>

<!-- MODALE RÉINITIALISATION (NOUVEAU MOT DE PASSE) -->
<div id="resetModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="resetModal">&times;</span>
        <h2>Nouveau mot de passe</h2>
        <div id="resetMessage" style="display:none;"></div>
        <form id="resetForm">
            <input type="hidden" name="token" id="resetToken">
            <div class="form-group"><label>Nouveau mot de passe</label><input type="password" name="password" id="resetPassword" required></div>
            <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" id="resetConfirm" required></div>
            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
        </form>
        <div class="modal-footer">
            <a href="#" id="backToLoginFromReset">Retour à la connexion</a>
        </div>
    </div>
</div>

<script>
    // Éléments
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const forgotModal = document.getElementById('forgotModal');
    const resetModal = document.getElementById('resetModal');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const heroRegisterBtn = document.getElementById('heroRegisterBtn');
    const heroLoginBtn = document.getElementById('heroLoginBtn');
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const backToLoginFromForgot = document.getElementById('backToLoginFromForgot');
    const backToLoginFromReset = document.getElementById('backToLoginFromReset');

    const openModal = (modal) => modal.style.display = 'flex';
    const closeModal = (modal) => modal.style.display = 'none';

    loginBtn.onclick = (e) => { e.preventDefault(); openModal(loginModal); };
    heroLoginBtn.onclick = (e) => { e.preventDefault(); openModal(loginModal); };
    registerBtn.onclick = (e) => { e.preventDefault(); openModal(registerModal); };
    heroRegisterBtn?.addEventListener('click', (e) => { e.preventDefault(); openModal(registerModal); });
    switchToRegister.onclick = (e) => { e.preventDefault(); closeModal(loginModal); openModal(registerModal); };
    switchToLogin.onclick = (e) => { e.preventDefault(); closeModal(registerModal); openModal(loginModal); };
    forgotPasswordLink.onclick = (e) => { e.preventDefault(); closeModal(loginModal); openModal(forgotModal); };
    backToLoginFromForgot.onclick = (e) => { e.preventDefault(); closeModal(forgotModal); openModal(loginModal); };
    backToLoginFromReset.onclick = (e) => { e.preventDefault(); closeModal(resetModal); openModal(loginModal); };

    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.onclick = () => closeModal(document.getElementById(btn.getAttribute('data-modal')));
    });
    window.onclick = (e) => {
        if (e.target === loginModal) closeModal(loginModal);
        if (e.target === registerModal) closeModal(registerModal);
        if (e.target === forgotModal) closeModal(forgotModal);
        if (e.target === resetModal) closeModal(resetModal);
    };

    // FORGOT PASSWORD (AJAX)
    const forgotForm = document.getElementById('forgotForm');
    const forgotMessage = document.getElementById('forgotMessage');
    forgotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('forgotEmail').value;
        forgotMessage.style.display = 'block';
        forgotMessage.innerHTML = '<div class="alert alert-info">Envoi en cours...</div>';
        fetch('index.php?action=ajax_forgot_password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.resetToken = data.token;
                forgotMessage.innerHTML = '<div class="alert alert-success">' + data.message + '<br><button id="openResetBtn" class="btn btn-primary" style="margin-top:10px;">Réinitialiser mon mot de passe</button></div>';
                document.getElementById('openResetBtn').addEventListener('click', function() {
                    closeModal(forgotModal);
                    document.getElementById('resetToken').value = window.resetToken;
                    openModal(resetModal);
                });
                setTimeout(() => { closeModal(forgotModal); }, 8000);
            } else {
                forgotMessage.innerHTML = '<div class="alert alert-error">' + data.message + '</div>';
            }
        })
        .catch(() => { forgotMessage.innerHTML = '<div class="alert alert-error">Erreur réseau.</div>'; });
    });

    // RESET PASSWORD (AJAX)
    const resetForm = document.getElementById('resetForm');
    const resetMessage = document.getElementById('resetMessage');
    resetForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const token = document.getElementById('resetToken').value;
        const password = document.getElementById('resetPassword').value;
        const confirm = document.getElementById('resetConfirm').value;
        if (password.length < 6) {
            resetMessage.style.display = 'block';
            resetMessage.innerHTML = '<div class="alert alert-error">Le mot de passe doit contenir au moins 6 caractères.</div>';
            return;
        }
        if (password !== confirm) {
            resetMessage.style.display = 'block';
            resetMessage.innerHTML = '<div class="alert alert-error">Les mots de passe ne correspondent pas.</div>';
            return;
        }
        resetMessage.style.display = 'block';
        resetMessage.innerHTML = '<div class="alert alert-info">Envoi en cours...</div>';
        fetch('index.php?action=ajax_reset_password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'token=' + encodeURIComponent(token) + '&password=' + encodeURIComponent(password)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                resetMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                setTimeout(() => {
                    closeModal(resetModal);
                    resetMessage.style.display = 'none';
                    openModal(loginModal);
                }, 2000);
            } else {
                resetMessage.innerHTML = '<div class="alert alert-error">' + data.message + '</div>';
            }
        })
        .catch(() => { resetMessage.innerHTML = '<div class="alert alert-error">Erreur réseau.</div>'; });
    });
</script>

</body>
</html>
