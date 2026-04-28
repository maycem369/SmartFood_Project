<?php
class StatsModel {
    private $conn;
    private $totalUsers;
    private $totalRecettes;
    private $totalIngredients;
    private $totalSuggestionsIA;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Getters ───────────────────────────────────────────────────────────────

    public function getConn() { return $this->conn; }
    public function getTotalUsers() { return $this->totalUsers; }
    public function getTotalRecettes() { return $this->totalRecettes; }
    public function getTotalIngredients() { return $this->totalIngredients; }
    public function getTotalSuggestionsIA() { return $this->totalSuggestionsIA; }

    // ── Setters ───────────────────────────────────────────────────────────────

    public function setTotalUsers($total): void { $this->totalUsers = $total; }
    public function setTotalRecettes($total): void { $this->totalRecettes = $total; }
    public function setTotalIngredients($total): void { $this->totalIngredients = $total; }
    public function setTotalSuggestionsIA($total): void { $this->totalSuggestionsIA = $total; }
}
?>