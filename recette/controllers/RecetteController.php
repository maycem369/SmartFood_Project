<?php
require_once __DIR__ . "/../config/database.php";

class RecetteController {

    const CATEGORIES = ['Italien', 'Healthy', 'Fast Food', 'Dessert', 'Tunisien', 'Autre'];

    /* ===== AUTO MIGRATE DB ===== */
    public static function migrate() {
        $db   = RecetteDatabase::connect();
        $cols = $db->query("SHOW COLUMNS FROM recette")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('categorie', $cols)) {
            $db->exec("ALTER TABLE recette ADD COLUMN categorie VARCHAR(50) DEFAULT 'Autre'");
        }
        if (!in_array('photo', $cols)) {
            $db->exec("ALTER TABLE recette ADD COLUMN photo VARCHAR(255) DEFAULT NULL");
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
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) return null;
        $filename = uniqid('recette_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return $filename;
    }

    /* ===== ADD ===== */
    public function addRecette($data, $files = []) {
        $db = RecetteDatabase::connect();
        if (strlen($data['nom']) < 3 || strlen($data['description']) < 3) return;
        $photo     = isset($files['photo']) ? $this->uploadPhoto($files['photo']) : null;
        $categorie = $data['categorie'] ?? 'Autre';
        $db->prepare("INSERT INTO recette (nom, description, status, categorie, photo) VALUES (?, ?, 'Non validée', ?, ?)")
           ->execute([$data['nom'], $data['description'], $categorie, $photo]);
        $id = $db->lastInsertId();
        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingId) {
                $this->addIngredientToRecette($id, $ingId);
            }
        }
    }

    /* ===== RELATION ===== */
    public function addIngredientToRecette($idRecette, $idIngredient) {
        $db = RecetteDatabase::connect();
        // quantite et unite ont des valeurs par défaut dans la table
        $db->prepare("INSERT INTO recette_ingredient (idRecette, idIngredient, quantite) VALUES (?, ?, 0)")
           ->execute([$idRecette, $idIngredient]);
    }

    /* ===== GET ALL ===== */
    public function getAll() {
        $db  = RecetteDatabase::connect();
        $sql = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recette r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idRecette
            LEFT JOIN ingredient i ON ri.idIngredient = i.idIngredient
            GROUP BY r.idrecette
            ORDER BY r.idrecette DESC
        ";
        return $db->query($sql)->fetchAll();
    }

    /* ===== GET BY CATEGORY ===== */
    public function getByCategory($categorie) {
        $db   = RecetteDatabase::connect();
        $sql  = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recette r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idRecette
            LEFT JOIN ingredient i ON ri.idIngredient = i.idIngredient
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
        $db           = RecetteDatabase::connect();
        $placeholders = implode(',', array_fill(0, count($ingredients), '?'));
        $sql          = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recette r
            JOIN recette_ingredient ri ON r.idrecette = ri.idRecette
            JOIN ingredient i ON ri.idIngredient = i.idIngredient
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
        $db      = RecetteDatabase::connect();
        $sql     = "
            SELECT r.idrecette, r.nom, r.description, r.status, r.categorie, r.photo,
                   GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
            FROM recette r
            LEFT JOIN recette_ingredient ri ON r.idrecette = ri.idRecette
            LEFT JOIN ingredient i ON ri.idIngredient = i.idIngredient
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
        $db = RecetteDatabase::connect();
        $r  = $db->prepare("SELECT photo FROM recette WHERE idrecette = ?");
        $r->execute([$id]);
        $row = $r->fetch();
        if ($row && $row['photo']) {
            $path = __DIR__ . '/../uploads/recettes/' . $row['photo'];
            if (file_exists($path)) unlink($path);
        }
        $db->prepare("DELETE FROM recette_ingredient WHERE idRecette = ?")->execute([$id]);
        $db->prepare("DELETE FROM recette WHERE idrecette = ?")->execute([$id]);
    }

    /* ===== UPDATE ===== */
    public function updateFull($data, $files = []) {
        $db    = RecetteDatabase::connect();
        $photo = null;
        if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
            $r = $db->prepare("SELECT photo FROM recette WHERE idrecette = ?");
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
            $db->prepare("UPDATE recette SET nom=?, description=?, categorie=?, photo=? WHERE idrecette=?")
               ->execute([$data['nom'], $data['description'], $categorie, $photo, $data['id']]);
        } else {
            $db->prepare("UPDATE recette SET nom=?, description=?, categorie=? WHERE idrecette=?")
               ->execute([$data['nom'], $data['description'], $categorie, $data['id']]);
        }
        $db->prepare("DELETE FROM recette_ingredient WHERE idRecette = ?")->execute([$data['id']]);
        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingId) {
                $this->addIngredientToRecette($data['id'], $ingId);
            }
        }
    }

    /* ===== VALIDATE ===== */
    public function validate($id) {
        $db = RecetteDatabase::connect();
        $db->prepare("UPDATE recette SET status='Validée' WHERE idrecette=?")->execute([$id]);
    }
}
