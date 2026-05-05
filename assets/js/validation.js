/* ============================================================
   SmartFood – validation.js (v2)
   Validation 100% JavaScript — aucun attribut HTML5 utilisé
   ============================================================ */

'use strict';

/* ═══════════════════════════════════════════════════════════
   HELPERS
   ═══════════════════════════════════════════════════════════ */
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

function showError(input, message) {
    clearFeedback(input);
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    const err = document.createElement('div');
    err.className = 'js-error';
    err.innerHTML = '⚠ ' + message;
    input.parentNode.appendChild(err);
    // Animation d'entrée
    input.style.animation = 'none';
    requestAnimationFrame(() => {
        input.style.animation = 'shakeInput .35s ease';
    });
}

function showSuccess(input) {
    clearFeedback(input);
    input.classList.add('is-valid');
    input.classList.remove('is-invalid');
    const ok = document.createElement('div');
    ok.className = 'js-success';
    ok.innerHTML = '✓';
    input.parentNode.appendChild(ok);
}

function clearFeedback(input) {
    input.classList.remove('is-invalid', 'is-valid');
    const existing = input.parentNode.querySelectorAll('.js-error, .js-success, .pwd-strength');
    existing.forEach(el => el.remove());
}

function validate(input, condition, message) {
    if (!condition) { showError(input, message); return false; }
    showSuccess(input);
    return true;
}

/* ═══════════════════════════════════════════════════════════
   FORCE DU MOT DE PASSE
   ═══════════════════════════════════════════════════════════ */
function getPasswordStrength(pwd) {
    let score = 0;
    if (pwd.length >= 6)  score++;
    if (pwd.length >= 10) score++;
    if (/[A-Z]/.test(pwd)) score++;
    if (/[0-9]/.test(pwd)) score++;
    if (/[^A-Za-z0-9]/.test(pwd)) score++;
    return score; // 0-5
}

function renderPasswordStrength(input) {
    // Supprime l'ancien indicateur s'il existe
    const existing = input.parentNode.querySelector('.pwd-strength');
    if (existing) existing.remove();

    const pwd = input.value;
    if (pwd.length === 0) return;

    const score = getPasswordStrength(pwd);
    const levels = [
        { label: 'Très faible', color: '#e74c3c', width: '20%' },
        { label: 'Faible',      color: '#e67e22', width: '35%' },
        { label: 'Moyen',       color: '#f39c12', width: '55%' },
        { label: 'Bon',         color: '#27ae60', width: '75%' },
        { label: 'Fort',        color: '#2D6A4F', width: '90%' },
        { label: 'Excellent',   color: '#1b4332', width: '100%'},
    ];
    const lvl = levels[Math.min(score, 5)];

    const bar = document.createElement('div');
    bar.className = 'pwd-strength';
    bar.innerHTML = `
        <div style="height:4px;background:#eee;border-radius:4px;margin-top:6px;overflow:hidden;">
            <div style="height:100%;width:${lvl.width};background:${lvl.color};border-radius:4px;transition:width .4s,background .4s;"></div>
        </div>
        <span style="font-size:.75rem;color:${lvl.color};font-weight:600;margin-top:3px;display:block;">${lvl.label}</span>`;
    input.parentNode.appendChild(bar);
}

/* ═══════════════════════════════════════════════════════════
   STYLES D'ANIMATION (injectés une seule fois)
   ═══════════════════════════════════════════════════════════ */
(function injectStyles() {
    if (document.getElementById('sf-val-styles')) return;
    const s = document.createElement('style');
    s.id = 'sf-val-styles';
    s.textContent = `
        @keyframes shakeInput {
            0%,100%{ transform:translateX(0); }
            20%    { transform:translateX(-6px); }
            40%    { transform:translateX(6px); }
            60%    { transform:translateX(-4px); }
            80%    { transform:translateX(4px); }
        }
        .js-error {
            color:#e74c3c; font-size:.8rem; margin-top:5px;
            display:flex; align-items:center; gap:4px; font-weight:600;
            animation:fadeIn .2s ease;
        }
        .js-success {
            color:#2D6A4F; font-size:.8rem; margin-top:5px;
            font-weight:700; animation:fadeIn .2s ease;
        }
        @keyframes fadeIn { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:none} }
        input.is-invalid { border-color:#e74c3c !important; box-shadow:0 0 0 3px rgba(231,76,60,.15) !important; }
        input.is-valid   { border-color:#2D6A4F !important; box-shadow:0 0 0 3px rgba(45,106,79,.12) !important; }
    `;
    document.head.appendChild(s);
})();

/* ═══════════════════════════════════════════════════════════
   VALIDATION LIVE (frappe + blur)
   ═══════════════════════════════════════════════════════════ */
function attachLiveValidation(form) {
    form.querySelectorAll('input, select, textarea').forEach(field => {

        // Validation au blur (perd le focus)
        field.addEventListener('blur', () => {
            const v = field.value.trim();
            const name = field.getAttribute('name') || '';

            if (v === '') {
                // Champs optionnels connus
                const optional = ['allergies', 'age', 'poids', 'taille'];
                if (!optional.includes(name)) {
                    showError(field, 'Ce champ est obligatoire.');
                } else {
                    clearFeedback(field);
                }
                return;
            }

            // Validation spécifique par nom
            if (name === 'email') {
                validate(field, isValidEmail(v), 'Adresse email invalide.');
            } else if (name === 'nom' || name === 'prenom') {
                validate(field, v.length >= 2, 'Minimum 2 caractères.');
            } else if (name === 'age' && v !== '') {
                const a = parseFloat(v);
                validate(field, !isNaN(a) && a >= 10 && a <= 120, 'Âge entre 10 et 120 ans.');
            } else if (name === 'poids' && v !== '') {
                const p = parseFloat(v);
                validate(field, !isNaN(p) && p >= 20 && p <= 300, 'Poids entre 20 et 300 kg.');
            } else if (name === 'taille' && v !== '') {
                const t = parseFloat(v);
                validate(field, !isNaN(t) && t >= 50 && t <= 250, 'Taille entre 50 et 250 cm.');
            } else if (name === 'password' || name === 'new_password') {
                validate(field, field.value.length >= 6, 'Minimum 6 caractères.');
            } else if (v !== '') {
                showSuccess(field);
            }
        });

        // Indicateur de force en live pour les mots de passe
        const name = field.getAttribute('name') || '';
        if (name === 'password' || name === 'new_password') {
            field.addEventListener('input', () => renderPasswordStrength(field));
        }
    });
}

/* ═══════════════════════════════════════════════════════════
   INSCRIPTION (page dédiée)
   ═══════════════════════════════════════════════════════════ */
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

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,           '⚠ Nom : 2 caractères minimum.')     && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,        '⚠ Prénom : 2 caractères minimum.')  && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),              '⚠ Adresse email invalide.')          && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,                  '⚠ Mot de passe : 6 caractères min.') && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, '⚠ Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   CONNEXION (page dédiée)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=login"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const email = this.querySelector('[name="email"]');
        const pwd   = this.querySelector('[name="password"]');

        if (email) valid = validate(email, isValidEmail(email.value), '⚠ Adresse email invalide.')    && valid;
        if (pwd)   valid = validate(pwd,   pwd.value.trim() !== '',   '⚠ Mot de passe obligatoire.')  && valid;
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   MOT DE PASSE OUBLIÉ (page dédiée)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=forgot_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const email = this.querySelector('[name="email"]');
        if (email && validate(email, isValidEmail(email.value), '⚠ Adresse email invalide.')) {
            this.submit();
        }
    });
});

/* ═══════════════════════════════════════════════════════════
   RÉINITIALISATION MOT DE PASSE (page dédiée)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=reset_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const pwd     = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,           '⚠ Mot de passe : 6 caractères min.')     && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, '⚠ Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   CHANGER MOT DE PASSE (paramètres)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=update_password"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const current = this.querySelector('[name="current_password"]');
        const newPwd  = this.querySelector('[name="new_password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (current) valid = validate(current, current.value.trim() !== '',    '⚠ Mot de passe actuel obligatoire.')         && valid;
        if (newPwd)  valid = validate(newPwd,  newPwd.value.length >= 6,       '⚠ Nouveau mot de passe : 6 caractères min.') && valid;
        if (confirm && newPwd) {
            valid = validate(confirm, confirm.value === newPwd.value, '⚠ Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   MODIFIER PROFIL (paramètres)
   ═══════════════════════════════════════════════════════════ */
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

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  '⚠ Nom : 2 caractères minimum.')    && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'⚠ Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     '⚠ Adresse email invalide.')         && valid;

        if (age && age.value !== '') {
            const a = parseFloat(age.value);
            valid = validate(age, !isNaN(a) && a >= 10 && a <= 120, '⚠ Âge : entre 10 et 120 ans.') && valid;
        }
        if (poids && poids.value !== '') {
            const p = parseFloat(poids.value);
            valid = validate(poids, !isNaN(p) && p >= 20 && p <= 300, '⚠ Poids : entre 20 et 300 kg.') && valid;
        }
        if (taille && taille.value !== '') {
            const t = parseFloat(taille.value);
            valid = validate(taille, !isNaN(t) && t >= 50 && t <= 250, '⚠ Taille : entre 50 et 250 cm.') && valid;
        }
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   AJOUTER UTILISATEUR (admin)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=add_user"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom    = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email  = this.querySelector('[name="email"]');
        const pwd    = this.querySelector('[name="password"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  '⚠ Nom : 2 caractères minimum.')    && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'⚠ Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     '⚠ Adresse email invalide.')         && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,         '⚠ Mot de passe : 6 caractères min.') && valid;
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   MODIFIER UTILISATEUR (admin)
   ═══════════════════════════════════════════════════════════ */
document.querySelectorAll('form[action*="action=update_user"]').forEach(form => {
    attachLiveValidation(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom    = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email  = this.querySelector('[name="email"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,  '⚠ Nom : 2 caractères minimum.')    && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,'⚠ Prénom : 2 caractères minimum.') && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),     '⚠ Adresse email invalide.')         && valid;
        if (valid) this.submit();
    });
});

/* ═══════════════════════════════════════════════════════════
   MODALES HOME.PHP — Inscription
   ═══════════════════════════════════════════════════════════ */
const registerFormModal = document.getElementById('registerForm');
if (registerFormModal) {
    attachLiveValidation(registerFormModal);
    registerFormModal.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const nom     = this.querySelector('[name="nom"]');
        const prenom  = this.querySelector('[name="prenom"]');
        const email   = this.querySelector('[name="email"]');
        const pwd     = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');

        if (nom)    valid = validate(nom,    nom.value.trim().length >= 2,           '⚠ Nom requis (2 car. min).')          && valid;
        if (prenom) valid = validate(prenom, prenom.value.trim().length >= 2,        '⚠ Prénom requis (2 car. min).')       && valid;
        if (email)  valid = validate(email,  isValidEmail(email.value),              '⚠ Email invalide.')                   && valid;
        if (pwd)    valid = validate(pwd,    pwd.value.length >= 6,                  '⚠ Mot de passe ≥ 6 caractères.')      && valid;
        if (confirm && pwd) {
            valid = validate(confirm, confirm.value === pwd.value, '⚠ Les mots de passe ne correspondent pas.') && valid;
        }
        if (valid) this.submit();
    });
}

/* ═══════════════════════════════════════════════════════════
   MODALES HOME.PHP — Connexion
   ═══════════════════════════════════════════════════════════ */
const loginFormModal = document.getElementById('loginForm');
if (loginFormModal) {
    attachLiveValidation(loginFormModal);
    loginFormModal.addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;
        const email = this.querySelector('[name="email"]');
        const pwd   = this.querySelector('[name="password"]');

        if (email) valid = validate(email, isValidEmail(email.value), '⚠ Email invalide.')          && valid;
        if (pwd)   valid = validate(pwd,   pwd.value.trim() !== '',   '⚠ Mot de passe obligatoire.') && valid;
        if (valid) this.submit();
    });
}

/* ═══════════════════════════════════════════════════════════
   MODALES HOME.PHP — Mot de passe oublié (AJAX)
   ═══════════════════════════════════════════════════════════ */
const forgotFormModal = document.getElementById('forgotForm');
if (forgotFormModal && !forgotFormModal.hasAttribute('action')) {
    const emailInput = document.getElementById('forgotEmail');
    forgotFormModal.addEventListener('submit', function (e) {
        if (emailInput && !isValidEmail(emailInput.value)) {
            e.preventDefault();
            showError(emailInput, '⚠ Adresse email invalide.');
        }
    });
}