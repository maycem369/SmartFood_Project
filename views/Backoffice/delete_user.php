<?php  
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php?action=login");
    exit();
}

require_once '../../config/Database.php';
require_once '../../controllers/UserController.php';

$controller = new UserController();
$controller->deleteUser($_GET['id'] ?? null);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Utilisateur - SmartFood</title>
    <style>
    :root {
        --green: #2D6A4F;
        --orange: #FF8C00;
        --bg: #F4F7F6;
        --white: #FFFFFF;
        --text: #333333;
        --border: #E0E0E0;
        --light-green: #E9F5EF;
        --dark-green: #1f4a38;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg);
        display: flex;
        min-height: 100vh;
    }
    /* Sidebar améliorée */
    .sidebar {
        width: 280px;
        background: var(--white);
        box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    .logo {
        padding: 0 30px 40px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 30px;
    }
    .logo h1 {
        color: var(--green);
        font-size: 2.2rem;
        font-weight: 700;
    }
    .logo span { color: var(--orange); }

    .nav-menu {
        list-style: none;
    }
    .nav-menu li { margin-bottom: 8px; }
    .nav-menu a {
        display: flex;
        align-items: center;
        padding: 16px 30px;
        color: var(--text);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .nav-menu a:hover,
    .nav-menu a.active {
        background: var(--light-green);
        color: var(--green);
        border-left: 5px solid var(--green);
    }
    .nav-menu a i { margin-right: 14px; font-size: 1.3rem; }

    /* Main Content */
    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 40px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }
    .card {
        background: var(--white);
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.07);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 18px 15px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }
    th {
        background: var(--light-green);
        color: var(--green);
        font-weight: 600;
    }
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-green { background: var(--green); color: white; }
    .btn-green:hover { background: var(--dark-green); transform: translateY(-2px); }
    .btn-orange { background: var(--orange); color: white; }
    .btn-danger { background: #e74c3c; color: white; }
</style>
</head>
<body>
    <div class="card">
        <h2 style="color:#e74c3c;">⚠️ Suppression d'utilisateur</h2>
        <p style="margin:25px 0; font-size:1.1rem;">Voulez-vous vraiment supprimer l'utilisateur <strong>Maycem Ben Ammar</strong> ?</p>
        <p style="color:#e74c3c; font-weight:bold;">Cette action est irréversible !</p>
        
        <div style="margin-top:40px;">
            <button onclick="confirmDelete()" style="background:#e74c3c; color:white; padding:14px 30px; border:none; border-radius:10px; font-size:1.1rem;">Oui, Supprimer</button>
            <button onclick="history.back()" style="background:#ccc; color:#333; padding:14px 30px; border:none; border-radius:10px; margin-left:15px;">Annuler</button>
        </div>
    </div>

    <script>
        function confirmDelete() {
            alert("✅ Utilisateur supprimé avec succès !");
            window.location.href = "users_list.html";
        }
    </script>
</body>
</html>