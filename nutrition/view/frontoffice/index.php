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
    <script src="/smartfoodMVC/assets/js/settings.js"></script>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/front-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/theme.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="front-page">

    <!-- Subtle Background Animation Blobs -->
    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="dashboard-container">
        <main class="main-content">
            <header class="content-header">
                <h1 data-i18n="nut_front_title">Dashboard <span>Nutrition</span></h1>
                <p data-i18n="nut_front_sub">Suivez votre alimentation avec une interface nouvelle génération.</p>
            </header>

            <div class="bento-grid">
                <!-- Section Profil & Objectifs -->
                <section class="card profile-section bento-item-large">
                    <div class="section-header">
                        <h3 data-i18n="nut_profil_title">Mon Profil & Objectifs</h3>
                        <span class="badge-status" data-i18n="nut_profil_params">Paramètres</span>
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
                            <button type="button" id="save-profile-btn" class="btn-secondary" data-i18n="nut_profil_update">Mise à jour</button>
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
                    <h3 data-i18n="nut_meal_title">Ajouter un repas</h3>
                    <form id="meal-form" novalidate>
                        <div class="stack-layout">
                            <div class="input-group">
                                <label for="food-select" data-i18n="nut_meal_food">Aliment</label>
                                <select id="food-select">
                                    <option value="" disabled selected>Sélectionner...</option>
                                    <?php foreach ($ingredients as $ing): ?>
                                    <option value="<?php echo $ing['idIngredient']; ?>"
                                            data-nom="<?php echo htmlspecialchars($ing['nom']); ?>"
                                            data-cals="<?php echo floatval($ing['calories']  ?? 0); ?>"
                                            data-prot="<?php echo floatval($ing['proteines'] ?? 0); ?>"
                                            data-gluc="<?php echo floatval($ing['glucides']  ?? 0); ?>"
                                            data-lip="<?php echo floatval($ing['lipides']    ?? 0); ?>">
                                        <?php echo htmlspecialchars($ing['nom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-msg" id="error-food"></span>
                            </div>
                            
                            <div class="input-group">
                                <label for="weight-input" data-i18n="nut_meal_weight">Poids (g)</label>
                                <input type="text" id="weight-input" placeholder="Ex: 150">
                                <span class="error-msg" id="error-weight"></span>
                            </div>
                            
                            <button type="button" id="add-btn" class="btn-primary" data-i18n="nut_meal_add">Ajouter</button>
                        </div>
                    </form>
                </section>

                <!-- Journal de consommation -->
                <section class="card table-section bento-item-table">
                    <div class="table-header">
                        <h3 data-i18n="nut_journal_title">Mon Journal</h3>
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

                <!-- Section Statistiques Progression -->
                <section class="card bento-item-stats">
                    <div class="section-header">
                        <h3 data-i18n="nut_progress_title">📊 Ma Progression</h3>
                        <span class="badge-status" id="stats-status" data-i18n="nut_progress_config">Configurez vos objectifs</span>
                    </div>
                    <div class="progress-charts-grid">
                        <div class="progress-chart-card safe-zone">
                            <canvas id="chart-cals" width="120" height="120"></canvas>
                            <div class="chart-label">
                                <span class="chart-percent" id="chart-cals-percent">0%</span>
                                <span class="chart-name">Calories</span>
                                <span class="chart-detail" id="chart-cals-detail">-- restant</span>
                            </div>
                        </div>
                        <div class="progress-chart-card safe-zone">
                            <canvas id="chart-prot" width="120" height="120"></canvas>
                            <div class="chart-label">
                                <span class="chart-percent" id="chart-prot-percent">0%</span>
                                <span class="chart-name">Protéines</span>
                                <span class="chart-detail" id="chart-prot-detail">-- restant</span>
                            </div>
                        </div>
                        <div class="progress-chart-card safe-zone">
                            <canvas id="chart-gluc" width="120" height="120"></canvas>
                            <div class="chart-label">
                                <span class="chart-percent" id="chart-gluc-percent">0%</span>
                                <span class="chart-name">Glucides</span>
                                <span class="chart-detail" id="chart-gluc-detail">-- restant</span>
                            </div>
                        </div>
                        <div class="progress-chart-card safe-zone">
                            <canvas id="chart-lip" width="120" height="120"></canvas>
                            <div class="chart-label">
                                <span class="chart-percent" id="chart-lip-percent">0%</span>
                                <span class="chart-name">Lipides</span>
                                <span class="chart-detail" id="chart-lip-detail">-- restant</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section Suggestion Intelligente -->
                <section class="card bento-item-suggestion">
                    <div class="section-header">
                        <h3 data-i18n="nut_suggestion_title">💡 Suggestion Intelligente</h3>
                    </div>
                    <div id="suggestion-container">
                        <div class="suggestion-card suggestion-empty">
                            <span class="suggestion-icon">🤔</span>
                            <div class="suggestion-text">
                                <strong>En attente</strong>
                                <p>Configurez vos objectifs et ajoutez un repas pour recevoir des recommandations.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section Gamification: 7-Day Streak -->
                <section class="card bento-item-streak">
                    <div class="section-header">
                        <h3 data-i18n="nut_streak_title">🔥 Ma Semaine</h3>
                        <span class="badge-status">Objectif: 7 Jours</span>
                    </div>
                    <div class="streak-container">
                        <div class="streak-days">
                            <!-- JS will generate days here -->
                        </div>
                        <div class="streak-message">
                            Mangez sainement aujourd'hui pour garder votre série !
                        </div>
                    </div>
                </section>

                <!-- Section Hydratation -->
                <section class="card bento-item-water">
                    <div class="section-header">
                        <h3 data-i18n="nut_water_title">💧 Hydratation</h3>
                    </div>
                    <div class="water-tracker-container">
                        <div class="water-glass-wrapper">
                            <div class="water-fill" id="water-fill-level"></div>
                        </div>
                        <div class="water-info">
                            <div class="water-text"><span id="water-current">0</span> ml</div>
                            <div class="water-goal">Objectif: 2500 ml</div>
                            <div class="water-actions">
                                <button type="button" class="btn-water" onclick="addWater(250)">+ 250ml</button>
                                <button type="button" class="btn-water" onclick="addWater(500)">+ 500ml</button>
                            </div>
                        </div>
                    </div>
                </section>

            </div> <!-- End bento-grid -->
        </main>
    </div>

    <script src="../js/theme-toggle.js"></script>
    <script src="../js/validation.js"></script>
    <script src="../js/metier.js"></script>
    <script>
        // === User Goals (shared state) ===
        var userGoals = { cals: 0, prot: 0, gluc: 0, lip: 0 };
        var currentTotals = { cals: 0, prot: 0, gluc: 0, lip: 0 };

        // === localStorage keys ===
        var STORAGE_JOURNAL = 'sf_nut_journal_' + (new Date().toDateString());
        var STORAGE_GOALS   = 'sf_nut_goals';
        var STORAGE_PROFILE = 'sf_nut_profile';
        var STORAGE_WATER   = 'sf_nut_water_' + (new Date().toDateString());

        // === Build ingredients DB from PHP data for suggestions ===
        var ingredientsDB = [];
        var foodSelect = document.getElementById('food-select');
        for (var i = 0; i < foodSelect.options.length; i++) {
            var opt = foodSelect.options[i];
            if (opt.value && opt.getAttribute('data-nom')) {
                ingredientsDB.push({
                    id: opt.value,
                    nom: opt.getAttribute('data-nom'),
                    cals: parseFloat(opt.getAttribute('data-cals')) || 0,
                    prot: parseFloat(opt.getAttribute('data-prot')) || 0,
                    gluc: parseFloat(opt.getAttribute('data-gluc')) || 0,
                    lip: parseFloat(opt.getAttribute('data-lip')) || 0
                });
            }
        }

        // === Profile & Goals Calculation ===
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

            // Store goals and update charts
            userGoals = { cals: targetCals, prot: protGrams, gluc: carbGrams, lip: fatGrams };
            document.getElementById('stats-status').textContent = 'En cours de suivi';
            updateAllCharts(currentTotals, userGoals);
            updateSuggestion(ingredientsDB, currentTotals, userGoals);

            // Sauvegarder objectifs + profil dans localStorage
            try {
                localStorage.setItem(STORAGE_GOALS, JSON.stringify(userGoals));
                localStorage.setItem(STORAGE_PROFILE, JSON.stringify({
                    height: height, weight: weight, goal: goal, gender: gender
                }));
            } catch(e) {}
        });

        // === Meal Journal ===
        var journal = [];

        // Charger le journal du jour depuis localStorage
        try {
            var saved = localStorage.getItem(STORAGE_JOURNAL);
            if (saved) journal = JSON.parse(saved);
        } catch(e) { journal = []; }

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
            journal.push(entry);
            // Sauvegarder dans localStorage
            try { localStorage.setItem(STORAGE_JOURNAL, JSON.stringify(journal)); } catch(e) {}
            renderJournal();
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

            // Update shared totals and refresh charts + suggestion
            currentTotals = { cals: tc, prot: tp, gluc: tg, lip: tl };
            updateAllCharts(currentTotals, userGoals);
            updateSuggestion(ingredientsDB, currentTotals, userGoals);
        }

        function removeEntry(index) {
            journal.splice(index, 1);
            try { localStorage.setItem(STORAGE_JOURNAL, JSON.stringify(journal)); } catch(e) {}
            renderJournal();
        }

        var today = new Date(); var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('today-date').textContent = today.toLocaleDateString('fr-FR', options);

        // === Restaurer le profil et les objectifs sauvegardés ===
        (function restoreProfile() {
            try {
                var savedProfile = localStorage.getItem(STORAGE_PROFILE);
                var savedGoals   = localStorage.getItem(STORAGE_GOALS);
                if (savedProfile) {
                    var p = JSON.parse(savedProfile);
                    if (p.height) document.getElementById('height-input').value = p.height;
                    if (p.weight) document.getElementById('weight-user-input').value = p.weight;
                    if (p.goal)   document.getElementById('goal-select').value = p.goal;
                    if (p.gender) {
                        var radios = document.querySelectorAll('input[name="gender"]');
                        radios.forEach(function(r) { r.checked = (r.value === p.gender); });
                    }
                }
                if (savedGoals) {
                    userGoals = JSON.parse(savedGoals);
                    document.getElementById('target-cals').textContent = userGoals.cals + ' kcal';
                    document.getElementById('target-prot').textContent = userGoals.prot + ' g';
                    document.getElementById('target-gluc').textContent = userGoals.gluc + ' g';
                    document.getElementById('target-lip').textContent  = userGoals.lip  + ' g';
                    document.getElementById('stats-status').textContent = 'En cours de suivi';
                }
            } catch(e) {}
        })();

        // Afficher le journal sauvegardé au chargement
        if (journal.length > 0) renderJournal();

        // === Water Tracker ===
        var waterIntake = 0;
        var waterGoal = 2500;

        // Restaurer l'eau du jour
        try {
            var savedWater = localStorage.getItem(STORAGE_WATER);
            if (savedWater) {
                waterIntake = parseInt(savedWater) || 0;
                document.getElementById('water-current').textContent = waterIntake;
                var pct = Math.min((waterIntake / waterGoal) * 100, 100);
                document.getElementById('water-fill-level').style.height = pct + '%';
            }
        } catch(e) {}

        function addWater(amount) {
            waterIntake += amount;
            var percentage = Math.min((waterIntake / waterGoal) * 100, 100);
            document.getElementById('water-current').textContent = waterIntake;
            document.getElementById('water-fill-level').style.height = percentage + '%';
            // Sauvegarder
            try { localStorage.setItem(STORAGE_WATER, waterIntake); } catch(e) {}
        }

        // === Gamification: 7-Day Streak ===
        function initStreakCalendar() {
            var daysContainer = document.querySelector('.streak-days');
            if (!daysContainer) return;
            
            var dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
            var todayIndex = new Date().getDay();
            
            var html = '';
            // Generate the last 7 days (ending today)
            for (var i = 6; i >= 0; i--) {
                var d = new Date();
                d.setDate(d.getDate() - i);
                var isToday = (i === 0);
                
                // Mock some past data for visual effect (randomly completed)
                var stateClass = '';
                var icon = '–';
                
                if (isToday) {
                    stateClass = 'today';
                } else {
                    // Random past days for visual appeal
                    var rand = Math.random();
                    if (rand > 0.6) {
                        stateClass = 'completed';
                        icon = '✓';
                    } else if (rand > 0.3) {
                        stateClass = 'fire';
                        icon = '🔥';
                    }
                }
                
                html += '<div class="streak-day ' + stateClass + '" id="streak-day-' + i + '">' +
                        '<span class="streak-day-name">' + dayNames[d.getDay()] + '</span>' +
                        '<div class="streak-circle" id="streak-circle-' + i + '">' + icon + '</div>' +
                        '</div>';
            }
            daysContainer.innerHTML = html;
        }
        
        function updateTodayStreak() {
            var circle = document.getElementById('streak-circle-0');
            var dayContainer = document.getElementById('streak-day-0');
            if (!circle || !dayContainer) return;
            
            if (journal.length > 0) {
                // If they've logged at least one meal, it's completed (green check)
                dayContainer.classList.add('completed');
                dayContainer.classList.remove('fire', 'over-limit');
                circle.textContent = '✓';
                
                if (userGoals.cals > 0) {
                    if (currentTotals.cals > (userGoals.cals * 1.1)) {
                        // Exceeded limit significantly (> 110%)
                        dayContainer.classList.add('over-limit');
                        dayContainer.classList.remove('completed', 'fire');
                        circle.textContent = '⚠️';
                    } else if (currentTotals.cals >= (userGoals.cals * 0.9)) {
                        // Perfect goal hit (90% - 110%)
                        dayContainer.classList.add('fire');
                        dayContainer.classList.remove('completed', 'over-limit');
                        circle.textContent = '🔥';
                    }
                }
            } else {
                dayContainer.classList.remove('completed', 'fire', 'over-limit');
                circle.textContent = '–';
            }
        }
        
        // Call init on load
        initStreakCalendar();
        
        // Hook updateTodayStreak into renderJournal
        var originalRenderJournal = renderJournal;
        renderJournal = function() {
            originalRenderJournal();
            updateTodayStreak();
        };

    </script>

    <!-- ── Sync thème + langue depuis localStorage (iframe) ── -->
    <script>
    (function() {
        var theme = localStorage.getItem('sf_theme') || 'light';
        var lang  = localStorage.getItem('sf_lang')  || 'fr';

        // Appliquer le thème via data-theme sur <html>
        document.documentElement.setAttribute('data-theme', theme);

        function applyLang(l) {
            if (typeof SmartFoodSettings !== 'undefined' && typeof SmartFoodSettings.applyLanguage === 'function') {
                SmartFoodSettings.applyLanguage(l);
            }
        }

        // Appliquer après chargement complet du DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() { applyLang(lang); });
        } else {
            applyLang(lang);
        }

        // Écouter les changements depuis la page parente
        window.addEventListener('message', function(e) {
            if (!e.data) return;
            if (e.data.type === 'sf_theme') {
                document.documentElement.setAttribute('data-theme', e.data.value);
            }
            if (e.data.type === 'sf_lang') {
                applyLang(e.data.value);
            }
        });
    })();
    </script>

</body>
</html>
