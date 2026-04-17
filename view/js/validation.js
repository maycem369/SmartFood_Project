/**
 * validation.js — Contrôle de saisie JavaScript pour SmartFood
 * 
 * IMPORTANT: Aucune validation HTML5 n'est utilisée.
 * Toute la validation est faite ici en JavaScript pur.
 */

// ========================
// FONCTIONS UTILITAIRES
// ========================

/**
 * Affiche un message d'erreur sous un champ
 * @param {string} errorId - L'ID du <span> d'erreur
 * @param {string} message - Le message à afficher
 */
function showError(errorId, message) {
    var errorSpan = document.getElementById(errorId);
    if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.style.display = 'block';

        // Mettre le champ en rouge
        var input = errorSpan.previousElementSibling;
        if (input && (input.tagName === 'INPUT' || input.tagName === 'SELECT')) {
            input.style.borderColor = '#e74c3c';
        }
    }
}

/**
 * Efface un message d'erreur
 * @param {string} errorId - L'ID du <span> d'erreur
 */
function clearError(errorId) {
    var errorSpan = document.getElementById(errorId);
    if (errorSpan) {
        errorSpan.textContent = '';
        errorSpan.style.display = 'none';

        var input = errorSpan.previousElementSibling;
        if (input && (input.tagName === 'INPUT' || input.tagName === 'SELECT')) {
            input.style.borderColor = '#E0E0E0';
        }
    }
}

/**
 * Efface toutes les erreurs d'un formulaire
 * @param {HTMLFormElement} form - Le formulaire
 */
function clearAllErrors(form) {
    var errorSpans = form.querySelectorAll('.error-msg');
    errorSpans.forEach(function(span) {
        span.textContent = '';
        span.style.display = 'none';
    });
    var inputs = form.querySelectorAll('input, select');
    inputs.forEach(function(input) {
        input.style.borderColor = '#E0E0E0';
    });
}

// ========================
// VALIDATION FORMULAIRE INGRÉDIENT (Back Office)
// ========================

/**
 * Valide le formulaire d'ajout/modification d'un ingrédient
 * @param {HTMLFormElement} form - Le formulaire à valider
 * @returns {boolean} true si valide, false sinon
 */
function validateIngredientForm(form) {
    var isValid = true;
    clearAllErrors(form);

    // --- Validation du nom ---
    var nom = form.querySelector('[name="nom"]').value.trim();
    if (!nom) {
        showError('error-nom', 'Le nom de l\'aliment est obligatoire.');
        isValid = false;
    } else if (nom.length < 2) {
        showError('error-nom', 'Le nom debe contenir au moins 2 caractères.');
        isValid = false;
    } else if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(nom)) {
        showError('error-nom', 'Le nom ne doit contenir que des lettres et espaces.');
        isValid = false;
    }

    // --- Validation des calories ---
    var calories = form.querySelector('[name="calories"]').value.trim();
    if (!calories) {
        showError('error-calories', 'Les calories sont obligatoires.');
        isValid = false;
    } else if (isNaN(calories) || parseFloat(calories) < 0) {
        showError('error-calories', 'Entrez un nombre positif valide.');
        isValid = false;
    }

    // --- Validation des protéines ---
    var proteines = form.querySelector('[name="proteines"]').value.trim();
    if (!proteines) {
        showError('error-proteines', 'Les protéines sont obligatoires.');
        isValid = false;
    } else if (isNaN(proteines) || parseFloat(proteines) < 0) {
        showError('error-proteines', 'Entrez un nombre positif valide.');
        isValid = false;
    }

    // --- Validation des glucides ---
    var glucides = form.querySelector('[name="glucides"]').value.trim();
    if (!glucides) {
        showError('error-glucides', 'Les glucides sont obligatoires.');
        isValid = false;
    } else if (isNaN(glucides) || parseFloat(glucides) < 0) {
        showError('error-glucides', 'Entrez un nombre positif valide.');
        isValid = false;
    }

    // --- Validation des lipides ---
    var lipides = form.querySelector('[name="lipides"]').value.trim();
    if (!lipides) {
        showError('error-lipides', 'Les lipides sont obligatoires.');
        isValid = false;
    } else if (isNaN(lipides) || parseFloat(lipides) < 0) {
        showError('error-lipides', 'Entrez un nombre positif valide.');
        isValid = false;
    }

    return isValid;
}

// ========================
// VALIDATION EN TEMPS RÉEL (blur)
// ========================

document.addEventListener('DOMContentLoaded', function() {

    // Validation en temps réel pour les champs du back office
    var ingredientForms = document.querySelectorAll('#admin-add-food, #edit-ingredient-form');
    
    ingredientForms.forEach(function(form) {
        // Nom
        var nomInput = form.querySelector('[name="nom"]');
        if (nomInput) {
            nomInput.addEventListener('blur', function() {
                clearError('error-nom');
                var val = this.value.trim();
                if (!val) {
                    showError('error-nom', 'Le nom de l\'aliment est obligatoire.');
                } else if (val.length < 2) {
                    showError('error-nom', 'Le nom doit contenir au moins 2 caractères.');
                } else if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(val)) {
                    showError('error-nom', 'Le nom ne doit contenir que des lettres et espaces.');
                }
            });

            // Effacer l'erreur quand l'utilisateur tape
            nomInput.addEventListener('input', function() {
                clearError('error-nom');
            });
        }

        // Champs numériques
        var numericFields = [
            { name: 'calories', errorId: 'error-calories', label: 'Les calories' },
            { name: 'proteines', errorId: 'error-proteines', label: 'Les protéines' },
            { name: 'glucides', errorId: 'error-glucides', label: 'Les glucides' },
            { name: 'lipides', errorId: 'error-lipides', label: 'Les lipides' }
        ];

        numericFields.forEach(function(field) {
            var input = form.querySelector('[name="' + field.name + '"]');
            if (input) {
                input.addEventListener('blur', function() {
                    clearError(field.errorId);
                    var val = this.value.trim();
                    if (!val) {
                        showError(field.errorId, field.label + ' sont obligatoires.');
                    } else if (isNaN(val) || parseFloat(val) < 0) {
                        showError(field.errorId, 'Entrez un nombre positif valide.');
                    }
                });

                input.addEventListener('input', function() {
                    clearError(field.errorId);
                });
            }
        });
    });
});
