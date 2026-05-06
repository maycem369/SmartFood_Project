<?php
// backoffice/edit_nutrition.php — Vue Back Office: Édition Nutrition (Validation PHP)
require_once __DIR__ . '/../../controller/NutritionController.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: nutrition_dashboard.php');
    exit;
}

$nutritionCtrl = new NutritionController();
$nutData = $nutritionCtrl->getNutritionById((int)$_GET['id']);

if (!$nutData) {
    die("Fiche nutritionnelle introuvable.");
}

// Récupérer les erreurs de validation stockées en session
$errors = isset($_SESSION['nutrition_errors']) ? $_SESSION['nutrition_errors'] : [];
unset($_SESSION['nutrition_errors']); // Nettoyer après lecture
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartFood - Éditer Nutrition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <div class="dashboard-container">
        <!-- Sidebar du Back Office -->
        <nav class="sidebar">
            <div class="logo">Smart<span>Food</span> <small>Admin</small></div>
            <ul class="nav-links">
                <li><a href="../frontoffice/index.php">⬅️ Retour au Front Office</a></li>
                <li><a href="index.php">🥑 Base de données Ingrédients</a></li>
                <li class="active"><a href="nutrition_dashboard.php">📊 Rapport Nutritionnel</a></li>
            </ul>
        </nav>

        <main class="main-content">
            
            <header class="admin-header">
                <h1>⚙️ Modification des Valeurs</h1>
                <p>Édition de l'analyse nutritionnelle pour : <strong><?php echo htmlspecialchars($nutData['nom_ingredient']); ?></strong></p>
            </header>

            <div style="max-width: 800px;">
                <!-- Affichage des erreurs de validation PHP -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <div style="display:flex; flex-direction:column; gap:5px;">
                            <strong>⚠️ Erreur de validation (Serveur) :</strong>
                            <ul style="margin-left: 20px; font-size: 0.9rem;">
                                <?php foreach ($errors as $err): ?>
                                    <li><?php echo $err; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <section class="card">
                    <h3>Fiche Technique (Valeurs pour 100g)</h3>
                    
                    <!-- NOTE: Le novalidate empêche la validation HTML5 pour tester la validation PHP du contrôleur -->
                    <form action="../../controller/NutritionController.php?action=update" method="POST" novalidate>
                        <input type="hidden" name="idNutrition" value="<?php echo $nutData['idNutrition']; ?>">
                        
                        <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 30px;">
                            <div class="input-group">
                                <label>Calories (kcal)</label>
                                <input type="text" name="calories" value="<?php echo $nutData['calories']; ?>">
                            </div>
                            <div class="input-group">
                                <label>Protéines (g)</label>
                                <input type="text" name="proteines" value="<?php echo $nutData['proteines']; ?>">
                            </div>
                            <div class="input-group">
                                <label>Glucides (g)</label>
                                <input type="text" name="glucides" value="<?php echo $nutData['glucides']; ?>">
                            </div>
                            <div class="input-group">
                                <label>Lipides (g)</label>
                                <input type="text" name="lipides" value="<?php echo $nutData['lipides']; ?>">
                            </div>
                        </div>

                        <div style="margin-top: 30px; display: flex; gap: 15px;">
                            <button type="submit" class="btn-primary" style="flex: 2;">Enregistrer les modifications</button>
                            <a href="nutrition_dashboard.php" class="btn-edit" style="flex: 1; justify-content: center; background: #f8fafc; color: #64748b;">Annuler</a>
                        </div>
                    </form>
                </section>

                <div style="background: var(--orange-glow); padding: 20px; border-radius: 15px; border-left: 5px solid var(--orange-primary); margin-top: 20px;">
                    <h4 style="color: var(--orange-deep); margin-bottom: 5px;">🧪 Test de Validation</h4>
                    <p style="color: var(--carbon-deep); font-size: 0.9rem;">
                        Pour tester la <strong>validation PHP obligatoire</strong>, essayez de vider un champ ou de mettre une valeur négative. 
                        Le formulaire sera rejeté par le serveur et les erreurs s'afficheront ci-dessus.
                    </p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
