<?php
$adminModel = __DIR__ . '../../model/Admin.php';
if (file_exists($adminModel)) {
    include_once($adminModel);
}
$dbConfig = __DIR__ . '../../database/config.php';
if (file_exists($dbConfig)) {
    include_once($dbConfig);
}

class AdminController extends Connexion{
function __construct() {
parent::__construct();
}

function authenticateAdmin($mail, $password) {
    $query = "SELECT * FROM admin WHERE email = ? AND mot_de_passe = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($mail, $password));
    return $res;
}

function rechercheAdmin($mail,$password){
    return $this->authenticateAdmin($mail, $password);
    }

function createAdmin(Admin $admin) {
        $nom = $admin->getNom();
        $prenom = $admin->getPrenom();
        $mot_de_passe = $admin->getMotDePasse();
        $email = $admin->getEmail();

        $query = "INSERT INTO admin (nom, prenom, mot_de_passe, email) VALUES ('$nom', '$prenom', '$mot_de_passe', '$email')";
        $res = $this->pdo->prepare($query);
        $res->execute();
        return $res; 
    }
}
?>