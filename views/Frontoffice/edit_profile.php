<?php 
 
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil - SmartFood</title>
    <style>
        :root {
            --green: #2D6A4F;
            --orange: #FF8C00;
            --bg: #F4F7F6;
            --white: #FFFFFF;
            --text: #333333;
            --border: #E0E0E0;
            --light-green: #E9F5EF;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--white);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .logo {
            padding: 25px;
            font-size: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }
        .logo span { color: var(--orange); }
        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: var(--light-green);
            color: var(--green);
            border-left: 4px solid var(--green);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 900px;
        }

        /* Photo Section */
        .photo-section {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 35px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border);
        }
        .photo-circle {
            width: 140px;
            height: 140px;
            border: 6px solid var(--green);
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Form */
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text);
        }
        input, select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        input:focus, select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.15);
            outline: none;
        }

        .btn-save {
            background: var(--green);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-cancel {
            background: #ddd;
            color: #333;
            padding: 14px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Smart<span>Food</span></div>
        <div class="nav-menu">
            <a href="index.php?action=dashboard_user.php">🏠 Tableau de bord</a>
            <a href="index.php?action=profile.php">👤 Mon Profil</a>
            <a href="index.php?action=edit_profile.php" class="active">✏️ Modifier Profil</a>
            <a href="#">📊 Mes Régimes</a>
            <a href="#">🍽️ Recettes</a>
            <a href="index.php?action=change_password.php">🔑 Changer Mot de Passe</a>
            <a href="../../index.php?action=logout">🚪 Déconnexion</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Modifier mon profil</h1>
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="../assets/uploads/default-avatar.png" alt="Avatar" style="width:45px; height:45px; border-radius:50%; border:3px solid var(--green);">
                <div>
                    <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Maycem') ?></strong>
                    <small>Utilisateur</small>
                </div>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="../../index.php?action=update_profile" enctype="multipart/form-data">

                <!-- Photo de profil -->
                <div class="photo-section">
                    <div class="photo-circle">
                        <img src="../assets/uploads/default-avatar.png" id="preview" alt="Photo de profil">
                    </div>
                    <div>
                        <h3>Photo de profil</h3>
                        <p style="color:#666;">Cliquez pour changer votre photo</p>
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
                        <button type="button" onclick="document.getElementById('photoInput').click()" 
                                style="background:var(--orange); color:white; padding:12px 24px; border:none; border-radius:12px;">
                            Changer la photo
                        </button>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label>Nom</label>
                        <input type="text" name="nom" value="Ben Ammar" required>
                    </div>
                    <div>
                        <label>Prénom</label>
                        <input type="text" name="prenom" value="Maycem" required>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" value="maycem@example.com" required>
                </div>

                <h3 style="margin:35px 0 15px; color:var(--green);">Informations santé & nutrition</h3>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div><label>Âge (ans)</label><input type="number" name="age" value="28"></div>
                    <div><label>Sexe</label>
                        <select name="sexe">
                            <option value="Homme" selected>Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                    </div>
                    <div><label>Poids (kg)</label><input type="number" step="0.1" name="poids" value="72"></div>
                    <div><label>Taille (cm)</label><input type="number" name="taille" value="175"></div>
                    <div><label>Objectif</label>
                        <select name="objectif">
                            <option value="Perte de poids" selected>Perte de poids</option>
                        </select>
                    </div>
                    <div><label>Niveau d'activité</label>
                        <select name="niveau_activite">
                            <option value="Modéré" selected>Modéré (exercice 3-5 jours/semaine)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <label>Allergies / Intolérances</label>
                    <input type="text" name="allergies" value="Aucune">
                </div>

                <div style="margin-top:40px; display:flex; gap:15px;">
                    <button type="submit" class="btn-save">Enregistrer les modifications</button>
                    <button type="button" onclick="history.back()" class="btn-cancel">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview photo
        document.getElementById('photoInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('preview').src = ev.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
    <script src="views/js/validation.js"></script>
</body>
</html>