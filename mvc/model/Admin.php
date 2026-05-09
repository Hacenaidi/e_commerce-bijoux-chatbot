<?php

include_once(dirname(__FILE__) . '/User.php');

class Admin extends User {
    public function __construct($id = "", $nom = "", $prenom = "", $email = "", $mot_de_passe = "") {
        parent::__construct($id, $nom, $prenom, $email, $mot_de_passe);
    }
}

?>