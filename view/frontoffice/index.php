<?php
// frontoffice/index.php — Vue Front Office: Gestion Calories & Régime
require_once __DIR__ . '/../../controller/IngredientController.php';

// Récupérer tous les ingrédients pour le <select> via le contrôleur
$ctrl = new IngredientController();
$ingredients = $ctrl->listIngredients();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Modern Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/front-style.css">
</head>
<body class="front-page">

    <!-- Subtle Background Animation Blobs -->
    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar du Front Office -->
        <nav class="sidebar">
            <div class="logo">Smart<span>Food</span></div>
            <ul class="nav-links">
                <li><a href="#">Tableau de bord</a></li>
                <li><a href="#">Gestion Recettes</a></li>
                <li class="active"><a href="index.php">Gestion Calories</a></li>
                <li><a href="#">Paramètres</a></li>
                
                <li class="switch-mode"><a href="../backoffice/index.php" class="admin-link">⚙️ Accès Back Office</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="content-header">
                <h1>Dashboard <span>Nutrition</span></h1>
                <p>Suivez votre alimentation avec une interface nouvelle génération.</p>
            </header>

            <div class="bento-grid">
                <!-- Section Profil & Objectifs -->
                <section class="card profile-section bento-item-large">
                    <div class="section-header">
                        <h3>Mon Profil & Objectifs</h3>
                        <span class="badge-status">Paramètres</span>
                    </div>
                    
                    <form id="profile-form" novalidate>
                        <div class="profile-grid">
                            <div class="input-group">
                                <label>Genre</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="gender" value="male" checked> H
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="gender" value="female"> F
                                    </label>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="height-input">Taille (cm)</label>
                                <input type="text" id="height-input" placeholder="175">
                                <span class="error-msg" id="error-height"></span>
                            </div>

                            <div class="input-group">
                                <label for="weight-user-input">Poids (kg)</label>
                                <input type="text" id="weight-user-input" placeholder="75">
                                <span class="error-msg" id="error-weight-user"></span>
                            </div>

                            <div class="input-group">
                                <label for="goal-select">Objectif</label>
                                <select id="goal-select">
                                    <option value="" disabled selected>Objectif...</option>
                                    <option value="lose_fat">Perte de gras</option>
                                    <option value="cut">Sèche</option>
                                    <option value="bulk">Prise de masse</option>
                                    <option value="maintain">Maintien</option>
                                </select>
                                <span class="error-msg" id="error-goal"></span>
                            </div>
                        </div>

                        <div class="profile-action">
                            <button type="button" id="save-profile-btn" class="btn-secondary">Mise à jour</button>
                        </div>
                    </form>
                </section>

                <!-- Affichage des macros calculés -->
                <div class="target-macros bento-item-macros">
                    <div class="macro-box">
                        <span class="macro-icon">🎯</span>
                        <div class="macro-text">
                            <span class="macro-label">Calories</span>
                            <strong id="target-cals">--</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🥩</span>
                        <div class="macro-text">
                            <span class="macro-label">Prot.</span>
                            <strong id="target-prot">--</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🌾</span>
                        <div class="macro-text">
                            <span class="macro-label">Glucide</span>
                            <strong id="target-gluc">--</strong>
                        </div>
                    </div>
                    <div class="macro-box">
                        <span class="macro-icon">🥑</span>
                        <div class="macro-text">
                            <span class="macro-label">Lipide</span>
                            <strong id="target-lip">--</strong>
                        </div>
                    </div>
                </div>

                <!-- Section Ajouter un repas -->
                <section class="card form-section bento-item-form">
                    <h3>Ajouter un repas</h3>
                    <form id="meal-form" novalidate>
                        <div class="stack-layout">
                            <div class="input-group">
                                <label for="food-select">Aliment</label>
                                <select id="food-select">
                                    <option value="" disabled selected>Sélectionner...</option>
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
                                <label for="weight-input">Poids (g)</label>
                                <input type="text" id="weight-input" placeholder="Ex: 150">
                                <span class="error-msg" id="error-weight"></span>
                            </div>
                            
                            <button type="button" id="add-btn" class="btn-primary">Ajouter</button>
                        </div>
                    </form>
                </section>

                <!-- Journal de consommation -->
                <section class="card table-section bento-item-table">
                    <div class="table-header">
                        <h3>Mon Journal</h3>
                        <span class="date-badge" id="today-date">Aujourd'hui</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="smart-table">
                            <thead>
                                <tr>
                                    <th>Aliment</th>
                                    <th>Poids</th>
                                    <th>Cals</th>
                                    <th>Prot</th>
                                    <th>Gluc</th>
                                    <th>Lip</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2">TTL</td>
                                    <td id="total-cals">0</td>
                                    <td id="total-prot">0</td>
                                    <td id="total-gluc">0</td>
                                    <td id="total-lip">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div> <!-- End bento-grid -->
        </main>
    </div>

    <script src="../js/validation.js"></script>
    <script>
        // Keep existing JS logic
        document.getElementById('save-profile-btn').addEventListener('click', function() {
            var valid = true;
            var height = document.getElementById('height-input').value.trim();
            var weight = document.getElementById('weight-user-input').value.trim();
            var goal = document.getElementById('goal-select').value;
            clearError('error-height'); clearError('error-weight-user'); clearError('error-goal');
            if (!height || isNaN(height) || parseFloat(height) < 100 || parseFloat(height) > 250) { showError('error-height', 'Invalide'); valid = false; }
            if (!weight || isNaN(weight) || parseFloat(weight) < 30 || parseFloat(weight) > 300) { showError('error-weight-user', 'Invalide'); valid = false; }
            if (!goal) { showError('error-goal', 'Invalide'); valid = false; }
            if (!valid) return;
            var h = parseFloat(height); var w = parseFloat(weight); var gender = document.querySelector('input[name="gender"]:checked').value;
            var bmr = (gender === 'male') ? (10 * w + 6.25 * h - 5 * 25 + 5) : (10 * w + 6.25 * h - 5 * 25 - 161);
            var tdee = bmr * 1.55;
            var targetCals; switch (goal) {
                case 'lose_fat': targetCals = tdee - 500; break;
                case 'cut': targetCals = tdee - 300; break;
                case 'bulk': targetCals = tdee + 400; break;
                case 'maintain': targetCals = tdee; break;
            }
            targetCals = Math.round(targetCals);
            var protGrams = Math.round(w * 2);
            var fatGrams = Math.round((targetCals * 0.25) / 9);
            var carbGrams = Math.round((targetCals - (protGrams * 4) - (fatGrams * 9)) / 4);
            document.getElementById('target-cals').textContent = targetCals + ' kcal';
            document.getElementById('target-prot').textContent = protGrams + ' g';
            document.getElementById('target-gluc').textContent = carbGrams + ' g';
            document.getElementById('target-lip').textContent = fatGrams + ' g';
        });

        var journal = [];
        document.getElementById('add-btn').addEventListener('click', function() {
            var valid = true; clearError('error-food'); clearError('error-weight');
            var foodSelect = document.getElementById('food-select');
            var weightInput = document.getElementById('weight-input');
            if (!foodSelect.value) { showError('error-food', 'Invalide'); valid = false; }
            var poids = weightInput.value.trim();
            if (!poids || isNaN(poids) || parseFloat(poids) <= 0) { showError('error-weight', 'Invalide'); valid = false; }
            if (!valid) return;
            var selected = foodSelect.options[foodSelect.selectedIndex];
            var ratio = parseFloat(poids) / 100;
            var entry = {
                nom: selected.getAttribute('data-nom'), poids: parseFloat(poids),
                cals: Math.round(parseFloat(selected.getAttribute('data-cals')) * ratio * 10) / 10,
                prot: Math.round(parseFloat(selected.getAttribute('data-prot')) * ratio * 10) / 10,
                gluc: Math.round(parseFloat(selected.getAttribute('data-gluc')) * ratio * 10) / 10,
                lip: Math.round(parseFloat(selected.getAttribute('data-lip')) * ratio * 10) / 10
            };
            journal.push(entry); renderJournal();
            foodSelect.selectedIndex = 0; weightInput.value = '';
        });

        function renderJournal() {
            var tbody = document.getElementById('table-body');
            tbody.innerHTML = '';
            var tc = 0, tp = 0, tg = 0, tl = 0;
            journal.forEach(function(entry, index) {
                tc += entry.cals; tp += entry.prot; tg += entry.gluc; tl += entry.lip;
                var tr = document.createElement('tr');
                tr.innerHTML = '<td><strong>' + escapeHtml(entry.nom) + '</strong></td><td>' + entry.poids + 'g</td><td>' + entry.cals + '</td><td>' + entry.prot + '</td><td>' + entry.gluc + '</td><td>' + entry.lip + '</td><td class="actions"><button class="btn-delete" onclick="removeEntry(' + index + ')">🗑️</button></td>';
                tbody.appendChild(tr);
            });
            document.getElementById('total-cals').textContent = Math.round(tc * 10) / 10;
            document.getElementById('total-prot').textContent = Math.round(tp * 10) / 10;
            document.getElementById('total-gluc').textContent = Math.round(tg * 10) / 10;
            document.getElementById('total-lip').textContent = Math.round(tl * 10) / 10;
        }

        function removeEntry(index) { journal.splice(index, 1); renderJournal(); }
        function escapeHtml(text) { var div = document.createElement('div'); div.appendChild(document.createTextNode(text)); return div.innerHTML; }
        var today = new Date(); var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('today-date').textContent = today.toLocaleDateString('fr-FR', options);
    </script>

</body>
</html>
