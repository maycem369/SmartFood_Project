<?php 

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../index.php?action=login");
    exit();
}

require_once '../../config/Database.php';
require_once '../../models/User.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$user = $userModel->readOne($_GET['id'] ?? 0);

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Utilisateur - SmartFood</title>
     <style>
        :root {
            --green: #2D6A4F;
            --orange: #FF8C00;
            --bg: #F4F7F6;
            --white: #FFFFFF;
            --text: #333333;
            --border: #E0E0E0;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .login-container {
            background: var(--white);
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            color: var(--green);
            font-size: 2.2rem;
        }
        
        .logo span {
            color: var(--orange);
        }
        
        h2 {
            text-align: center;
            color: var(--text);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text);
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input:focus {
            border-color: var(--green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--green);
            color: white;
        }
        
        .btn-primary:hover {
            background: #1f4a38;
        }
        
        .btn-orange {
            background: var(--orange);
            color: white;
            margin-top: 10px;
        }
        
        .links {
            text-align: center;
            margin-top: 25px;
        }
        
        .links a {
            color: var(--green);
            text-decoration: none;
        }
        
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="main-content">
        <h1 style="color:var(--green);">Détails de l'utilisateur</h1>
        <div class="card">
            <h2>Maycem Ben Ammar</h2>
            <p><strong>Email :</strong> maycem@example.com</p>
            <p><strong>Téléphone :</strong> 98 123 456</p>
            <p><strong>Rôle :</strong> Utilisateur</p>
            <p><strong>Date d'inscription :</strong> 12 Avril 2026</p>
            <p><strong>Âge :</strong> 28 ans | <strong>Poids :</strong> 72 kg | <strong>Taille :</strong> 175 cm</p>
            
            <div style="margin-top:40px;">
                <a href="edit_user.html?id=1" style="background:var(--orange); color:white; padding:12px 25px; border-radius:10px; text-decoration:none; display:inline-block;">Modifier</a>
                <a href="users_list.html" style="background:#ccc; color:#333; padding:12px 25px; border-radius:10px; text-decoration:none; display:inline-block; margin-left:15px;">Retour à la liste</a>
            </div>
        </div>
    </div>

    <script>function logout(){if(confirm("Déconnexion ?")) window.location.href="login.html";}</script>
</body>
</html>