<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profil.php';

class UserController {
    private $user;
    private $profil;
    private $db;

    public function __construct() {
        $database    = new Database();
        $this->db    = $database->getConnection();
        $this->user  = new User($this->db);
        $this->profil = new Profil($this->db);
    }

    // ── Garde session ─────────────────────────────────────────────────────────
    private function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // ── Dashboard utilisateur ─────────────────────────────────────────────────
    public function dashboardUser(): void {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'user') {
            header("Location: index.php?action=login");
            exit();
        }
        $profilData = $this->profil->getByUserId($_SESSION['user_id']);
        $imc        = null;
        $imcLabel   = null;
        if (!empty($profilData['poids']) && !empty($profilData['taille'])) {
            $imc = Profil::calculIMC($profilData['poids'], $profilData['taille']);
            if      ($imc < 18.5) $imcLabel = "Insuffisance pondérale";
            elseif  ($imc < 25)   $imcLabel = "Normal";
            elseif  ($imc < 30)   $imcLabel = "Surpoids";
            else                  $imcLabel = "Obésité";
        }
        include __DIR__ . '/../views/Frontoffice/dashboard_user.php';
    }

    // ================= FRONT OFFICE =================
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (empty($_POST['prenom']))                              $errors[] = "Prénom requis";
            if (empty($_POST['nom']))                                 $errors[] = "Nom requis";
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
            if ($_POST['password'] !== $_POST['confirm_password'])   $errors[] = "Mots de passe différents";
            if (strlen($_POST['password']) < 6)                      $errors[] = "Mot de passe ≥ 6 caractères";
            if ($this->user->emailExists($_POST['email']))           $errors[] = "Email déjà utilisé";

            if (!empty($errors)) {
                $_SESSION['error'] = implode("<br>", $errors);
                header("Location: index.php?action=register");
                exit();
            }

            $this->user->nom       = htmlspecialchars($_POST['nom']);
            $this->user->prenom    = htmlspecialchars($_POST['prenom']);
            $this->user->email     = htmlspecialchars($_POST['email']);
            $this->user->motDePasse = $_POST['password'];
            $this->user->role      = 'user';

            if ($this->user->create()) {
                $lastId = $this->user->getLastInsertId();
                $this->profil->id_user = $lastId;
                $this->profil->createDefault();
                $_SESSION['success'] = "Compte créé ! Connectez-vous.";
                header("Location: index.php?action=login");
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription.";
                header("Location: index.php?action=register");
            }
            exit();
        }
        include __DIR__ . '/../views/Frontoffice/register.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->user->login($_POST['email'] ?? '', $_POST['password'] ?? '');
            if ($result) {
                $_SESSION['user_id']     = $result['idUser'];
                $_SESSION['user_role']   = $result['role'];
                $_SESSION['user_prenom'] = $result['prenom'];
                $_SESSION['user_nom']    = $result['nom'];
                $_SESSION['user_email']  = $result['email'];
                $profilData = $this->profil->getByUserId($result['idUser']);
                if ($profilData) {
                    $_SESSION['user_photo'] = $profilData['photo'] ?? 'default-avatar.png';
                }
                $redirect = ($result['role'] === 'admin') ? 'admin_dashboard' : 'dashboard_user';
                header("Location: index.php?action=" . $redirect);
                exit();
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header("Location: index.php?action=login");
                exit();
            }
        }
        include __DIR__ . '/../views/Frontoffice/login.php';
    }

    public function forgotPassword(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            if (!$this->user->emailExists($email)) {
                $_SESSION['error'] = "Cet email n'existe pas.";
                header("Location: index.php?action=forgot_password");
                exit();
            }
            $token      = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $this->db->prepare("DELETE FROM password_reset WHERE email = ?")->execute([$email]);
            $stmt = $this->db->prepare("INSERT INTO password_reset (email, token, expiration) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expiration]);
            $resetLink = "http://localhost/smartfoodMVC/index.php?action=reset_password&token=" . $token;
            $_SESSION['info'] = "Lien de réinitialisation (démonstration) : <a href='$resetLink'>$resetLink</a>";
            header("Location: index.php?action=login");
            exit();
        }
        include __DIR__ . '/../views/Frontoffice/forgot_password.php';
    }

    public function resetPassword(): void {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            header("Location: index.php?action=login");
            exit();
        }
        $stmt = $this->db->prepare("SELECT * FROM password_reset WHERE token = :token AND expiration > NOW() AND used = 0");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$reset) {
            $_SESSION['error'] = "Lien invalide ou expiré.";
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'];
            $confirm     = $_POST['confirm_password'];
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Mot de passe ≥ 6 caractères.";
                header("Location: index.php?action=reset_password&token=" . $token);
                exit();
            }
            if ($newPassword !== $confirm) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("Location: index.php?action=reset_password&token=" . $token);
                exit();
            }
            $this->user->updatePassword($reset['email'], $newPassword);
            $update = $this->db->prepare("UPDATE password_reset SET used = 1 WHERE token = :token");
            $update->bindParam(':token', $token);
            $update->execute();
            $_SESSION['success'] = "Mot de passe modifié. Connectez-vous.";
            header("Location: index.php?action=login");
            exit();
        }
        include __DIR__ . '/../views/Frontoffice/reset_password.php';
    }

    public function updateProfile(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];
            $this->user->idUser = $id;
            $this->user->nom    = htmlspecialchars($_POST['nom']);
            $this->user->prenom = htmlspecialchars($_POST['prenom']);
            $this->user->email  = htmlspecialchars($_POST['email']);
            $this->user->update();

            $this->profil->id_user         = $id;
            $this->profil->age             = $_POST['age']            ?? null;
            $this->profil->sexe            = $_POST['sexe']           ?? null;
            $this->profil->poids           = $_POST['poids']          ?? null;
            $this->profil->taille          = $_POST['taille']         ?? null;
            $this->profil->objectif        = $_POST['objectif']       ?? null;
            $this->profil->niveau_activite = $_POST['niveau_activite'] ?? null;
            $this->profil->allergies       = $_POST['allergies']      ?? null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $target = __DIR__ . "/../assets/uploads/";
                if (!is_dir($target)) mkdir($target, 0777, true);
                $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $filename = "user_" . $id . "_" . time() . "." . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target . $filename)) {
                    $this->profil->photo = $filename;
                }
            } else {
                $existing            = $this->profil->getByUserId($id);
                $this->profil->photo = $existing['photo'] ?? 'default-avatar.png';
            }

            if ($this->profil->update()) {
                $_SESSION['success'] = "Profil mis à jour.";
                header("Location: index.php?action=profil");
            } else {
                $_SESSION['error'] = "Erreur mise à jour.";
                header("Location: index.php?action=edit_profile");
            }
            exit();
        }
    }

    public function updatePassword(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = $_SESSION['user_id'];
            $currentUser = $this->user->readOne($id);
            if (!password_verify($_POST['current_password'], $currentUser['motDePasse'])) {
                $_SESSION['error'] = "Mot de passe actuel incorrect.";
                header("Location: index.php?action=change_password");
                exit();
            }
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("Location: index.php?action=change_password");
                exit();
            }
            if (strlen($_POST['new_password']) < 6) {
                $_SESSION['error'] = "Mot de passe ≥ 6 caractères.";
                header("Location: index.php?action=change_password");
                exit();
            }
            $newHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $query   = "UPDATE utilisateur SET motDePasse = :password WHERE idUser = :id";
            $stmt    = $this->db->prepare($query);
            $stmt->bindParam(':password', $newHash);
            $stmt->bindParam(':id',       $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Mot de passe changé.";
                header("Location: index.php?action=dashboard_user");
            } else {
                $_SESSION['error'] = "Erreur technique.";
                header("Location: index.php?action=change_password");
            }
            exit();
        }
    }

    // ================= BACK OFFICE =================
    public function addUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->nom        = $_POST['nom'];
            $this->user->prenom     = $_POST['prenom'];
            $this->user->email      = $_POST['email'];
            $this->user->motDePasse = $_POST['password'];
            $this->user->role       = $_POST['role'];
            if ($this->user->create()) {
                $lastId                = $this->user->getLastInsertId();
                $this->profil->id_user = $lastId;
                $this->profil->createDefault();
                $_SESSION['success'] = "Utilisateur ajouté.";
            } else {
                $_SESSION['error'] = "Email existe déjà.";
            }
            header("Location: index.php?action=users_list");
            exit();
        }
    }

    public function updateUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->idUser  = $_POST['id'];
            $this->user->nom     = $_POST['nom'];
            $this->user->prenom  = $_POST['prenom'];
            $this->user->email   = $_POST['email'];
            $this->user->role    = $_POST['role'];
            if ($this->user->update()) {
                $_SESSION['success'] = "Utilisateur modifié.";
            } else {
                $_SESSION['error'] = "Erreur modification.";
            }
            header("Location: index.php?action=users_list");
            exit();
        }
    }
}
?>