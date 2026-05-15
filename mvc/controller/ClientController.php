<?php
include_once('../model/Client.php') ;
include_once('../database/config.php');
class ClientController extends Connexion{
function __construct() {
parent::__construct();
}

function authenticateClient($mail, $password) {
    $query = "SELECT * FROM client WHERE email = ? AND mot_de_passe = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($mail, $password));
    return $res;
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

function rechercheClient(Client $client ){
    $mail = $client->getEmail();
    $password = $client->getMotDePasse();
    return $this->authenticateClient($mail, $password);
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
        $mot_de_passe = $client->getMotDePasse();
        $email = $client->getEmail();


        $query = "INSERT INTO client (`nom`, `prenom`, `mot_de_passe`, `email`) VALUES ('$nom', '$prenom',  '$mot_de_passe', '$email')";
        $res = $this->pdo->prepare($query);
        $res->execute();
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