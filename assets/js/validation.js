/* ============================================================
   SmartFood – validation.js
   Contrôle de saisie côté client pour tous les formulaires
   ============================================================ */

/* ── Helpers ──────────────────────────────────────────────── */
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

function showError(input, message) {
    clearFeedback(input);
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    const err = document.createElement('div');
    err.className = 'js-error';
    err.textContent = message;
    input.parentNode.appendChild(err);
}

function showSuccess(input) {
    clearFeedback(input);
    input.classList.add('is-valid');
    input.classList.remove('is-invalid');
}

function clearFeedback(input) {
    input.classList.remove('is-invalid', 'is-valid');
    const existing = input.parentNode.querySelector('.js-error, .js-success');
    if (existing) existing.remove();
}

function validate(input, condition, message) {
    if (!condition) { showError(input, message); return false; }
    showSuccess(input);
    return true;
}

/* ── Live validation (on blur) ────────────────────────────── */
function attachLiveValidation(form) {
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', () => {
            if (field.value.trim() === '' && field.hasAttribute('required')) {
                showError(field, 'Ce champ est obligatoire.');
            } else {
                clearFeedback(field);
            }
        });
    });
}

/* ── Inscription ──────────────────────────────────────────── */
document.querySelectorAll('form[action*="action=register"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom     = this.querySelector('[name="nom"]');
        const prenom  = this.querySelector('[name="prenom"]');
        const email   = this.querySelector('[name="email"]');
        const pwd     = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,           'Nom : 2 caractères minimum.')     && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,        'Prénom : 2 caractères minimum.')  && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),              'Adresse email invalide.')          && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,                  'Mot de passe : 6 caractères min.') && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, 'Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ── Connexion ────────────────────────────────────────────── */
document.querySelectorAll('form[action*="action=login"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const email = this.querySelector('[name="email"]');
        const pwd   = this.querySelector('[name="password"]');

        if (email) valid = validate(email, isValidEmail(email.value), 'Adresse email invalide.')       && valid;
        if (pwd)   valid = validate(pwd,   pwd.value.trim() !== '',   'Mot de passe obligatoire.')    && valid;
        if (valid) this.submit();
    });
});

/* ── Mot de passe oublié (page dédiée) ───────────────────── */
document.querySelectorAll('form[action*="action=forgot_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const email = this.querySelector('[name="email"]');
        if (email && validate(email, isValidEmail(email.value), 'Adresse email invalide.')) {
            this.submit();
        }
    });
});

/* ── Réinitialisation mot de passe (page dédiée) ─────────── */
document.querySelectorAll('form[action*="action=reset_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const pwd     = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,           'Mot de passe : 6 caractères min.')     && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, 'Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ── Changer mot de passe ─────────────────────────────────── */
document.querySelectorAll('form[action*="action=update_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const current = this.querySelector('[name="current_password"]');
        const newPwd  = this.querySelector('[name="new_password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (current) valid = validate(current, current.value.trim() !== '',    'Mot de passe actuel obligatoire.')           && valid;
        if (newPwd)  valid = validate(newPwd,  newPwd.value.length >= 6,       'Nouveau mot de passe : 6 caractères min.')   && valid;
        if (confirm && newPwd) {
            valid = validate(confirm, confirm.value === newPwd.value, 'Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ── Modifier profil ──────────────────────────────────────── */
document.querySelectorAll('form[action*="action=update_profile"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom    = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email  = this.querySelector('[name="email"]');
        const age    = this.querySelector('[name="age"]');
        const poids  = this.querySelector('[name="poids"]');
        const taille = this.querySelector('[name="taille"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  'Nom : 2 caractères minimum.')     && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     'Adresse email invalide.')          && valid;

        // Champs numériques optionnels : valider seulement si remplis
        if (age && age.value !== '') {
            const a = parseFloat(age.value);
            valid = validate(age, !isNaN(a) && a >= 10 && a <= 120, 'Âge : entre 10 et 120 ans.') && valid;
        }
        if (poids && poids.value !== '') {
            const p = parseFloat(poids.value);
            valid = validate(poids, !isNaN(p) && p >= 20 && p <= 300, 'Poids : entre 20 et 300 kg.') && valid;
        }
        if (taille && taille.value !== '') {
            const t = parseFloat(taille.value);
            valid = validate(taille, !isNaN(t) && t >= 50 && t <= 250, 'Taille : entre 50 et 250 cm.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ── Ajouter utilisateur (admin) ──────────────────────────── */
document.querySelectorAll('form[action*="action=add_user"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom    = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email  = this.querySelector('[name="email"]');
        const pwd    = this.querySelector('[name="password"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  'Nom : 2 caractères minimum.')     && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     'Adresse email invalide.')          && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,         'Mot de passe : 6 caractères min.') && valid;
        if (valid) this.submit();
    });
});

/* ── Modifier utilisateur (admin) ─────────────────────────── */
document.querySelectorAll('form[action*="action=update_user"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom    = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email  = this.querySelector('[name="email"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  'Nom : 2 caractères minimum.')     && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     'Adresse email invalide.')          && valid;
        if (valid) this.submit();
    });
});

/* ── Validation modale inscription (home.php) ─────────────── */
const registerFormModal = document.getElementById('registerForm');
if (registerFormModal) {
    registerFormModal.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom     = this.querySelector('[name="nom"]');
        const prenom  = this.querySelector('[name="prenom"]');
        const email   = this.querySelector('[name="email"]');
        const pwd     = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,            'Nom requis (2 car. min).')            && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,         'Prénom requis (2 car. min).')         && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),               'Email invalide.')                     && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,                   'Mot de passe ≥ 6 caractères.')        && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, 'Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
}

/* ── Validation modale connexion (home.php) ───────────────── */
const loginFormModal = document.getElementById('loginForm');
if (loginFormModal) {
    loginFormModal.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const email = this.querySelector('[name="email"]');
        const pwd   = this.querySelector('[name="password"]');

        if (email) valid = validate(email, isValidEmail(email.value), 'Email invalide.')          && valid;
        if (pwd)   valid = validate(pwd,   pwd.value.trim() !== '',   'Mot de passe obligatoire.') && valid;
        if (valid) this.submit();
    });
}

/* ── Validation modale forgot (home.php) ──────────────────── */
const forgotFormModal = document.getElementById('forgotForm');
if (forgotFormModal && !forgotFormModal.hasAttribute('action')) { // modale AJAX uniquement
    const emailInput = document.getElementById('forgotEmail');
    forgotFormModal.addEventListener('submit', function (e) {
        // On laisse le JS AJAX de home.php gérer la soumission
        // On valide seulement l'email ici
        if (emailInput && !isValidEmail(emailInput.value)) {
            e.preventDefault();
            showError(emailInput, 'Adresse email invalide.');
        }
    });
}