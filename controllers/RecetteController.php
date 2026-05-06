<?php
require_once __DIR__."/../config/Database.php";

class RecetteController {

    /* ===== CATEGORIES ===== */
    const CATEGORIES = [
        'Italien',
        'Healthy',
        'Fast Food',
        'Dessert',
        'Tunisien',
        'Autre'
    ];

    /* ===== AUTO MIGRATE DB ===== */
    public static function migrate() {
        $db = Database::connect();

        // Add categorie column if not exists
        $cols = $db->query("SHOW COLUMNS FROM recettes")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('categorie', $cols)) {
            $db->exec("ALTER TABLE recettes ADD COLUMN categorie VARCHAR(50) DEFAULT 'Autre'");
        }
        if (!in_array('photo', $cols)) {
            $db->exec("ALTER TABLE recettes ADD COLUMN photo VARCHAR(255) DEFAULT NULL");
        }
    }

    /* ===== HANDLE REQUEST ===== */
    public function handleRequest() {
        if (isset($_POST['add']))      { $this->addRecette($_POST, $_FILES); }
        if (isset($_POST['delete']))   { $this->delete($_POST['id']); }
        if (isset($_POST['update']))   { $this->updateFull($_POST, $_FILES); }
        if (isset($_POST['validate'])) { $this->validate($_POST['id']); }
    }

    /* ===== UPLOAD PHOTO ===== */
    private function uploadPhoto($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) return null;

        $uploadDir = __DIR__ . '/../uploads/recettes/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) return null;

        $filename = uniqid('recette_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return $filename;
    }

    /* ===== ADD ===== */
    public function addRecette($data, $files = []) {
        $db = Database::connect();

        if (strlen($data['nom']) < 3 || strlen($data['description']) < 3) return;

        $photo    = isset($files['photo']) ? $this->uploadPhoto($files['photo']) : null;
        $categorie = $data['categorie'] ?? 'Autre';

        $db->prepare("INSERT INTO recettes(nom, description, status, categorie, photo)
                      VALUES(?, ?, 'Non validée', ?, ?)")
           ->execute([$data['nom'], $data['description'], $categorie, $photo]);

        $id = $db->lastInsertId();

        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $i) {
                $this->addIngredientToRecette($id, $i);
            }
        }
    }

    /* ===== RELATION ===== */
    public function addIngredientToRecette($id, $ing) {
        $db = Database::connect();
        $db->prepare("INSERT INTO recette_ingredient VALUES(?,?)")->execute([$id, $ing]);
    }

    /* ===== GET ALL ===== */
    public function getAll() {
        $db = Database::connect();
        $sql = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recettes r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idrecette
            LEFT JOIN ingredient i ON ri.idingredient = i.idingredient
            GROUP BY r.idrecette
            ORDER BY r.idrecette DESC
        ";
        return $db->query($sql)->fetchAll();
    }

    /* ===== GET BY CATEGORY ===== */
    public function getByCategory($categorie) {
        $db = Database::connect();
        $sql = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recettes r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idrecette
            LEFT JOIN ingredient i ON ri.idingredient = i.idingredient
            WHERE r.categorie = ?
            GROUP BY r.idrecette
            ORDER BY r.idrecette DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$categorie]);
        return $stmt->fetchAll();
    }

    /* ===== SEARCH MULTIPLE ===== */
    public function searchMultiple($ingredients) {
        $db = Database::connect();
        $placeholders = implode(',', array_fill(0, count($ingredients), '?'));
        $sql = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recettes r
            JOIN recette_ingredient ri ON r.idrecette = ri.idrecette
            JOIN ingredient i ON ri.idingredient = i.idingredient
            GROUP BY r.idrecette
            HAVING SUM(i.nom IN ($placeholders)) = ?
        ";
        $params   = $ingredients;
        $params[] = count($ingredients);
        $stmt     = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /* ===== RECOMMEND ===== */
    public function recommend($userIngredients) {
        $db      = Database::connect();
        $sql     = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recettes r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idrecette
            LEFT JOIN ingredient i ON ri.idingredient = i.idingredient
            GROUP BY r.idrecette
        ";
        $recettes = $db->query($sql)->fetchAll();
        $result   = [];

        foreach ($recettes as $r) {
            $list    = $r['ingredients'] ? explode(',', $r['ingredients']) : [];
            $list    = array_map('trim', $list);
            $total   = count($list);
            $match   = 0;
            $missing = [];

            foreach ($list as $ing) {
                if (in_array($ing, $userIngredients)) $match++;
                else $missing[] = $ing;
            }

            $percent = $total > 0 ? round(($match / $total) * 100) : 0;
            if ($percent == 0) continue;

            $r['match']   = $percent;
            $r['missing'] = implode(', ', $missing);
            $result[]     = $r;
        }

        usort($result, fn($a, $b) => $b['match'] - $a['match']);
        return $result;
    }

    /* ===== DELETE ===== */
    public function delete($id) {
        $db = Database::connect();

        // Delete photo file if exists
        $r = $db->prepare("SELECT photo FROM recettes WHERE idrecette=?");
        $r->execute([$id]);
        $row = $r->fetch();
        if ($row && $row['photo']) {
            $path = __DIR__ . '/../uploads/recettes/' . $row['photo'];
            if (file_exists($path)) unlink($path);
        }

        $db->prepare("DELETE FROM recette_ingredient WHERE idrecette=?")->execute([$id]);
        $db->prepare("DELETE FROM recettes WHERE idrecette=?")->execute([$id]);
    }

    /* ===== UPDATE ===== */
    public function updateFull($data, $files = []) {
        $db = Database::connect();

        // Handle new photo upload
        $photo = null;
        if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
            // Delete old photo
            $r = $db->prepare("SELECT photo FROM recettes WHERE idrecette=?");
            $r->execute([$data['id']]);
            $old = $r->fetch();
            if ($old && $old['photo']) {
                $path = __DIR__ . '/../uploads/recettes/' . $old['photo'];
                if (file_exists($path)) unlink($path);
            }
            $photo = $this->uploadPhoto($files['photo']);
        }

        $categorie = $data['categorie'] ?? 'Autre';

        if ($photo) {
            $db->prepare("UPDATE recettes SET nom=?, description=?, categorie=?, photo=? WHERE idrecette=?")
               ->execute([$data['nom'], $data['description'], $categorie, $photo, $data['id']]);
        } else {
            $db->prepare("UPDATE recettes SET nom=?, description=?, categorie=? WHERE idrecette=?")
               ->execute([$data['nom'], $data['description'], $categorie, $data['id']]);
        }

        $db->prepare("DELETE FROM recette_ingredient WHERE idrecette=?")->execute([$data['id']]);

        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $i) {
                $this->addIngredientToRecette($data['id'], $i);
            }
        }
    }

    /* ===== VALIDATE ===== */
    public function validate($id) {
        $db = Database::connect();
        $db->prepare("UPDATE recettes SET status='Validée' WHERE idrecette=?")->execute([$id]);
    }
}