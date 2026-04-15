<?php
// =============================================
// index.php - Front Controller (MVC)
// =============================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config/Database.php';
require_once 'controllers/UserController.php';

$controller = new UserController();
$action = $_GET['action'] ?? 'login';

switch ($action) {

    // ===================== FRONT OFFICE =====================
    case 'register':
        $controller->register();
        break;

    case 'login':
        $controller->login();
        break;

    case 'dashboard_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/dashboard_user.php';
        break;

    case 'profil':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/profil.php';
        break;

    case 'edit_profile':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/edit_profile.php';
        break;

    case 'change_password':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Frontoffice/change_password.php';
        break;
        // Dans index.php, après case 'change_password':
    case 'update_password':
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
    $controller->updatePassword();
    break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login");
        exit();
        break;

    // ===================== BACK OFFICE =====================
    case 'admin_dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/dashboard_admin.php';
        break;

    case 'users_list':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $controller->listUsers();
        break;

    case 'add_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $controller->addUser();
        break;

    case 'edit_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $controller->editUser($_GET['id'] ?? null);
        break;

    case 'update_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $controller->updateUser();
        break;

    case 'delete_user':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $controller->deleteUser($_GET['id'] ?? null);
        break;

    case 'user_details':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/Backoffice/user_details.php';
        break;

    // Page par défaut
    default:
        include 'views/Frontoffice/login.php';
}
?>