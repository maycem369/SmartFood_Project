<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') header("Location: index.php?action=login"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="sidebar">
    <div class="logo"><h1>Smart<span>Food</span></h1><p>Administration</p></div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard" class="active">📊 Dashboard</a></li>
        <li><a href="index.php?action=users_list">👥 Utilisateurs</a></li>
        <li><a href="#">🍽️ Recettes</a></li>
        <li><a href="#">🥗 Ingrédients</a></li>
        <li><a href="#">🤖 IA</a></li>
        <li><a href="index.php?action=logout">🚪 Déconnexion</a></li>
    </ul>
</div>
<div class="main-content">
    <h1 style="color:var(--green);">Bienvenue, Admin ! 👋</h1>
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-number"><?= $totalUsers ?></div><div class="stat-label">Utilisateurs</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalRecettes ?></div><div class="stat-label">Recettes</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalIngredients ?></div><div class="stat-label">Ingrédients</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalSuggestions ?></div><div class="stat-label">Suggestions IA</div></div>
    </div>
    <div class="charts-row">
        <div class="chart-card"><h3>📈 Recettes par mois</h3><canvas id="recettesChart"></canvas></div>
        <div class="chart-card"><h3>🍽️ Calories moyennes</h3><canvas id="caloriesChart"></canvas></div>
    </div>
    <div class="data-section">
        <div class="data-card">
            <h3>👥 Derniers utilisateurs</h3>
            <table><thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr></thead><tbody>
            <?php foreach ($derniersUtilisateurs as $u): ?>
                <tr><td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td><td><?= htmlspecialchars($u['email']) ?></td><td><?= $u['role'] ?></td><td><a href="index.php?action=edit_user&id=<?= $u['idUser'] ?>" class="btn-sm btn-edit">Modifier</a></td></tr>
            <?php endforeach; ?>
            </tbody></table>
        </div>
        <div class="data-card">
            <h3>🍽️ Dernières recettes</h3>
            <table><thead><tr><th>Recette</th><th>Calories</th><th>Auteur</th><th>Actions</th></tr></thead><tbody>
            <?php foreach ($dernieresRecettes as $r): ?>
                <tr><td><?= htmlspecialchars($r['nom']) ?></td><td><?= $r['caloriesTotales'] ?> Cal</td><td><?= htmlspecialchars($r['auteur_prenom'].' '.$r['auteur_nom']) ?></td><td><a href="#" class="btn-sm btn-edit">Modifier</a></td></tr>
            <?php endforeach; ?>
            </tbody></table>
        </div>
    </div>
</div>
<script>
const recettesParMois = <?= json_encode($recettesParMois) ?>;
const caloriesParMois = <?= json_encode($caloriesParMois) ?>;
const moisLabels = recettesParMois.map(i => i.mois);
new Chart(document.getElementById('recettesChart'), { type:'line', data:{ labels:moisLabels, datasets:[{ label:'Recettes', data:recettesParMois.map(i=>i.nombre), borderColor:'#2D6A4F', fill:true }] } });
new Chart(document.getElementById('caloriesChart'), { type:'bar', data:{ labels:moisLabels, datasets:[{ label:'Calories moyennes', data:caloriesParMois.map(i=>i.moyenne_calories), backgroundColor:'#FF8C00' }] } });
</script>
</body>
</html>