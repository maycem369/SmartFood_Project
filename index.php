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
        $userController->dashboardUser();       // logique déplacée dans le contrôleur
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
        $profilController->showChangePassword(); // nouvelle méthode dans ProfilController
        break;

    case 'update_password':
        $userController->updatePassword();
        break;

    // ── Back Office (admin) ───────────────────────────────────────────────────
    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'users_list':
        $adminController->usersList();           // plus de SQL dans la vue
        break;

    case 'add_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->addUser();
        } else {
            $adminController->showAddUser();     // nouvelle méthode
        }
        break;

    case 'edit_user':
        $adminController->editUser();            // charge $user dans le contrôleur
        break;

    case 'update_user':
        $userController->updateUser();
        break;

    case 'user_details':
        $adminController->userDetails();         // charge $user dans le contrôleur
        break;

    case 'delete_user':
        $adminController->deleteUser();          // charge $user + vérification dans le contrôleur
        break;

    case 'delete_user_confirm':
        $adminController->deleteUserConfirm();
        break;
case 'ajax_forgot_password':
    header('Content-Type: application/json');
    require_once 'models/User.php';
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    $email = $_POST['email'] ?? '';
    if (!$user->emailExists($email)) {
        echo json_encode(['success' => false, 'message' => 'Cet email n\'existe pas.']);
        exit;
    }
    $token = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $db->prepare("DELETE FROM password_reset WHERE email = ?")->execute([$email]);
    $stmt = $db->prepare("INSERT INTO password_reset (email, token, expiration) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expiration]);
    echo json_encode(['success' => true, 'message' => 'Un lien de réinitialisation a été généré.', 'token' => $token]);
    exit;

case 'ajax_reset_password':
    header('Content-Type: application/json');
    require_once 'models/User.php';
    $database = new Database();
    $db = $database->getConnection();
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mot de passe trop court (minimum 6 caractères).']);
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
    $user = new User($db);
    $user->updatePassword($reset['email'], $newPassword);
    $update = $db->prepare("UPDATE password_reset SET used = 1 WHERE token = :token");
    $update->bindParam(':token', $token);
    $update->execute();
    echo json_encode(['success' => true, 'message' => 'Mot de passe modifié avec succès !']);
    exit;
    default:
        include 'views/Frontoffice/home.php';
}
?>