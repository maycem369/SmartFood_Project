<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ProfilController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/FaceIdController.php';

$userController   = new UserController();
$profilController = new ProfilController();
$adminController  = new AdminController();
$faceIdController = new FaceIdController();

$action = $_GET['action'] ?? 'home';

switch ($action) {

    // ── Pages publiques ───────────────────────────────────
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

    // ── Face ID public ─────────────────────────────
    case 'login_face':
        include 'views/Frontoffice/login_face.php';
        break;
    case 'face_login':
        $faceIdController->login();
        break;

    // ── Paramètres apparence / langue ──────────────
    case 'settings':
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        include 'views/Frontoffice/settings.php';
        break;

    // ── AJAX password reset ───────────────────────────────
    case 'ajax_forgot_password':
        header('Content-Type: application/json');
        $database = new Database();
        $db       = $database->getConnection();
        require_once 'models/User.php';
        $user  = new User($db);
        $email = $_POST['email'] ?? '';
        if (!$user->emailExists($email)) {
            echo json_encode(['success' => false, 'message' => "Cet email n'existe pas."]);
            exit;
        }
        $token      = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $db->prepare("DELETE FROM password_reset WHERE email = ?")->execute([$email]);
        $db->prepare("INSERT INTO password_reset (email, token, expiration) VALUES (?, ?, ?)")
           ->execute([$email, $token, $expiration]);
        echo json_encode(['success' => true, 'message' => 'Lien généré.', 'token' => $token]);
        exit;

    case 'ajax_reset_password':
        header('Content-Type: application/json');
        $database    = new Database();
        $db          = $database->getConnection();
        require_once 'models/User.php';
        $token       = $_POST['token']    ?? '';
        $newPassword = $_POST['password'] ?? '';
        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe trop court.']);
            exit;
        }
        $stmt = $db->prepare("SELECT * FROM password_reset WHERE token = :token AND expiration > NOW() AND used = 0");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$reset) {
            echo json_encode(['success' => false, 'message' => 'Lien invalide ou expiré.']);
            exit;
        }
        (new User($db))->updatePassword($reset['email'], $newPassword);
        $upd = $db->prepare("UPDATE password_reset SET used = 1 WHERE token = :token");
        $upd->bindParam(':token', $token);
        $upd->execute();
        echo json_encode(['success' => true, 'message' => 'Mot de passe modifié !']);
        exit;

    // ── Front Office ─────────────────────────────────────
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

    // ── Face ID authentifié ───────────────────────────────
    case 'face_register':
        $faceIdController->showRegister();
        break;
    case 'face_register_save':
        $faceIdController->register();
        break;
    case 'face_delete':
        $faceIdController->delete();
        break;
    case 'face_status':
        $faceIdController->status();
        break;

    // ── Recettes (Front & Back) ───────────────────────────
    case 'recettes_front':
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        include 'views/Frontoffice/recettes_front.php';
        break;

    case 'recettes_admin':
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header("Location: index.php?action=login"); exit();
        }
        include 'views/Backoffice/recettes_admin.php';
        break;

    case 'nutrition_admin':
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header("Location: index.php?action=login"); exit();
        }
        include 'views/Backoffice/nutrition_admin.php';
        break;

    case 'nutrition_front':
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit(); }
        include 'views/Frontoffice/nutrition_front.php';
        break;

    // ── Back Office ───────────────────────────────────────
    case 'admin_dashboard':
        $adminController->dashboard();
        break;
    case 'users_list':
        $adminController->usersList();
        break;
    case 'add_user':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $userController->addUser()
            : $adminController->showAddUser();
        break;
    case 'edit_user':
        $adminController->editUser();
        break;
    case 'update_user':
        $userController->updateUser_admin();
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
    case 'admin_configuration':
        $adminController->configuration();
        break;
    case 'ajax_update_password':
        $userController->ajaxUpdatePassword();
        break;

    default:
        include 'views/Frontoffice/home.php';
}
?>
