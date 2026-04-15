// =============================================
// validation.js - Gestion des formulaires Front Office
// =============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ========== FORMULAIRE D'INSCRIPTION ==========
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Nom
            const nom = document.getElementById('nom');
            if (nom && nom.value.trim() === '') {
                errorMessage += 'Le nom est obligatoire.\n';
                isValid = false;
                nom.style.borderColor = '#dc3545';
            } else if (nom) {
                nom.style.borderColor = '#E0E0E0';
            }
            
            // Prénom
            const prenom = document.getElementById('prenom');
            if (prenom && prenom.value.trim() === '') {
                errorMessage += 'Le prénom est obligatoire.\n';
                isValid = false;
                prenom.style.borderColor = '#dc3545';
            } else if (prenom) {
                prenom.style.borderColor = '#E0E0E0';
            }
            
            // Email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email.value)) {
                errorMessage += 'Veuillez entrer une adresse email valide.\n';
                isValid = false;
                email.style.borderColor = '#dc3545';
            } else if (email) {
                email.style.borderColor = '#E0E0E0';
            }
            
            // Mot de passe
            const password = document.getElementById('password');
            if (password && password.value.length < 6) {
                errorMessage += 'Le mot de passe doit contenir au moins 6 caractères.\n';
                isValid = false;
                password.style.borderColor = '#dc3545';
            } else if (password) {
                password.style.borderColor = '#E0E0E0';
            }
            
            // Confirmation mot de passe
            const confirmPassword = document.getElementById('confirm_password');
            if (confirmPassword && confirmPassword.value !== password.value) {
                errorMessage += 'Les mots de passe ne correspondent pas.\n';
                isValid = false;
                confirmPassword.style.borderColor = '#dc3545';
            } else if (confirmPassword) {
                confirmPassword.style.borderColor = '#E0E0E0';
            }
            
            if (!isValid) {
                e.preventDefault();
                showErrorDialog(errorMessage);
            }
        });
        
        // Effet de focus sur les champs
        addFocusEffects(registerForm);
    }
    
    // ========== FORMULAIRE DE CONNEXION ==========
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email.value)) {
                errorMessage += 'Veuillez entrer une adresse email valide.\n';
                isValid = false;
                email.style.borderColor = '#dc3545';
            } else if (email) {
                email.style.borderColor = '#E0E0E0';
            }
            
            const password = document.getElementById('password');
            if (password && password.value === '') {
                errorMessage += 'Le mot de passe est obligatoire.\n';
                isValid = false;
                password.style.borderColor = '#dc3545';
            } else if (password) {
                password.style.borderColor = '#E0E0E0';
            }
            
            if (!isValid) {
                e.preventDefault();
                showErrorDialog(errorMessage);
            }
        });
        
        addFocusEffects(loginForm);
    }
    
    // ========== FORMULAIRE DE MODIFICATION DE PROFIL ==========
    const editProfileForm = document.querySelector('form[action*="update_profile"]');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Nom
            const nom = document.querySelector('input[name="nom"]');
            if (nom && nom.value.trim() === '') {
                errorMessage += 'Le nom ne peut pas être vide.\n';
                isValid = false;
                nom.style.borderColor = '#dc3545';
            }
            
            // Prénom
            const prenom = document.querySelector('input[name="prenom"]');
            if (prenom && prenom.value.trim() === '') {
                errorMessage += 'Le prénom ne peut pas être vide.\n';
                isValid = false;
                prenom.style.borderColor = '#dc3545';
            }
            
            // Email
            const email = document.querySelector('input[name="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email.value)) {
                errorMessage += 'Veuillez entrer une adresse email valide.\n';
                isValid = false;
                email.style.borderColor = '#dc3545';
            }
            
            // Âge
            const age = document.querySelector('input[name="age"]');
            if (age && (age.value < 10 || age.value > 120)) {
                errorMessage += 'L\'âge doit être compris entre 10 et 120 ans.\n';
                isValid = false;
                age.style.borderColor = '#dc3545';
            }
            
            // Poids
            const poids = document.querySelector('input[name="poids"]');
            if (poids && (poids.value < 20 || poids.value > 300)) {
                errorMessage += 'Le poids doit être compris entre 20 et 300 kg.\n';
                isValid = false;
                poids.style.borderColor = '#dc3545';
            }
            
            // Taille
            const taille = document.querySelector('input[name="taille"]');
            if (taille && (taille.value < 50 || taille.value > 250)) {
                errorMessage += 'La taille doit être comprise entre 50 et 250 cm.\n';
                isValid = false;
                taille.style.borderColor = '#dc3545';
            }
            
            if (!isValid) {
                e.preventDefault();
                showErrorDialog(errorMessage);
            }
        });
        
        addFocusEffects(editProfileForm);
    }
    
    // ========== FORMULAIRE DE CHANGEMENT DE MOT DE PASSE ==========
    const changePasswordForm = document.querySelector('form[action*="update_password"]');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            const currentPassword = document.querySelector('input[name="current_password"]');
            const newPassword = document.querySelector('input[name="new_password"]');
            const confirmPassword = document.querySelector('input[name="confirm_password"]');
            
            if (currentPassword && currentPassword.value === '') {
                errorMessage += 'Le mot de passe actuel est obligatoire.\n';
                isValid = false;
                currentPassword.style.borderColor = '#dc3545';
            }
            
            if (newPassword && newPassword.value.length < 6) {
                errorMessage += 'Le nouveau mot de passe doit contenir au moins 6 caractères.\n';
                isValid = false;
                newPassword.style.borderColor = '#dc3545';
            }
            
            if (confirmPassword && confirmPassword.value !== newPassword.value) {
                errorMessage += 'La confirmation du mot de passe ne correspond pas.\n';
                isValid = false;
                confirmPassword.style.borderColor = '#dc3545';
            }
            
            if (!isValid) {
                e.preventDefault();
                showErrorDialog(errorMessage);
            }
        });
        
        addFocusEffects(changePasswordForm);
    }
    
    // ========== VALIDATION DE LA PHOTO DE PROFIL ==========
    const photoInput = document.getElementById('photoInput');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Vérifier le type de fichier
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showErrorDialog('Format de fichier non supporté. Utilisez JPG, PNG ou GIF.');
                    this.value = '';
                    return;
                }
                
                // Vérifier la taille (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showErrorDialog('La photo ne doit pas dépasser 2 Mo.');
                    this.value = '';
                    return;
                }
                
                // Aperçu
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const preview = document.getElementById('preview');
                    if (preview) {
                        preview.src = ev.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // ========== AFFICHAGE/DISPARITION DES MESSAGES ==========
    const messages = document.querySelectorAll('.message');
    messages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s';
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 5000);
    });
    
    // ========== EFFET DE SURVOL SUR LES LIENS DU MENU ==========
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            navLinks.forEach(function(l) {
                l.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // ========== TOOLTIP POUR LES BOUTONS ==========
    const buttons = document.querySelectorAll('.btn, .btn-green, .btn-save, .btn-profile');
    buttons.forEach(function(button) {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // ========== VALIDATION EN TEMPS RÉEL ==========
    const allInputs = document.querySelectorAll('input, select');
    allInputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#FF8C00';
            this.style.boxShadow = '0 0 0 4px rgba(255, 140, 0, 0.12)';
        });
        input.addEventListener('blur', function() {
            this.style.borderColor = '#E0E0E0';
            this.style.boxShadow = 'none';
        });
    });
});

// ========== FONCTIONS UTILITAIRES ==========

/**
 * Affiche une boîte de dialogue d'erreur personnalisée
 */
function showErrorDialog(message) {
    // Supprimer les anciennes notifications
    const oldNotification = document.querySelector('.custom-notification');
    if (oldNotification) {
        oldNotification.remove();
    }
    
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = 'custom-notification';
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 5px solid #dc3545;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 350px;
            animation: slideIn 0.3s ease-out;
        ">
            <strong style="display: block; margin-bottom: 5px;">❌ Erreur</strong>
            <p style="margin: 0;">${message.replace(/\n/g, '<br>')}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Disparition automatique après 5 secondes
    setTimeout(function() {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Affiche un message de succès
 */
function showSuccessMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'custom-notification success';
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 5px solid #28a745;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 350px;
            animation: slideIn 0.3s ease-out;
        ">
            <strong style="display: block; margin-bottom: 5px;">✓ Succès</strong>
            <p style="margin: 0;">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Ajoute des effets de focus sur les champs
 */
function addFocusEffects(form) {
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.2s';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
}

/**
 * Calcule et affiche l'IMC en temps réel
 */
function calculateIMC(poids, taille) {
    if (poids && taille && taille > 0) {
        const tailleMetres = taille / 100;
        const imc = poids / (tailleMetres * tailleMetres);
        return imc.toFixed(1);
    }
    return null;
}

/**
 * Met à jour l'affichage de l'IMC si présent sur la page
 */
function updateIMCDisplay() {
    const poidsInput = document.querySelector('input[name="poids"]');
    const tailleInput = document.querySelector('input[name="taille"]');
    const imcDisplay = document.getElementById('imc-value');
    
    if (poidsInput && tailleInput && imcDisplay) {
        function update() {
            const imc = calculateIMC(parseFloat(poidsInput.value), parseFloat(tailleInput.value));
            if (imc) {
                imcDisplay.textContent = imc;
                
                // Ajouter une classe de couleur selon l'IMC
                if (imc < 18.5) {
                    imcDisplay.style.color = '#FF8C00';
                } else if (imc >= 18.5 && imc <= 24.9) {
                    imcDisplay.style.color = '#2D6A4F';
                } else {
                    imcDisplay.style.color = '#dc3545';
                }
            }
        }
        
        poidsInput.addEventListener('input', update);
        tailleInput.addEventListener('input', update);
        update();
    }
}

// Initialiser le calcul IMC si présent
document.addEventListener('DOMContentLoaded', function() {
    updateIMCDisplay();
});