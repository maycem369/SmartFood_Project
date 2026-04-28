<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood – Mangez sain, gaspillez moins</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">>
</head>
<body>

<!-- Navigation -->
<nav style="background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="font-size: 1.8rem; font-weight: bold; color: var(--smart-green);">Smart<span style="color: var(--smart-orange);">Food</span></div>
    <div>
        <a href="#" id="loginBtn" class="btn-hero" style="padding: 8px 24px;">Connexion</a>
        <a href="#" id="registerBtn" class="btn-hero" style="padding: 8px 24px;">Inscription</a>
    </div>
</nav>

<section class="hero">
    <h1>Mangez sain, gaspillez moins</h1>
    <p>SmartFood utilise l’IA pour vous proposer des recettes anti-gaspillage.</p>
    <a href="#" id="heroRegisterBtn" class="btn-hero">Commencez gratuitement →</a>
</section>

<div class="grid-3">
    <div class="card"><i class="fas fa-utensils fa-2x" style="color:var(--smart-orange);"></i><h3>Recettes personnalisées</h3><p>À partir de vos ingrédients.</p></div>
    <div class="card"><i class="fas fa-chart-line fa-2x" style="color:var(--smart-orange);"></i><h3>Nutrition intelligente</h3><p>Calories, protéines, glucides.</p></div>
    <div class="card"><i class="fas fa-leaf fa-2x" style="color:var(--smart-orange);"></i><h3>Anti-gaspillage</h3><p>Réduisez vos déchets.</p></div>
</div>

<!-- MODALE CONNEXION -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="loginModal">&times;</span>
        <h2>Connexion</h2>
        <form id="loginForm" method="POST" action="index.php?action=login">
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <div class="modal-footer"><a href="#" id="forgotPasswordLink">Mot de passe oublié ?</a><br><a href="#" id="switchToRegister">Pas de compte ? S'inscrire</a></div>
    </div>
</div>

<!-- MODALE INSCRIPTION -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="registerModal">&times;</span>
        <h2>Inscription</h2>
        <form id="registerForm" method="POST" action="index.php?action=register">
            <div class="two-columns"><div class="form-group"><label>Nom</label><input type="text" name="nom" required></div><div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <div class="modal-footer"><a href="#" id="switchToLogin">Déjà inscrit ? Se connecter</a></div>
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
        <div class="modal-footer"><a href="#" id="backToLoginFromForgot">Retour à la connexion</a></div>
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
        <div class="modal-footer"><a href="#" id="backToLoginFromReset">Retour à la connexion</a></div>
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
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const backToLoginFromForgot = document.getElementById('backToLoginFromForgot');
    const backToLoginFromReset = document.getElementById('backToLoginFromReset');

    const openModal = (modal) => modal.style.display = 'flex';
    const closeModal = (modal) => modal.style.display = 'none';

    loginBtn.onclick = (e) => { e.preventDefault(); openModal(loginModal); };
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