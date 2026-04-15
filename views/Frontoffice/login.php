<?php 
// Pas de session_start() ici (déjà géré dans index.php)
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SmartFood</title>
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
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-container {
            background: var(--white);
            padding: 45px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
        }
        .logo { text-align: center; margin-bottom: 35px; }
        .logo h1 { color: var(--green); font-size: 2.4rem; }
        .logo span { color: var(--orange); }
        h2 { text-align: center; margin-bottom: 30px; color: var(--text); }
        .form-group { margin-bottom: 22px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text); }
        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
        }
        input:focus {
            border-color: var(--green);
            outline: none;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.15);
        }
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            background: var(--green);
            color: white;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background: #1f4a38; }
        .links { text-align: center; margin-top: 25px; }
        .links a { color: var(--green); text-decoration: none; }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Smart<span>Food</span></h1>
        </div>
        
        <h2>Connexion</h2>

        <?php if (isset($success)): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login">
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
        </form>
        
        <div class="links">
            <p>Pas encore de compte ? <a href="index.php?action=register">S'inscrire</a></p>
        </div>
    </div>
    <script src="views/js/validation.js"></script>
</body>
</html>