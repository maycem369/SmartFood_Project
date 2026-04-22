<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood – Mangez sain, gaspillez moins</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Modales */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: var(--white);
            border-radius: 24px;
            width: 90%;
            max-width: 450px;
            padding: 30px;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: fadeInUp 0.3s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #999;
        }
        .close-modal:hover { color: var(--smart-green); }
        .modal-content h2 {
            color: var(--smart-green);
            margin-bottom: 20px;
            text-align: center;
        }
        .modal-content .form-group { margin-bottom: 15px; }
        .modal-content .btn-primary { width: 100%; }
        .modal-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .modal-footer a {
            color: var(--smart-green);
            text-decoration: none;
            cursor: pointer;
        }
        .modal-footer a:hover { text-decoration: underline; }
        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }

        /* Landing page styles */
        .hero { background: linear-gradient(135deg, var(--smart-green) 0%, #1f4a38 100%); color: white; padding: 80px 20px; text-align: center; }
        .hero h1 { font-size: 3rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; max-width: 600px; margin: 0 auto 2rem; }
        .btn-hero { background: var(--smart-orange); color: white; padding: 12px 32px; border-radius: 40px; font-weight: 600; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-hero:hover { background: #e67e00; transform: translateY(-2px); }
        .section { padding: 60px 20px; max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 1.8rem; color: var(--smart-green); margin-bottom: 40px; font-weight: 600; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .card { background: var(--white); padding: 30px 20px; border-radius: 24px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.04); transition: 0.2s; }
        .card i { font-size: 2.5rem; color: var(--smart-orange); margin-bottom: 1rem; }
        .card h3 { color: var(--smart-green); margin-bottom: 0.5rem; }
        .bg-light { background: #F9FBF9; }
        .odd-badge { background: var(--light-green-badge); padding: 20px; border-radius: 20px; text-align: center; }
        .comparison-table { overflow-x: auto; }
        .comparison-table table { width: 100%; border-collapse: collapse; background: white; border-radius: 20px; overflow: hidden; }
        .comparison-table th, .comparison-table td { padding: 14px; text-align: center; border-bottom: 1px solid var(--border); }
        .comparison-table th { background: var(--smart-green); color: white; }
        .fa-check-circle { color: #2D6A4F; }
        .fa-times-circle { color: #e74c3c; }
        @media (max-width: 768px) { .grid-3 { grid-template-columns: 1fr; } .hero h1 { font-size: 2rem; } }
    </style>
</head>
<body>

<!-- Navigation -->
<nav style="background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; box-shadow: 0 1px 8px rgba(0,0,0,0.05);">
    <div style="font-size: 1.8rem; font-weight: bold; color: var(--smart-green);">Smart<span style="color: var(--smart-orange);">Food</span></div>
    <div>
        <a href="#" id="loginBtn" style="margin-right: 1.5rem; color: var(--smart-green); text-decoration: none; font-weight: 500;">Connexion</a>
        <a href="#" id="registerBtn" class="btn-hero" style="padding: 8px 24px; font-size: 0.9rem;">Inscription</a>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <h1>Mangez sain, gaspillez moins</h1>
    <p>SmartFood utilise l’intelligence artificielle pour vous proposer des recettes anti-gaspillage adaptées à vos ingrédients.</p>
    <a href="#" id="heroRegisterBtn" class="btn-hero">Commencez gratuitement →</a>
</section>

<!-- 3 piliers -->
<section class="section">
    <div class="grid-3">
        <div class="card"><i class="fas fa-utensils"></i><h3>Recettes personnalisées</h3><p>À partir de vos ingrédients, l’IA génère des idées de repas équilibrés.</p></div>
        <div class="card"><i class="fas fa-chart-line"></i><h3>Nutrition intelligente</h3><p>Calories, protéines, glucides – suivez votre alimentation.</p></div>
        <div class="card"><i class="fas fa-leaf"></i><h3>Anti-gaspillage</h3><p>Réduisez vos déchets alimentaires et cuisinez durablement.</p></div>
    </div>
</section>

<!-- IA + ODD -->
<section class="bg-light" style="padding: 60px 20px;">
    <div class="section" style="padding: 0;">
        <div class="grid-3">
            <div class="odd-badge"><i class="fas fa-robot" style="font-size: 2rem; color: var(--smart-orange);"></i><h4>IA & Chatbot</h4><p>Suggestions intelligentes, analyse de vos habitudes.</p></div>
            <div class="odd-badge"><i class="fas fa-globe" style="font-size: 2rem; color: var(--smart-orange);"></i><h4>ODD 2, 3 & 12</h4><p>Faim zéro, bonne santé, consommation responsable.</p></div>
            <div class="odd-badge"><i class="fas fa-store" style="font-size: 2rem; color: var(--smart-orange);"></i><h4>Circuits courts</h4><p>Promotion des produits locaux et de saison.</p></div>
        </div>
    </div>
</section>

<!-- Comparatif -->
<section class="section">
    <h2 class="section-title">Pourquoi SmartFood ?</h2>
    <div class="comparison-table">
        <table>
            <thead><tr><th>Fonctionnalité</th><th>SmartFood</th><th>Yummly</th><th>MyFitnessPal</th><th>Too Good To Go</th></tr></thead>
            <tbody>
                <tr><td>Recettes personnalisées</td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-times-circle"></i></td><td><i class="fas fa-times-circle"></i></td></tr>
                <tr><td>Nutrition intégrée</td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-times-circle"></i></td></tr>
                <tr><td>Anti-gaspillage</td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-times-circle"></i></td><td><i class="fas fa-times-circle"></i></td><td><i class="fas fa-check-circle"></i></td></tr>
                <tr><td>IA / Suggestions</td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-check-circle"></i></td><td><i class="fas fa-times-circle"></i></td><td><i class="fas fa-times-circle"></i></td></tr>
            </tbody>
        </table>
    </div>
</section>

<!-- Équipe -->
<section class="bg-light" style="padding: 40px 20px; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
        <p style="color: var(--smart-green); font-weight: 600;">Made with ❤️ by</p>
        <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
            <span><strong>Maycem Ouji</strong> – Dev</span>
            <span><strong>Anas Hidri</strong> – Design</span>
            <span><strong>Rayen Guermassi</strong> – Manager</span>
        </div>
        <hr style="margin: 30px 0; border-color: var(--border);">
        <p style="font-size: 0.85rem; color: #666;">© 2025-2026 SmartFood – Projet Technologies Web 2A34</p>
    </div>
</section>

<!-- MODALE CONNEXION -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="loginModal">&times;</span>
        <h2>Connexion</h2>
        <form id="loginForm" method="POST" action="index.php?action=login" novalidate>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <div class="modal-footer">
            <a href="#" id="switchToRegister">Pas de compte ? S'inscrire</a><br>
            <a href="#" id="forgotPasswordLink" style="font-size:0.85rem;">Mot de passe oublié ?</a>
        </div>
    </div>
</div>

<!-- MODALE INSCRIPTION -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="registerModal">&times;</span>
        <h2>Inscription</h2>
        <form id="registerForm" method="POST" action="index.php?action=register" novalidate>
            <div class="two-columns" style="gap: 10px;">
                <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
            </div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Confirmer</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <div class="modal-footer">
            <a href="#" id="switchToLogin">Déjà inscrit ? Se connecter</a>
        </div>
    </div>
</div>

<!-- MODALE MOT DE PASSE OUBLIÉ -->
<div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="forgotModal">&times;</span>
        <h2>Réinitialisation</h2>
        <div id="forgotMessage" style="display:none;"></div>
        <form id="forgotForm" novalidate>
            <div class="form-group"><label>Email</label><input type="email" name="email" id="forgotEmail" required></div>
            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
        </form>
        <div class="modal-footer">
            <a href="#" id="backToLoginFromForgot">Retour à la connexion</a>
        </div>
    </div>
</div>

<script>
    // Gestion des modales
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const forgotModal = document.getElementById('forgotModal');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const heroRegisterBtn = document.getElementById('heroRegisterBtn');
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const backToLoginFromForgot = document.getElementById('backToLoginFromForgot');

    function openModal(modal) { modal.style.display = 'flex'; }
    function closeModal(modal) { modal.style.display = 'none'; }

    loginBtn.onclick = (e) => { e.preventDefault(); openModal(loginModal); };
    registerBtn.onclick = (e) => { e.preventDefault(); openModal(registerModal); };
    if(heroRegisterBtn) heroRegisterBtn.onclick = (e) => { e.preventDefault(); openModal(registerModal); };
    switchToRegister.onclick = (e) => { e.preventDefault(); closeModal(loginModal); openModal(registerModal); };
    switchToLogin.onclick = (e) => { e.preventDefault(); closeModal(registerModal); openModal(loginModal); };
    forgotPasswordLink.onclick = (e) => { e.preventDefault(); closeModal(loginModal); openModal(forgotModal); };
    backToLoginFromForgot.onclick = (e) => { e.preventDefault(); closeModal(forgotModal); openModal(loginModal); };

    // Fermeture avec croix
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.onclick = () => closeModal(document.getElementById(btn.getAttribute('data-modal')));
    });
    // Fermeture en cliquant en dehors
    window.onclick = (e) => {
        if (e.target === loginModal) closeModal(loginModal);
        if (e.target === registerModal) closeModal(registerModal);
        if (e.target === forgotModal) closeModal(forgotModal);
    };

    // AJAX pour mot de passe oublié
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
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Afficher un lien cliquable qui s'ouvre dans un nouvel onglet
        forgotMessage.innerHTML = '<div class="alert alert-success">' + data.message + '<br><a href="' + data.link + '" target="_blank" style="color: var(--smart-green); font-weight: bold;">🔗 Cliquez ici pour réinitialiser votre mot de passe</a></div>';
        // Optionnel : fermeture auto après 6 secondes
        setTimeout(() => { closeModal(forgotModal); forgotMessage.style.display = 'none'; }, 6000);
    } else {
        forgotMessage.innerHTML = '<div class="alert alert-error">' + data.message + '</div>';
    }
})
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                forgotMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                // Optionnel : fermer la modale après 3 secondes
                setTimeout(() => { closeModal(forgotModal); forgotMessage.style.display = 'none'; }, 3000);
            } else {
                forgotMessage.innerHTML = '<div class="alert alert-error">' + data.message + '</div>';
            }
        })
        .catch(error => {
            forgotMessage.innerHTML = '<div class="alert alert-error">Erreur réseau. Réessayez.</div>';
        });
    });
</script>

<script src="assets/js/validation.js"></script>
</body>
</html>