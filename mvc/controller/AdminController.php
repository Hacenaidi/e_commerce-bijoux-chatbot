<?php
$adminModel = __DIR__ . '/../model/Admin.php';
if (file_exists($adminModel)) {
    include_once($adminModel);
}
$dbConfig = __DIR__ . '/../database/config.php';
if (file_exists($dbConfig)) {
    include_once($dbConfig);
}

class AdminController extends Connexion{
function __construct() {
parent::__construct();
    $this->ensureDefaultAdminExists();
}

function authenticateAdmin($mail, $password) {
    // Fetch admin by email and verify hashed password
    $query = "SELECT * FROM admin WHERE email = ? LIMIT 1";
    $res = $this->pdo->prepare($query);
    $res->execute(array($mail));
    $row = $res->fetch(PDO::FETCH_ASSOC);
    if ($row && isset($row['mot_de_passe']) && password_verify($password, $row['mot_de_passe'])) {
        return $row;
    }
    return false;
}


function createAdmin(Admin $admin) {
        $nom = $admin->getNom();
        $prenom = $admin->getPrenom();
    $mot_de_passe = password_hash($admin->getMotDePasse(), PASSWORD_DEFAULT);
        $email = $admin->getEmail();
    $query = "INSERT INTO admin (nom, prenom, mot_de_passe, email) VALUES (?, ?, ?, ?)";
    $res = $this->pdo->prepare($query);
    $res->execute(array($nom, $prenom, $mot_de_passe, $email));
        return $res; 
    }

private function ensureDefaultAdminExists() {
    $email = 'admin@gmail.com';
    $nom = 'Admin';
    $prenom = 'Admin';
    $plainPassword = 'admin123';

    $check = $this->pdo->prepare("SELECT id FROM admin WHERE email = ? LIMIT 1");
    $check->execute(array($email));
    if ($check->fetch(PDO::FETCH_ASSOC)) {
        return;
    }

    $admin = new Admin('', $nom, $prenom, $email, $plainPassword);
    $this->createAdmin($admin);
}
}
?>