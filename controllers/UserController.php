<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profil.php';

class UserController {
    private $user;
    private $profil;
    private $db;

    public function __construct() {
        $database     = new Database();
        $this->db     = $database->getConnection();
        $this->user   = new User($this->db);
        $this->profil = new Profil($this->db);
    }

    // ── Guard ─────────────────────────────────────────────────────────────────

    private function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // =========================================================================
    // USER — DB OPERATIONS
    // =========================================================================

    /** Insert a new user row and return true on success. */
    private function createUser(): bool {
        $query = "INSERT INTO utilisateur (nom, prenom, email, motDePasse, role)
                  VALUES (:nom, :prenom, :email, :motDePasse, :role)";
        $stmt   = $this->db->prepare($query);
        $hashed = password_hash($this->user->getMotDePasse(), PASSWORD_DEFAULT);
        $stmt->bindValue(':nom',        $this->user->getNom());
        $stmt->bindValue(':prenom',     $this->user->getPrenom());
        $stmt->bindValue(':email',      $this->user->getEmail());
        $stmt->bindValue(':motDePasse', $hashed);
        $stmt->bindValue(':role',       $this->user->getRole());
        return $stmt->execute();
    }

    /** Verify credentials and return the user row, or false. */
    private function loginUser(string $email, string $password) {
        $query = "SELECT * FROM utilisateur WHERE email = :email AND statut = 'actif'";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['motDePasse'])) {
            return $user;
        }
        return false;
    }

    /** Check whether an email is already registered. */
    private function emailExists(string $email): bool {
        $query = "SELECT idUser FROM utilisateur WHERE email = :email";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /** Hash and persist a new password for the given email. */
    private function updatePasswordByEmail(string $email, string $newPassword): bool {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $query  = "UPDATE utilisateur SET motDePasse = :password WHERE email = :email";
        $stmt   = $this->db->prepare($query);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':email',    $email);
        return $stmt->execute();
    }

    /** Fetch a single user by primary key. */
    private function fetchUser(int $id): ?array {
        $query = "SELECT * FROM utilisateur WHERE idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Fetch all users ordered by newest first. */
    private function fetchAllUsers(): array {
        $query = "SELECT * FROM utilisateur ORDER BY idUser DESC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Update nom, prenom, email, role for the current user model. */
    private function updateUser(): bool {
        $query = "UPDATE utilisateur
                  SET nom=:nom, prenom=:prenom, email=:email, role=:role
                  WHERE idUser=:id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id',     $this->user->getIdUser());
        $stmt->bindValue(':nom',    $this->user->getNom());
        $stmt->bindValue(':prenom', $this->user->getPrenom());
        $stmt->bindValue(':email',  $this->user->getEmail());
        $stmt->bindValue(':role',   $this->user->getRole());
        return $stmt->execute();
    }

    /** Delete a user row by id. */
    private function deleteUser(int $id): bool {
        $query = "DELETE FROM utilisateur WHERE idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // =========================================================================
    // PROFIL — DB OPERATIONS
    // =========================================================================

    /** Insert a blank profil row linked to a user. */
    private function createDefaultProfil(): bool {
        $query = "INSERT INTO profil
                    (id_user, age, sexe, poids, taille, objectif, niveau_activite, allergies, photo)
                  VALUES
                    (:id_user, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-avatar.png')";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->profil->getIdUser());
        return $stmt->execute();
    }

    /** Fetch profil + user info by user id. */
    private function fetchProfilByUserId(int $id_user): ?array {
        $query = "SELECT p.*, u.nom, u.prenom, u.email
                  FROM profil p
                  JOIN utilisateur u ON p.id_user = u.idUser
                  WHERE p.id_user = :id_user
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Fetch user row joined with its profil (used by admin detail view). */
    private function fetchUserWithProfil(int $id): ?array {
        $query = "SELECT u.*, p.age, p.sexe, p.poids, p.taille,
                         p.objectif, p.niveau_activite, p.allergies
                  FROM utilisateur u
                  LEFT JOIN profil p ON u.idUser = p.id_user
                  WHERE u.idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Persist profil updates for the current profil model. */
    private function updateProfil(): bool {
        $query = "UPDATE profil
                  SET age = :age, sexe = :sexe, poids = :poids, taille = :taille,
                      objectif = :objectif, niveau_activite = :niveau_activite,
                      allergies = :allergies, photo = :photo
                  WHERE id_user = :id_user";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':age',             $this->profil->getAge());
        $stmt->bindValue(':sexe',            $this->profil->getSexe());
        $stmt->bindValue(':poids',           $this->profil->getPoids());
        $stmt->bindValue(':taille',          $this->profil->getTaille());
        $stmt->bindValue(':objectif',        $this->profil->getObjectif());
        $stmt->bindValue(':niveau_activite', $this->profil->getNiveauActivite());
        $stmt->bindValue(':allergies',       $this->profil->getAllergies());
        $stmt->bindValue(':photo',           $this->profil->getPhoto());
        $stmt->bindValue(':id_user',         $this->profil->getIdUser());
        return $stmt->execute();
    }

    // =========================================================================
    // BUSINESS LOGIC — IMC
    // =========================================================================

    /** Calculate BMI from weight (kg) and height (cm). */
    private function calculIMC(float $poids, float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        $tailleM = $taille / 100;
        return round($poids / ($tailleM * $tailleM), 1);
    }

    /** Return the French label for a given BMI value. */
    private function getImcLabel(float $imc): string {
        if      ($imc < 18.5) return "Insuffisance pondérale";
        elseif  ($imc < 25)   return "Normal";
        elseif  ($imc < 30)   return "Surpoids";
        else                  return "Obésité";
    }

    // =========================================================================
    // FRONT OFFICE — ACTIONS
    // =========================================================================

    public function dashboardUser(): void {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'user') {
            header("Location: index.php?action=login");
            exit();
        }
        $profilData = $this->fetchProfilByUserId($_SESSION['user_id']);
        $imc        = null;
        $imcLabel   = null;
        if (!empty($profilData['poids']) && !empty($profilData['taille'])) {
            $imc      = $this->calculIMC($profilData['poids'], $profilData['taille']);
            $imcLabel = $this->getImcLabel($imc);
        }
        include __DIR__ . '/../views/Frontoffice/dashboard_user.php';
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (empty($_POST['prenom']))                              $errors[] = "Prénom requis";
            if (empty($_POST['nom']))                                 $errors[] = "Nom requis";
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
            if ($_POST['password'] !== $_POST['confirm_password'])   $errors[] = "Mots de passe différents";
            if (strlen($_POST['password']) < 6)                      $errors[] = "Mot de passe ≥ 6 caractères";
            if ($this->emailExists($_POST['email']))                  $errors[] = "Email déjà utilisé";

            if (!empty($errors)) {
                $_SESSION['error'] = implode("<br>", $errors);
                header("Location: index.php?action=register");
                exit();
            }

            $this->user->setNom(htmlspecialchars($_POST['nom']));
            $this->user->setPrenom(htmlspecialchars($_POST['prenom']));
            $this->user->setEmail(htmlspecialchars($_POST['email']));
            $this->user->setMotDePasse($_POST['password']);
            $this->user->setRole('user');

            if ($this->createUser()) {
                $lastId = $this->db->lastInsertId();
                $this->profil->setIdUser($lastId);
                $this->createDefaultProfil();
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
            $result = $this->loginUser($_POST['email'] ?? '', $_POST['password'] ?? '');
            if ($result) {
                $_SESSION['user_id']     = $result['idUser'];
                $_SESSION['user_role']   = $result['role'];
                $_SESSION['user_prenom'] = $result['prenom'];
                $_SESSION['user_nom']    = $result['nom'];
                $_SESSION['user_email']  = $result['email'];
                $profilData = $this->fetchProfilByUserId($result['idUser']);
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
            if (!$this->emailExists($email)) {
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
            $this->updatePasswordByEmail($reset['email'], $newPassword);
            $update = $this->db->prepare("UPDATE password_reset SET used = 1 WHERE token = :token");
            $update->bindParam(':token', $token);
            $update->execute();
            $_SESSION['success'] = "Mot de passe modifié. Connectez-vous.";
            header("Location: index.php?action=login");
            exit();
        }
        include __DIR__ . '/../views/Frontoffice/reset_password.php';
    }
    public function ajaxForgotPassword(): void {
    header('Content-Type: application/json');
    $email = $_POST['email'] ?? '';
    if (!$this->emailExists($email)) {
        echo json_encode(['success' => false, 'message' => 'Cet email n\'existe pas.']);
        exit;
    }
    $token = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $this->db->prepare("DELETE FROM password_reset WHERE email = ?")->execute([$email]);
    $stmt = $this->db->prepare("INSERT INTO password_reset (email, token, expiration) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expiration]);
    echo json_encode([
        'success' => true,
        'message' => 'Un lien de réinitialisation a été généré.',
        'token'   => $token
    ]);
    exit;
}

public function ajaxResetPassword(): void {
    header('Content-Type: application/json');
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mot de passe trop court (minimum 6 caractères).']);
        exit;
    }
    $stmt = $this->db->prepare("SELECT * FROM password_reset WHERE token = :token AND expiration > NOW() AND used = 0");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$reset) {
        echo json_encode(['success' => false, 'message' => 'Lien invalide ou expiré.']);
        exit;
    }
    $this->updatePasswordByEmail($reset['email'], $newPassword);
    $update = $this->db->prepare("UPDATE password_reset SET used = 1 WHERE token = :token");
    $update->bindParam(':token', $token);
    $update->execute();
    echo json_encode(['success' => true, 'message' => 'Mot de passe modifié avec succès !']);
    exit;
}
    public function updateProfile(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];

            $this->user->setIdUser($id);
            $this->user->setNom(htmlspecialchars($_POST['nom']));
            $this->user->setPrenom(htmlspecialchars($_POST['prenom']));
            $this->user->setEmail(htmlspecialchars($_POST['email']));
            $this->updateUser();

            $this->profil->setIdUser($id);
            $this->profil->setAge($_POST['age']            ?? null);
            $this->profil->setSexe($_POST['sexe']          ?? null);
            $this->profil->setPoids($_POST['poids']        ?? null);
            $this->profil->setTaille($_POST['taille']      ?? null);
            $this->profil->setObjectif($_POST['objectif']  ?? null);
            $this->profil->setNiveauActivite($_POST['niveau_activite'] ?? null);
            $this->profil->setAllergies($_POST['allergies'] ?? null);

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $target = __DIR__ . "/../assets/uploads/";
                if (!is_dir($target)) mkdir($target, 0777, true);
                $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $filename = "user_" . $id . "_" . time() . "." . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target . $filename)) {
                    $this->profil->setPhoto($filename);
                }
            } else {
                $existing = $this->fetchProfilByUserId($id);
                $this->profil->setPhoto($existing['photo'] ?? 'default-avatar.png');
            }

            if ($this->updateProfil()) {
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
            $currentUser = $this->fetchUser($id);

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

    // =========================================================================
    // BACK OFFICE — ACTIONS
    // =========================================================================

    public function addUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->setNom($_POST['nom']);
            $this->user->setPrenom($_POST['prenom']);
            $this->user->setEmail($_POST['email']);
            $this->user->setMotDePasse($_POST['password']);
            $this->user->setRole($_POST['role']);

            if ($this->createUser()) {
                $lastId = $this->db->lastInsertId();
                $this->profil->setIdUser($lastId);
                $this->createDefaultProfil();
                $_SESSION['success'] = "Utilisateur ajouté.";
            } else {
                $_SESSION['error'] = "Email existe déjà.";
            }
            header("Location: index.php?action=users_list");
            exit();
        }
    }

    public function updateUser_admin(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->setIdUser($_POST['id']);
            $this->user->setNom($_POST['nom']);
            $this->user->setPrenom($_POST['prenom']);
            $this->user->setEmail($_POST['email']);
            $this->user->setRole($_POST['role']);
            if ($this->updateUser()) {
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