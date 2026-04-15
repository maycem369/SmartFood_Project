<?php
// controllers/UserController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profil.php';

class UserController {
    private $user;
    private $profil;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->profil = new Profil($this->db);
    }

    // ===================== FRONT OFFICE =====================
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['prenom']) || empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
                header("Location: index.php?action=register");
                exit();
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Adresse email invalide.";
                header("Location: index.php?action=register");
                exit();
            }
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("Location: index.php?action=register");
                exit();
            }
            if (strlen($_POST['password']) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
                header("Location: index.php?action=register");
                exit();
            }

            $this->user->nom = htmlspecialchars($_POST['nom']);
            $this->user->prenom = htmlspecialchars($_POST['prenom']);
            $this->user->email = htmlspecialchars($_POST['email']);
            $this->user->motDePasse = $_POST['password'];
            $this->user->role = 'user';

            if ($this->user->create()) {
                $lastId = $this->user->getLastInsertId();
                $this->profil->id_user = $lastId;
                $this->profil->createDefault();

                $_SESSION['success'] = "Compte créé avec succès ! Connectez-vous.";
                header("Location: index.php?action=login");
            } else {
                $_SESSION['error'] = "Cet email est déjà utilisé.";
                header("Location: index.php?action=register");
            }
            exit();
        }
        include __DIR__ . '/../views/Frontoffice/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->user->login($_POST['email'] ?? '', $_POST['password'] ?? '');

            if ($result) {
                $_SESSION['user_id']    = $result['idUser'];
                $_SESSION['user_role']  = $result['role'];
                $_SESSION['user_prenom'] = $result['prenom'];
                $_SESSION['user_nom']   = $result['nom'];
                $_SESSION['user_email'] = $result['email'];

                $profilData = $this->profil->getByUserId($result['idUser']);
                if ($profilData) {
                    $_SESSION['user_age'] = $profilData['age'];
                    $_SESSION['user_poids'] = $profilData['poids'];
                    $_SESSION['user_taille'] = $profilData['taille'];
                    $_SESSION['user_objectif'] = $profilData['objectif'];
                    $_SESSION['user_sexe'] = $profilData['sexe'];
                    $_SESSION['user_allergies'] = $profilData['allergies'];
                    $_SESSION['user_niveau_activite'] = $profilData['niveau_activite'];
                    $_SESSION['user_photo'] = $profilData['photo'] ?? 'default-avatar.png';
                }

                header($result['role'] === 'admin' 
                    ? "Location: index.php?action=admin_dashboard" 
                    : "Location: index.php?action=dashboard_user");
                exit();
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header("Location: index.php?action=login");
                exit();
            }
        }
        include __DIR__ . '/../views/Frontoffice/login.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];

            $this->user->idUser = $id;
            $this->user->nom = htmlspecialchars($_POST['nom'] ?? '');
            $this->user->prenom = htmlspecialchars($_POST['prenom'] ?? '');
            $this->user->email = htmlspecialchars($_POST['email'] ?? '');
            $this->user->update();

            $this->profil->id_user = $id;
            $this->profil->age = $_POST['age'] ?? 0;
            $this->profil->sexe = $_POST['sexe'] ?? 'Homme';
            $this->profil->poids = $_POST['poids'] ?? 0;
            $this->profil->taille = $_POST['taille'] ?? 0;
            $this->profil->objectif = $_POST['objectif'] ?? '';
            $this->profil->niveau_activite = $_POST['niveau_activite'] ?? '';
            $this->profil->allergies = $_POST['allergies'] ?? '';

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $target_dir = __DIR__ . "/../assets/uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = "user_" . $id . "_" . time() . ".jpg";
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    $this->profil->photo = $file_name;
                    $_SESSION['user_photo'] = $file_name;
                }
            } else {
                $existingProfil = $this->profil->getByUserId($id);
                $this->profil->photo = $existingProfil['photo'] ?? 'default-avatar.png';
            }

            if ($this->profil->update()) {
                $_SESSION['user_nom'] = $this->user->nom;
                $_SESSION['user_prenom'] = $this->user->prenom;
                $_SESSION['user_email'] = $this->user->email;
                $_SESSION['user_age'] = $this->profil->age;
                $_SESSION['user_poids'] = $this->profil->poids;
                $_SESSION['user_taille'] = $this->profil->taille;
                $_SESSION['user_objectif'] = $this->profil->objectif;
                $_SESSION['user_sexe'] = $this->profil->sexe;
                $_SESSION['user_allergies'] = $this->profil->allergies;
                $_SESSION['user_niveau_activite'] = $this->profil->niveau_activite;
                
                $_SESSION['success'] = "Profil mis à jour avec succès !";
                header("Location: index.php?action=profil");
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
                header("Location: index.php?action=edit_profile");
            }
            exit();
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];
            
            $currentUser = $this->user->readOne($id);
            
            if (password_verify($_POST['current_password'], $currentUser['motDePasse'])) {
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    if (strlen($_POST['new_password']) >= 6) {
                        $newHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                        
                        $query = "UPDATE utilisateur SET motDePasse = :password WHERE idUser = :id";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':password', $newHash);
                        $stmt->bindParam(':id', $id);
                        
                        if ($stmt->execute()) {
                            $_SESSION['success'] = "Mot de passe modifié avec succès !";
                            header("Location: index.php?action=dashboard_user");
                        } else {
                            $_SESSION['error'] = "Erreur lors de la modification.";
                            header("Location: index.php?action=change_password");
                        }
                    } else {
                        $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
                        header("Location: index.php?action=change_password");
                    }
                } else {
                    $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                    header("Location: index.php?action=change_password");
                }
            } else {
                $_SESSION['error'] = "Mot de passe actuel incorrect.";
                header("Location: index.php?action=change_password");
            }
            exit();
        }
    }

    // ===================== BACK OFFICE =====================
    public function listUsers() {
        // Vérification admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        
        $users = $this->user->readAll();
        // Inclure la vue avec les données
        include __DIR__ . '/../views/Backoffice/users_list.php';
    }

    public function addUser() {
        // Vérification admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->nom = htmlspecialchars($_POST['nom']);
            $this->user->prenom = htmlspecialchars($_POST['prenom']);
            $this->user->email = htmlspecialchars($_POST['email']);
            $this->user->motDePasse = $_POST['password'];
            $this->user->role = $_POST['role'] ?? 'user';

            if ($this->user->create()) {
                $lastId = $this->user->getLastInsertId();
                $this->profil->id_user = $lastId;
                $this->profil->createDefault();
                $_SESSION['success'] = "Utilisateur ajouté avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout. L'email existe peut-être déjà.";
            }
            header("Location: index.php?action=users_list");
            exit();
        }
        
        include __DIR__ . '/../views/Backoffice/add_user.php';
    }

    public function editUser($id) {
        // Vérification admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        
        if (!$id) {
            header("Location: index.php?action=users_list");
            exit();
        }
        
        $user = $this->user->readOne($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        
        include __DIR__ . '/../views/Backoffice/edit_user.php';
    }

    public function updateUser() {
        // Vérification admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->idUser = $_POST['id'];
            $this->user->nom = htmlspecialchars($_POST['nom']);
            $this->user->prenom = htmlspecialchars($_POST['prenom']);
            $this->user->email = htmlspecialchars($_POST['email']);
            $this->user->role = $_POST['role'];

            if ($this->user->update()) {
                $_SESSION['success'] = "Utilisateur modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
            header("Location: index.php?action=users_list");
            exit();
        }
    }

    public function deleteUser($id) {
        // Vérification admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        
        // Empêcher la suppression de son propre compte
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            header("Location: index.php?action=users_list");
            exit();
        }
        
        if ($id && $this->user->delete($id)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header("Location: index.php?action=users_list");
        exit();
    }
}
?>