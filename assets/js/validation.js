function showError(input, message) {
    removeError(input);
    input.style.borderColor = '#e74c3c';
    const err = document.createElement('div');
    err.className = 'js-error';
    err.style.cssText = 'color:#e74c3c; font-size:0.85rem; margin-top:5px;';
    err.textContent = message;
    input.parentNode.appendChild(err);
}
function removeError(input) {
    input.style.borderColor = '';
    const existing = input.parentNode.querySelector('.js-error');
    if (existing) existing.remove();
}
function showSuccess(input) {
    removeError(input);
    input.style.borderColor = '#2D6A4F';
}
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

// Inscription
const registerForm = document.querySelector('form[action*="action=register"]');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const nom = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email = this.querySelector('[name="email"]');
        const pwd = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');
        if (nom.value.trim().length < 2) { showError(nom, 'Nom ≥ 2 caractères'); valid = false; } else showSuccess(nom);
        if (prenom.value.trim().length < 2) { showError(prenom, 'Prénom ≥ 2 caractères'); valid = false; } else showSuccess(prenom);
        if (!isValidEmail(email.value)) { showError(email, 'Email invalide'); valid = false; } else showSuccess(email);
        if (pwd.value.length < 6) { showError(pwd, 'Mot de passe ≥ 6 caractères'); valid = false; } else showSuccess(pwd);
        if (confirm.value !== pwd.value) { showError(confirm, 'Mots de passe différents'); valid = false; } else if (pwd.value.length >= 6) showSuccess(confirm);
        if (valid) this.submit();
    });
}

// Connexion
const loginForm = document.querySelector('form[action*="action=login"]');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const email = this.querySelector('[name="email"]');
        const pwd = this.querySelector('[name="password"]');
        if (!isValidEmail(email.value)) { showError(email, 'Email valide requis'); valid = false; } else showSuccess(email);
        if (pwd.value.trim() === '') { showError(pwd, 'Mot de passe obligatoire'); valid = false; } else showSuccess(pwd);
        if (valid) this.submit();
    });
}

// Mot de passe oublié
const forgotForm = document.querySelector('form[action*="action=forgot_password"]');
if (forgotForm) {
    forgotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('[name="email"]');
        if (!isValidEmail(email.value)) { showError(email, 'Email invalide'); return; }
        this.submit();
    });
}

// Reset password
const resetForm = document.querySelector('form[action*="action=reset_password"]');
if (resetForm) {
    resetForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const pwd = this.querySelector('[name="password"]');
        const confirm = this.querySelector('[name="confirm_password"]');
        if (pwd.value.length < 6) { showError(pwd, '≥ 6 caractères'); return; }
        if (pwd.value !== confirm.value) { showError(confirm, 'Mots de passe différents'); return; }
        this.submit();
    });
}

// Modifier profil
const profileForm = document.querySelector('form[action*="action=update_profile"]');
if (profileForm) {
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const nom = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email = this.querySelector('[name="email"]');
        const age = this.querySelector('[name="age"]');
        const poids = this.querySelector('[name="poids"]');
        const taille = this.querySelector('[name="taille"]');
        if (nom && nom.value.trim().length < 2) { showError(nom, 'Nom ≥ 2'); valid = false; }
        if (prenom && prenom.value.trim().length < 2) { showError(prenom, 'Prénom ≥ 2'); valid = false; }
        if (email && !isValidEmail(email.value)) { showError(email, 'Email invalide'); valid = false; }
        if (age && (isNaN(age.value) || age.value < 10 || age.value > 120)) { showError(age, 'Âge 10-120'); valid = false; }
        if (poids && (isNaN(poids.value) || poids.value < 20 || poids.value > 300)) { showError(poids, 'Poids 20-300 kg'); valid = false; }
        if (taille && (isNaN(taille.value) || taille.value < 50 || taille.value > 250)) { showError(taille, 'Taille 50-250 cm'); valid = false; }
        if (valid) this.submit();
    });
}

// Changer mot de passe
const changePwdForm = document.querySelector('form[action*="action=update_password"]');
if (changePwdForm) {
    changePwdForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const current = this.querySelector('[name="current_password"]');
        const newPwd = this.querySelector('[name="new_password"]');
        const confirm = this.querySelector('[name="confirm_password"]');
        if (current.value.trim() === '') { showError(current, 'Mot de passe actuel requis'); return; }
        if (newPwd.value.length < 6) { showError(newPwd, '≥ 6 caractères'); return; }
        if (newPwd.value !== confirm.value) { showError(confirm, 'Mots de passe différents'); return; }
        this.submit();
    });
}

// Admin add user
const addUserForm = document.querySelector('form[action*="action=add_user"]');
if (addUserForm) {
    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const nom = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email = this.querySelector('[name="email"]');
        const pwd = this.querySelector('[name="password"]');
        if (nom.value.trim().length < 2) { showError(nom, 'Nom requis'); valid = false; }
        if (prenom.value.trim().length < 2) { showError(prenom, 'Prénom requis'); valid = false; }
        if (!isValidEmail(email.value)) { showError(email, 'Email invalide'); valid = false; }
        if (pwd.value.length < 6) { showError(pwd, 'Mot de passe ≥ 6'); valid = false; }
        if (valid) this.submit();
    });
}

// Admin edit user
const editUserForm = document.querySelector('form[action*="action=update_user"]');
if (editUserForm) {
    editUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const nom = this.querySelector('[name="nom"]');
        const prenom = this.querySelector('[name="prenom"]');
        const email = this.querySelector('[name="email"]');
        if (nom.value.trim().length < 2) { showError(nom, 'Nom requis'); valid = false; }
        if (prenom.value.trim().length < 2) { showError(prenom, 'Prénom requis'); valid = false; }
        if (!isValidEmail(email.value)) { showError(email, 'Email invalide'); valid = false; }
        if (valid) this.submit();
    });
}