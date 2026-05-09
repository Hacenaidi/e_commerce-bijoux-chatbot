<?php

class Adress{
    private $first_name;
    private $last_name;
    private $email;
    private $adress;
    private $telephone;
    private $mandate;
    private $accrediation;
    private $zip;
    private $id;
    private $id_client;
    function __construct($first_name,$last_name,$email,$adress,$telephone,$mandate,$accrediation,$zip,$id,$id_client){
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->adress = $adress;
        $this->telephone = $telephone;
        $this->mandate = $mandate;
        $this->accrediation = $accrediation;
        $this->zip = $zip;
        $this->id = $id;
        $this->id_client = $id_client;
    }
    function getFirst_name(){
        return $this->first_name;
    }
    function getLast_name(){
        return $this->last_name;
    }
    function getEmail(){
        return $this->email;
    }
    function getAdress(){
        return $this->adress;
    }
    function getTelephone(){
        return $this->telephone;
    }
    function getMandate(){
        return $this->mandate;
    }
    function getAccrediation(){
        return $this->accrediation;
    }
    function getZip(){
        return $this->zip;
    }
    function getId(){
        return $this->id;
    }
    function getId_client(){
        return $this->id_client;
    }
    function getIdClient(){
        return $this->id_client;
    }
    function setFirst_name($first_name){
        $this->first_name = $first_name;
    }
    function setLast_name($last_name){
        $this->last_name = $last_name;
    }
    function setEmail($email){
        $this->email = $email;
    }
    function setAdress($adress){
        $this->adress = $adress;
    }
    function setTelephone($telephone){
        $this->telephone = $telephone;
    }
    function setMandate($mandate){
        $this->mandate = $mandate;
    }
    function setAccrediation($accrediation){
        $this->accrediation = $accrediation;
    }
    function setZip($zip){
        $this->zip = $zip;
    }
    function setId($id){
        $this->id = $id;
    }
    function setId_client($id_client){
        $this->id_client = $id_client;
    }
    function setIdClient($id_client){
        $this->id_client = $id_client;
    }
}


?>
