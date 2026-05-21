<?php
include_once('../../model/Client.php') ;
include_once('../../database/config.php');
class ClientController extends Connexion{
function __construct() {
parent::__construct();
}

function authenticateClient($mail, $password) {
    // Fetch user by email and verify hashed password
    $query = "SELECT * FROM client WHERE email = ? LIMIT 1";
    $res = $this->pdo->prepare($query);
    $res->execute(array($mail));
    $row = $res->fetch(PDO::FETCH_ASSOC);
    if ($row && isset($row['mot_de_passe']) && password_verify($password, $row['mot_de_passe'])) {
        return $row; // return associative user row on success
    }
    return false;
}

public function findByEmail($email) {
    $query = "SELECT * FROM client WHERE email = ?";
    $res = $this->pdo->prepare($query);
    $res->execute([$email]);
    return $res;
}

function listAllClient($search = '') {
    if ($search !== '') {
        $query = "select * from client where nom like :search order by id desc";
    } else {
        $query = "select * from client order by id desc";
    }
    $res = $this->pdo->prepare($query);
    if ($search !== '') {
        $res->execute(array('search' => '%' . $search . '%'));
        return $res;
    }
    $res->execute();
    return $res;
}

function updateClient($id, $nom, $prenom, $email) {
    $query = "UPDATE client SET nom = ?, prenom = ?, email = ? WHERE id = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($nom, $prenom, $email, $id));
    return $res;
}

// Change client password after verifying current password
function changePassword($clientId, $currentPassword, $newPassword) {
    // Verify current password
    $query = "SELECT mot_de_passe FROM client WHERE id = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($clientId));
    $row = $res->fetch(PDO::FETCH_ASSOC);
    if (!$row) return ['success' => false, 'error' => 'User not found'];

    $existing = $row['mot_de_passe'];
    if (!password_verify($currentPassword, $existing)) {
        return ['success' => false, 'error' => 'Current password is incorrect'];
    }

    // Update password (store hashed)
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = "UPDATE client SET mot_de_passe = ? WHERE id = ?";
    $u = $this->pdo->prepare($update);
    $ok = $u->execute(array($hashed, $clientId));
    return ['success' => (bool)$ok];
}


function rechercheClientParNom($nom){
    $query = "select * from client WHERE nom like '%$nom%'";
    $res=$this->pdo->prepare($query);
    $res->execute();
    return $res;
    

}
    
function createClient(Client $client) {
        $nom = $client->getNom();
        $prenom = $client->getPrenom();
    $mot_de_passe = password_hash($client->getMotDePasse(), PASSWORD_DEFAULT);
        $email = $client->getEmail();
    $query = "INSERT INTO client (`nom`, `prenom`, `mot_de_passe`, `email`) VALUES (?, ?, ?, ?)";
    $res = $this->pdo->prepare($query);
    $res->execute(array($nom, $prenom, $mot_de_passe, $email));
        return $res; 
    }
function getClientCount(){
    $query = "select count(*) from `client`";
    $res = $this->pdo->prepare($query);
    $res->execute();
    $count = $res->fetchColumn();

    return $count; 
}}
?>