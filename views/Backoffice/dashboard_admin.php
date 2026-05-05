<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="assets/js/settings.js"></script>
    <meta charset="UTF-8">
    <title>Pro Admin – SmartFood Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
<<<<<<< HEAD
=======
        /* Surcharge locale pour l'effet Pro */
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
        body { background-color: #f0f4f3; }
        .main-content { margin-left: var(--sidebar-w); padding: 0 30px 30px; }
        .sidebar { width: var(--sidebar-w); }
    </style>
</head>
<body>

<<<<<<< HEAD
<div class="sidebar admin-sidebar">
    <div class="logo"><h1 style="color:white;">Smart<span>Food</span></h1></div>
=======
<!-- Sidebar Pro -->
<div class="sidebar admin-sidebar">
    <div class="logo">
        <h1 style="color:white;">Smart<span>Food</span> </h1>
    </div>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard" class="active">
            <i class="fas fa-chart-line"></i> <span data-i18n="admin_nav_dashboard">Vue d'ensemble</span>
        </a></li>
        <li><a href="index.php?action=users_list">
            <i class="fas fa-user-shield"></i> <span data-i18n="admin_nav_users">Utilisateurs</span>
        </a></li>
        <li><a href="#">
            <i class="fas fa-scroll"></i> <span data-i18n="admin_nav_recipes">Recettes & Menus</span>
        </a></li>
        <li><a href="#">
            <i class="fas fa-database"></i> <span data-i18n="admin_nav_ingredients">Ingrédients</span>
        </a></li>
        <li><a href="index.php?action=admin_configuration">
            <i class="fas fa-cog"></i> <span data-i18n="admin_nav_config">Configuration</span>
        </a></li>
    </ul>
    <div class="switch-mode" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <a href="index.php?action=logout" class="admin-link" style="color: var(--text-sidebar);">
            <i class="fas fa-sign-out-alt"></i> <span data-i18n="nav_logout">Déconnexion</span>
        </a>
    </div>
</div>
<<<<<<< HEAD

<div class="main-content">
    <div class="admin-top-nav">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un utilisateur, une recette...">
        </div>
        <div class="admin-profile-info">
            <div style="text-align: right;">
                <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);" data-i18n="admin_role">Administrateur Principal</div>
            </div>
            <div class="admin-avatar"><?= strtoupper(substr($_SESSION['user_prenom'], 0, 1)) ?></div>
        </div>
    </div>
=======
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080

<div class="main-content">
    <!-- Top Navbar -->
    <div class="admin-top-nav">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un utilisateur, une recette...">
        </div>
        <div class="admin-profile-info">
            <div style="text-align: right;">
                <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Administrateur Principal</div>
            </div>
            <div class="admin-avatar">
                <?= strtoupper(substr($_SESSION['user_prenom'], 0, 1)) ?>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card" style="border-left: 4px solid var(--front-accent);">
            <div class="stat-number"><?= $totalUsers ?></div>
<<<<<<< HEAD
            <div class="stat-label" data-i18n="total_users">Total Utilisateurs</div>
            <div style="font-size: 0.7rem; color: #2ecc71;"><i class="fas fa-arrow-up"></i> +12%</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--front-orange);">
            <div class="stat-number"><?= $totalRecettes ?></div>
            <div class="stat-label" data-i18n="total_recipes">Recettes Actives</div>
            <div style="font-size: 0.7rem; color: #2ecc71;"><i class="fas fa-arrow-up"></i> +5%</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #3498db;">
            <div class="stat-number"><?= $totalIngredients ?></div>
            <div class="stat-label" data-i18n="total_ingredients">Ingrédients Base</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #9b59b6;">
            <div class="stat-number"><?= $totalSuggestions ?></div>
            <div class="stat-label" data-i18n="total_ai_suggestions">IA Suggestions</div>
        </div>
    </div>

    <div class="stats-innovative-grid">
        <div class="innovative-card">
            <h3><i class="fas fa-chart-area" style="color: var(--front-accent);"></i> <span data-i18n="performance_chart">Croissance des Recettes</span></h3>
            <canvas id="recettesChart" height="200"></canvas>
        </div>
        <div class="innovative-card">
            <h3><i class="fas fa-pie-chart" style="color: var(--front-orange);"></i> <span data-i18n="goals_chart">Répartition des Objectifs</span></h3>
=======
            <div class="stat-label">Total Utilisateurs</div>
            <div style="font-size: 0.7rem; color: #2ecc71;"><i class="fas fa-arrow-up"></i> +12% ce mois</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--front-orange);">
            <div class="stat-number"><?= $totalRecettes ?></div>
            <div class="stat-label">Recettes Actives</div>
            <div style="font-size: 0.7rem; color: #2ecc71;"><i class="fas fa-arrow-up"></i> +5% ce mois</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #3498db;">
            <div class="stat-number"><?= $totalIngredients ?></div>
            <div class="stat-label">Ingrédients Base</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #9b59b6;">
            <div class="stat-number"><?= $totalSuggestions ?></div>
            <div class="stat-label">IA Suggestions</div>
        </div>
    </div>

    <!-- Innovative Stats Grid -->
    <div class="stats-innovative-grid">
        <!-- Chart 1: Performance -->
        <div class="innovative-card">
            <h3><i class="fas fa-chart-area" style="color: var(--front-accent);"></i> Croissance des Recettes</h3>
            <canvas id="recettesChart" height="200"></canvas>
        </div>
        <!-- Chart 2: Goal Distribution (Innovative) -->
        <div class="innovative-card">
            <h3><i class="fas fa-pie-chart" style="color: var(--front-orange);"></i> Répartition des Objectifs</h3>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
            <canvas id="goalsChart" height="200"></canvas>
        </div>
    </div>

    <div class="stats-innovative-grid" style="grid-template-columns: 2fr 1fr;">
<<<<<<< HEAD
        <div class="innovative-card">
            <h3><i class="fas fa-users"></i> <span data-i18n="recent_users">Inscriptions Récentes</span></h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th data-i18n="avatar">Avatar</th>
                        <th data-i18n="fullname_col">Utilisateur</th>
                        <th data-i18n="email_col">Email</th>
                        <th data-i18n="date_col">Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($derniersUtilisateurs as $u): ?>
                    <tr>
                        <td><div class="admin-avatar" style="width:30px;height:30px;font-size:0.8rem;background:#ecf0f1;color:#2c3e50;"><?= strtoupper(substr($u['prenom'], 0, 1)) ?></div></td>
                        <td><strong><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                        <td><span style="padding:4px 8px;border-radius:20px;font-size:0.7rem;background:#e9f5ef;color:#2d6a4f;" data-i18n="status_active">Actif</span></td>
=======
        <!-- Last Users -->
        <div class="innovative-card">
            <h3><i class="fas fa-users"></i> Inscriptions Récentes</h3>
            <table class="data-table">
                <thead><tr><th>Avatar</th><th>Utilisateur</th><th>Email</th><th>Date</th><th>Statut</th></tr></thead>
                <tbody>
                    <?php foreach($derniersUtilisateurs as $u): ?>
                    <tr>
                        <td><div class="admin-avatar" style="width:30px; height:30px; font-size:0.8rem; background:#ecf0f1; color:#2c3e50;"><?= strtoupper(substr($u['prenom'], 0, 1)) ?></div></td>
                        <td><strong><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                        <td><span style="padding: 4px 8px; border-radius: 20px; font-size: 0.7rem; background: #e9f5ef; color: #2d6a4f;">Actif</span></td>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<<<<<<< HEAD
        <div class="innovative-card history-detector">
            <h3><i class="fas fa-history"></i> <span data-i18n="history_detector">Détecteur d'historique</span></h3>
=======
        <!-- History Detector (History of actions) -->
        <div class="innovative-card history-detector">
            <h3><i class="fas fa-history"></i> Détecteur d'historique</h3>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach($recentActivities as $log): ?>
                <div class="history-item">
                    <div class="history-icon <?= $log['action_type'] ?>">
                        <i class="fas <?= $log['action_type'] === 'user' ? 'fa-user-plus' : ($log['action_type'] === 'recette' ? 'fa-utensils' : ($log['action_type'] === 'danger' ? 'fa-exclamation-triangle' : 'fa-info-circle')) ?>"></i>
                    </div>
                    <div class="history-content">
<<<<<<< HEAD
                        <div style="font-size:0.85rem;font-weight:500;"><?= htmlspecialchars($log['description']) ?></div>
=======
                        <div style="font-size: 0.85rem; font-weight: 500;"><?= htmlspecialchars($log['description']) ?></div>
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
                        <div class="time"><?= date('H:i', strtotime($log['created_at'])) ?> — <?= date('d M', strtotime($log['created_at'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
<<<<<<< HEAD
    const recettesData = <?= json_encode($recettesParMois) ?>;
=======
    // Chart 1: Line Chart
    const recettesData = <?= json_encode($recettesParMois) ?>;
    const labels = recettesData.map(i => i.mois);
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
    const ctx1 = document.getElementById('recettesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
<<<<<<< HEAD
            labels: recettesData.map(i => i.mois),
            datasets: [{ label: 'Recettes créées', data: recettesData.map(i => i.nombre),
                borderColor: '#2D6A4F', backgroundColor: 'rgba(45,106,79,0.1)',
                borderWidth: 3, tension: 0.4, fill: true }]
=======
            labels: labels,
            datasets: [{
                label: 'Recettes créées',
                data: recettesData.map(i => i.nombre),
                borderColor: '#2D6A4F',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

<<<<<<< HEAD
=======
    // Chart 2: Doughnut Chart (Innovative)
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
    const goalsData = <?= json_encode($goalDistribution) ?>;
    const ctx2 = document.getElementById('goalsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: goalsData.map(i => i.objectif || 'Non défini'),
<<<<<<< HEAD
            datasets: [{ data: goalsData.map(i => i.nb),
                backgroundColor: ['#2D6A4F','#FF8C00','#3498db','#9b59b6','#f1c40f'],
                borderWidth: 0, hoverOffset: 10 }]
        },
        options: { cutout: '70%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
=======
            datasets: [{
                data: goalsData.map(i => i.nb),
                backgroundColor: ['#2D6A4F', '#FF8C00', '#3498db', '#9b59b6', '#f1c40f'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: { 
            cutout: '70%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } 
        }
>>>>>>> 318f1fe5f5c4954ba1f9f36cc032e57a5bff4080
    });
</script>
</body>
</html>








