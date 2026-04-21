<?php
class StatsModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
    public function getTotalUsers() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM utilisateur");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getTotalRecettes() {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM recette");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getTotalIngredients() { return 320; } // valeur exemple
    public function getTotalSuggestionsIA() { return 1200; }
    public function getRecettesParMois() {
        $query = "SELECT DATE_FORMAT(date_creation, '%Y-%m') as mois, COUNT(*) as nombre FROM recette WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY mois ORDER BY mois ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCaloriesMoyennesParMois() {
        $query = "SELECT DATE_FORMAT(date_creation, '%Y-%m') as mois, AVG(caloriesTotales) as moyenne_calories FROM recette WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY mois ORDER BY mois ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDerniersUtilisateurs($limit = 5) {
        $query = "SELECT idUser, nom, prenom, email, role, date_creation FROM utilisateur ORDER BY date_creation DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDernieresRecettes($limit = 5) {
        $query = "SELECT r.idRecette, r.nom, r.caloriesTotales, r.date_creation, u.nom as auteur_nom, u.prenom as auteur_prenom FROM recette r LEFT JOIN utilisateur u ON r.id_user = u.idUser ORDER BY r.date_creation DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>