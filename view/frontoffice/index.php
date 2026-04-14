<?php
// frontoffice/index.php — Vue Front Office: Gestion Calories & Régime
require_once __DIR__ . '/../../model/Ingredient.php';

// Récupérer tous les ingrédients pour le <select>
$ingredientModel = new Ingredient();
$ingredients = $ingredientModel->getAllIngredients();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Gestion Calories & Régime</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

    <div class="dashboard-container">
        <!-- Sidebar du Front Office -->
        <nav class="sidebar">
            <div class="logo">Smart<span>Food</span></div>
            <ul class="nav-links">
                <li><a href="#">Tableau de bord</a></li>
                <li><a href="#">Gestion Recettes</a></li>
                <li class="active"><a href="index.php">Gestion Calories</a></li>
                <li><a href="#">Paramètres</a></li>
                
                <!-- Lien pour basculer vers le Back Office -->
                <li class="switch-mode"><a href="../backoffice/index.php" class="admin-link">⚙️ Accès Back Office</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="content-header">
                <h1>Gestion Calories & Régime</h1>
                <p>Suivez votre alimentation et contrôlez vos macros facilement.</p>
            </header>

            <!-- Section Profil & Objectifs -->
            <section class="card profile-section">
                <div class="section-header">
                    <h3>Mon Profil & Objectifs</h3>
                    <span class="badge-status">Paramètres de base</span>
                </div>
                
                <form id="profile-form" novalidate>
                    <div class="profile-grid">
                        <div class="input-group">
                            <label>Genre</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="male" checked> Homme
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="female"> Femme
                                </label>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="height-input">Taille (cm)</label>
                            <input type="text" id="height-input" placeholder="Ex: 175">
                            <span class="error-msg" id="error-height"></span>
                        </div>

                        <div class="input-group">
                            <label for="weight-user-input">Poids actuel (kg)</label>
                            <input type="text" id="weight-user-input" placeholder="Ex: 75">
                            <span class="error-msg" id="error-weight-user"></span>
                        </div>

                        <div class="input-group">
                            <label for="goal-select">Objectif Physique</label>
                            <select id="goal-select">
                                <option value="" disabled selected>Choisir un objectif...</option>
                                <option value="lose_fat">Perdre du gras (Déficit calorique)</option>
                                <option value="cut">Sèche musculaire (Cut)</option>
                                <option value="bulk">Prise de masse (Bulk)</option>
                                <option value="maintain">Maintien du poids</option>
                            </select>
                            <span class="error-msg" id="error-goal"></span>
                        </div>
                    </div>

                    <div class="profile-action">
                        <button type="button" id="save-profile-btn" class="btn-secondary">Calculer mes besoins journaliers</button>
                    </div>
                </form>

                <!-- Affichage des macros calculés -->
                <div class="target-macros">
                    <div class="macro-box">
                        <span class="macro-icon">🎯</span>
                        <div class="macro-text">
                            <span class="macro-label">Objectif Calories</span>
                            <strong id="target-cals">-- kcal</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🥩</span>
                        <div class="macro-text">
                            <span class="macro-label">Protéines</span>
                            <strong id="target-prot">-- g</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🌾</span>
                        <div class="macro-text">
                            <span class="macro-label">Glucides</span>
                            <strong id="target-gluc">-- g</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🥑</span>
                        <div class="macro-text">
                            <span class="macro-label">Lipides</span>
                            <strong id="target-lip">-- g</strong>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Ajouter un repas -->
            <section class="card form-section">
                <h3>Ajouter à mon repas</h3>
                <form id="meal-form" novalidate>
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="food-select">Aliment</label>
                            <select id="food-select">
                                <option value="" disabled selected>Choisir un ingrédient...</option>
                                <?php foreach ($ingredients as $ing): ?>
                                <option value="<?php echo $ing['idIngredient']; ?>"
                                        data-nom="<?php echo htmlspecialchars($ing['nom']); ?>"
                                        data-cals="<?php echo $ing['calories']; ?>"
                                        data-prot="<?php echo $ing['proteines']; ?>"
                                        data-gluc="<?php echo $ing['glucides']; ?>"
                                        data-lip="<?php echo $ing['lipides']; ?>">
                                    <?php echo htmlspecialchars($ing['nom']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-msg" id="error-food"></span>
                        </div>
                        
                        <div class="input-group">
                            <label for="weight-input">Poids (en grammes)</label>
                            <input type="text" id="weight-input" placeholder="Ex: 150">
                            <span class="error-msg" id="error-weight"></span>
                        </div>
                        
                        <div class="input-group btn-container">
                            <button type="button" id="add-btn" class="btn-primary">Calculer & Ajouter</button>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Journal de consommation -->
            <section class="card table-section">
                <div class="table-header">
                    <h3>Journal de consommation</h3>
                    <span class="date-badge" id="today-date">Aujourd'hui</span>
                </div>
                
                <div class="table-responsive">
                    <table class="smart-table">
                        <thead>
                            <tr>
                                <th>Aliment</th>
                                <th>Poids (g)</th>
                                <th>Cals (kcal)</th>
                                <th>Prot (g)</th>
                                <th>Gluc (g)</th>
                                <th>Lip (g)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Les lignes seront ajoutées dynamiquement via JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="2">TOTAL JOURNALIER</td>
                                <td id="total-cals">0 kcal</td>
                                <td id="total-prot">0g</td>
                                <td id="total-gluc">0g</td>
                                <td id="total-lip">0g</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="../../js/validation.js"></script>
    <script>
        // ========================
        // PROFIL: Calcul des besoins journaliers (Mifflin-St Jeor)
        // ========================
        document.getElementById('save-profile-btn').addEventListener('click', function() {
            // Validation du profil
            var valid = true;

            var height = document.getElementById('height-input').value.trim();
            var weight = document.getElementById('weight-user-input').value.trim();
            var goal = document.getElementById('goal-select').value;

            // Reset errors
            clearError('error-height');
            clearError('error-weight-user');
            clearError('error-goal');

            if (!height || isNaN(height) || parseFloat(height) < 100 || parseFloat(height) > 250) {
                showError('error-height', 'Entrez une taille valide (100-250 cm)');
                valid = false;
            }
            if (!weight || isNaN(weight) || parseFloat(weight) < 30 || parseFloat(weight) > 300) {
                showError('error-weight-user', 'Entrez un poids valide (30-300 kg)');
                valid = false;
            }
            if (!goal) {
                showError('error-goal', 'Veuillez choisir un objectif');
                valid = false;
            }

            if (!valid) return;

            var h = parseFloat(height);
            var w = parseFloat(weight);
            var gender = document.querySelector('input[name="gender"]:checked').value;

            // Formule de Mifflin-St Jeor (BMR)
            var bmr;
            if (gender === 'male') {
                bmr = 10 * w + 6.25 * h - 5 * 25 + 5; // Âge estimé 25 ans
            } else {
                bmr = 10 * w + 6.25 * h - 5 * 25 - 161;
            }

            // TDEE (activité modérée x1.55)
            var tdee = bmr * 1.55;

            // Ajustement selon l'objectif
            var targetCals;
            switch (goal) {
                case 'lose_fat': targetCals = tdee - 500; break;
                case 'cut': targetCals = tdee - 300; break;
                case 'bulk': targetCals = tdee + 400; break;
                case 'maintain': targetCals = tdee; break;
            }

            targetCals = Math.round(targetCals);

            // Répartition macros
            var protGrams = Math.round(w * 2);        // 2g/kg
            var fatGrams = Math.round((targetCals * 0.25) / 9); // 25% des cals
            var carbGrams = Math.round((targetCals - (protGrams * 4) - (fatGrams * 9)) / 4);

            // Affichage
            document.getElementById('target-cals').textContent = targetCals + ' kcal';
            document.getElementById('target-prot').textContent = protGrams + ' g';
            document.getElementById('target-gluc').textContent = carbGrams + ' g';
            document.getElementById('target-lip').textContent = fatGrams + ' g';

            // Animation de mise en avant
            document.querySelectorAll('.macro-box').forEach(function(box) {
                box.style.transition = 'transform 0.3s, box-shadow 0.3s';
                box.style.transform = 'scale(1.05)';
                box.style.boxShadow = '0 4px 15px rgba(45, 106, 79, 0.15)';
                setTimeout(function() {
                    box.style.transform = 'scale(1)';
                    box.style.boxShadow = 'none';
                }, 600);
            });
        });

        // ========================
        // REPAS: Ajouter un aliment au journal
        // ========================
        var journal = [];

        document.getElementById('add-btn').addEventListener('click', function() {
            var valid = true;
            clearError('error-food');
            clearError('error-weight');

            var foodSelect = document.getElementById('food-select');
            var weightInput = document.getElementById('weight-input');

            if (!foodSelect.value) {
                showError('error-food', 'Veuillez sélectionner un aliment');
                valid = false;
            }

            var poids = weightInput.value.trim();
            if (!poids || isNaN(poids) || parseFloat(poids) <= 0) {
                showError('error-weight', 'Entrez un poids valide (> 0g)');
                valid = false;
            }

            if (!valid) return;

            var selected = foodSelect.options[foodSelect.selectedIndex];
            var poidsNum = parseFloat(poids);
            var ratio = poidsNum / 100;

            var entry = {
                nom: selected.getAttribute('data-nom'),
                poids: poidsNum,
                cals: Math.round(parseFloat(selected.getAttribute('data-cals')) * ratio * 10) / 10,
                prot: Math.round(parseFloat(selected.getAttribute('data-prot')) * ratio * 10) / 10,
                gluc: Math.round(parseFloat(selected.getAttribute('data-gluc')) * ratio * 10) / 10,
                lip: Math.round(parseFloat(selected.getAttribute('data-lip')) * ratio * 10) / 10
            };

            journal.push(entry);
            renderJournal();

            // Reset
            foodSelect.selectedIndex = 0;
            weightInput.value = '';
        });

        function renderJournal() {
            var tbody = document.getElementById('table-body');
            tbody.innerHTML = '';

            var totalCals = 0, totalProt = 0, totalGluc = 0, totalLip = 0;

            journal.forEach(function(entry, index) {
                totalCals += entry.cals;
                totalProt += entry.prot;
                totalGluc += entry.gluc;
                totalLip += entry.lip;

                var tr = document.createElement('tr');
                tr.innerHTML = 
                    '<td><strong>' + escapeHtml(entry.nom) + '</strong></td>' +
                    '<td>' + entry.poids + 'g</td>' +
                    '<td>' + entry.cals + '</td>' +
                    '<td>' + entry.prot + '</td>' +
                    '<td>' + entry.gluc + '</td>' +
                    '<td>' + entry.lip + '</td>' +
                    '<td class="actions">' +
                        '<button class="btn-delete" onclick="removeEntry(' + index + ')" title="Supprimer">🗑️</button>' +
                    '</td>';
                tbody.appendChild(tr);
            });

            document.getElementById('total-cals').textContent = Math.round(totalCals * 10) / 10 + ' kcal';
            document.getElementById('total-prot').textContent = Math.round(totalProt * 10) / 10 + 'g';
            document.getElementById('total-gluc').textContent = Math.round(totalGluc * 10) / 10 + 'g';
            document.getElementById('total-lip').textContent = Math.round(totalLip * 10) / 10 + 'g';
        }

        function removeEntry(index) {
            journal.splice(index, 1);
            renderJournal();
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        // Afficher la date d'aujourd'hui
        var today = new Date();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('today-date').textContent = today.toLocaleDateString('fr-FR', options);
    </script>

</body>
</html>
