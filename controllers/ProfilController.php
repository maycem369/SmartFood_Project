<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Profil.php';
require_once __DIR__ . '/../models/User.php';

class ProfilController {
    private $profil;
    private $user;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->profil = new Profil($this->db);
        $this->user = new User($this->db);
    }

    public function showProfil() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $profilData = $this->profil->getByUserId($_SESSION['user_id']);
        $userData = $this->user->readOne($_SESSION['user_id']);
        $imc = null;
        $imcLabel = null;
        if (!empty($profilData['poids']) && !empty($profilData['taille'])) {
            $imc = Profil::calculIMC($profilData['poids'], $profilData['taille']);
            if ($imc < 18.5) $imcLabel = "Insuffisance pondérale";
            elseif ($imc < 25) $imcLabel = "Normal";
            elseif ($imc < 30) $imcLabel = "Surpoids";
            else $imcLabel = "Obésité";
        }
        include __DIR__ . '/../views/Frontoffice/profil.php';
    }

    public function showEditProfil() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $profilData = $this->profil->getByUserId($_SESSION['user_id']);
        $userData = $this->user->readOne($_SESSION['user_id']);
        include __DIR__ . '/../views/Frontoffice/edit_profile.php';
    }
}
?>