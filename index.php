<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ProfilController.php';
require_once 'controllers/AdminController.php';

$userController  = new UserController();
$profilController = new ProfilController();
$adminController = new AdminController();

$action = $_GET['action'] ?? 'home';

switch ($action) {

    // ── Pages publiques ───────────────────────────────────────────────────────
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

    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit();

    // ── Front Office (connecté) ───────────────────────────────────────────────
    case 'dashboard_user':
        $userController->dashboardUser();
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
        $profilController->showChangePassword();
        break;

    case 'update_password':
        $userController->updatePassword();
        break;

    // ── Back Office (admin) ───────────────────────────────────────────────────
    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'users_list':
        $adminController->usersList();
        break;

    case 'add_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->addUser();
        } else {
            $adminController->showAddUser();
        }
        break;

    case 'edit_user':
        $adminController->editUser();
        break;

    case 'update_user':
        $userController->updateUser_admin();   // ← méthode renommée pour cohérence
        break;

    case 'user_details':
        $adminController->userDetails();
        break;

    case 'delete_user':
        $adminController->deleteUser();
        break;

    case 'delete_user_confirm':
        $adminController->deleteUserConfirm();
        break;

    // ── AJAX (appels depuis les modales) ──────────────────────────────────────
    case 'ajax_forgot_password':
        $userController->ajaxForgotPassword();
        break;

    case 'ajax_reset_password':
        $userController->ajaxResetPassword();
        break;

    default:
        include 'views/Frontoffice/home.php';
}
?>