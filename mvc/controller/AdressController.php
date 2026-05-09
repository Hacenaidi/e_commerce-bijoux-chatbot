<?php

include_once('../model/Adress.php');
include_once('../database/config.php');

class AdressController extends Connexion{
    function __construct() {
        parent::__construct();
    }
    function createAdress(Adress $a) {
        $first_name = $a->getFirst_name();
        $last_name = $a->getLast_name();
        $telephone = $a->getTelephone();
        $email = $a->getEmail();
        $adress = $a->getAdress();
        $mandate = $a->getMandate();
        $accrediation = $a->getAccrediation();
        $zip = $a->getZip();
        $id_client = $a->getId_client();
        //print all the variables seperated by a | to check if they are correct

        $query = "INSERT INTO address (`first_name`, `last_name`, `email`, `adress`, `telephone`, `mandate`, `accrediation`, `zip`, `id_client`) VALUES ('$first_name', '$last_name', '$email', '$adress', '$telephone', '$mandate', '$accrediation', '$zip', $id_client)";
        $res = $this->pdo->prepare($query);
        $res->execute();
        $last_id = $this->pdo->lastInsertId();
        return $last_id; 
    }

}



?>