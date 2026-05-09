<?php

include_once(dirname(__FILE__) . '/User.php');

class Client extends User {
    public function __construct($id = "", $nom = "", $prenom = "", $mot_de_passe = "", $email = "") {
	    parent::__construct($id, $nom, $prenom, $email, $mot_de_passe);
    }
}

?>