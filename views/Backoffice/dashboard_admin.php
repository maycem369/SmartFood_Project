<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<!-- Top Navbar -->
<nav class="top-navbar">
    <div class="logo-area"><h1>Smart<span>Food</span></h1></div>
    <div class="nav-links">
        <a href="index.php?action=admin_dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php?action=users_list"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-utensils"></i> Recettes</a>
        <a href="#"><i class="fas fa-carrot"></i> Ingrédients</a>
    </div>
    <div class="user-menu">
        <span><?= htmlspecialchars($_SESSION['user_prenom']) ?></span>
        <a href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i>🚪 Déconnexion</a>
    </div>
</nav>

<div class="backoffice-container">
    <h1 class="page-title">Dashboard</h1>

    <div class="stats-cards">
        <div class="stat-card"><div class="stat-number"><?= $totalUsers ?></div><div class="stat-label">Utilisateurs</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalRecettes ?></div><div class="stat-label">Recettes</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalIngredients ?></div><div class="stat-label">Ingrédients</div></div>
        <div class="stat-card"><div class="stat-number"><?= $totalSuggestions ?></div><div class="stat-label">Suggestions IA</div></div>
    </div>

    <div class="section-card">
        <h2 class="section-title">📈 Recettes par mois</h2>
        <canvas id="recettesChart" height="100"></canvas>
    </div>

    <div class="section-card">
        <h2 class="section-title">🍽️ Calories moyennes</h2>
        <canvas id="caloriesChart" height="100"></canvas>
    </div>

    <div class="section-card">
        <h2 class="section-title">👥 Derniers utilisateurs</h2>
        <table class="data-table">
            <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($derniersUtilisateurs as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                    <td><a href="index.php?action=edit_user&id=<?= $u['idUser'] ?>" class="btn-sm btn-edit">Modifier</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const recettesParMois = <?= json_encode($recettesParMois) ?>;
const caloriesParMois = <?= json_encode($caloriesParMois) ?>;
const moisLabels = recettesParMois.map(i => i.mois);
new Chart(document.getElementById('recettesChart'), { type:'line', data:{ labels:moisLabels, datasets:[{ label:'Nombre de recettes', data:recettesParMois.map(i=>i.nombre), borderColor:'#2D6A4F', fill:true }] } });
new Chart(document.getElementById('caloriesChart'), { type:'bar', data:{ labels:moisLabels, datasets:[{ label:'Calories moyennes', data:caloriesParMois.map(i=>i.moyenne_calories), backgroundColor:'#FF8C00' }] } });
</script>
</body>
</html>