<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ProfilController.php';
require_once 'controllers/AdminController.php';

$userController = new UserController();
$profilController = new ProfilController();
$adminController = new AdminController();

// Page par défaut : 'home' (landing page) au lieu de 'login'
$action = $_GET['action'] ?? 'home';

switch ($action) {
    // Front Office - pages publiques
    case 'home':
        include 'views/Frontoffice/home.php';
        break;

    case 'register':
        $userController->register();
        break;

    case 'login':
        $userController->login();
        break;

    case 'forgot_password':
        $userController->forgotPassword();
        break;

    case 'reset_password':
        $userController->resetPassword();
        break;

    case 'dashboard_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/dashboard_user.php';
        break;

    case 'profil':
        $profilController->showProfil();
        break;

    case 'edit_profile':
        $profilController->showEditProfil();
        break;

    case 'update_profile':
        $userController->updateProfile();
        break;

    case 'change_password':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/change_password.php';
        break;

    case 'update_password':
        $userController->updatePassword();
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login");
        exit();

    // Back Office
    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'users_list':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/users_list.php';
        break;

    case 'add_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->addUser();
        } else {
            include 'views/Backoffice/add_user.php';
        }
        break;

    case 'edit_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/edit_user.php';
        break;

    case 'update_user':
        $userController->updateUser();
        break;

    case 'user_details':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/user_details.php';
        break;

    case 'delete_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/delete_user.php';
        break;

    case 'delete_user_confirm':
        $userController->deleteUser($_GET['id'] ?? null);
        break;

    default:
        // Si l'action n'existe pas, afficher la page d'accueil
        include 'views/Frontoffice/home.php';
}
?>