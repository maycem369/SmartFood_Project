<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion utilisateurs - SmartFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <div class="logo">
        <h1>Smart<span>Food</span></h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php?action=admin_dashboard">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a></li>
        <li><a href="index.php?action=users_list" class="active">
            <i class="fas fa-users"></i> Utilisateurs
        </a></li>
        <li><a href="#">
            <i class="fas fa-utensils"></i> Recettes
        </a></li>
        <li><a href="#">
            <i class="fas fa-carrot"></i> Ingrédients
        </a></li>
    </ul>
    <div class="switch-mode">
        <a href="index.php?action=home" class="admin-link">
            ← Retour à l'accueil
        </a>
    </div>
</div>

<div class="main-content">
    <h1 class="page-title">👥 Gestion des utilisateurs</h1>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="message success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="filter-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Rechercher un utilisateur...">
        <a href="index.php?action=add_user" class="btn-add"><i class="fas fa-plus"></i> Ajouter un utilisateur</a>
    </div>

    <div class="section-card">
        <table class="data-table" id="usersTable">
            <thead><tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Rôle</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u['idUser'] ?></td>
                    <td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="role-badge <?= $u['role']=='admin'?'role-admin':'role-user' ?>"><?= $u['role'] ?></span></td>
                    <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                    <td>
                        <a href="index.php?action=user_details&id=<?= $u['idUser'] ?>" class="btn-sm btn-view">Voir</a>
                        <a href="index.php?action=edit_user&id=<?= $u['idUser'] ?>" class="btn-sm btn-edit">Modifier</a>
                        <a href="#" class="btn-sm btn-delete" data-id="<?= $u['idUser'] ?>" data-nom="<?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?>">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modale suppression -->
<div id="deleteModal" class="modal-suppression">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>⚠️ Suppression</h2>
        <p id="deleteMessage">Voulez-vous vraiment supprimer <strong></strong> ?</p>
        <p style="color:#e74c3c;">Action irréversible !</p>
        <div><a href="#" id="confirmDeleteBtn" class="btn-danger">Oui, supprimer</a><button class="btn-cancel" id="cancelDeleteBtn">Annuler</button></div>
    </div>
</div>

<script>
const modal = document.getElementById('deleteModal');
const closeSpan = document.querySelector('#deleteModal .close-modal');
const cancelBtn = document.getElementById('cancelDeleteBtn');
const confirmBtn = document.getElementById('confirmDeleteBtn');
const deleteMessageSpan = document.querySelector('#deleteMessage strong');
let currentUserId = null;

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        currentUserId = this.getAttribute('data-id');
        deleteMessageSpan.textContent = this.getAttribute('data-nom');
        modal.style.display = 'flex';
    });
});
function closeModal() { modal.style.display = 'none'; currentUserId = null; }
closeSpan.onclick = closeModal;
cancelBtn.onclick = closeModal;
window.onclick = (e) => { if (e.target === modal) closeModal(); };
confirmBtn.onclick = () => { if (currentUserId) window.location.href = 'index.php?action=delete_user_confirm&id=' + currentUserId; };

// Recherche
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        let text = row.cells[1].innerText.toLowerCase() + ' ' + row.cells[2].innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
