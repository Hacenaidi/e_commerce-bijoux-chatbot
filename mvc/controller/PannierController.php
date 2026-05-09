<?php
include_once('../model/Pannier.php') ;
include_once('../database/config.php');
class PannierController extends Connexion{
function __construct() {
parent::__construct();
}
function listpannier($id){
    $query = "select * from pannier where id = $id";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res;
}
function addpannier(Pannier $pannier){
    $ref = $pannier->getRef();
    $quantity = $pannier->getQuant();
    $taille = $pannier->getTaille();
    $total_prod = $pannier->getTotal_prod();
    $id = $pannier->getId();
    $query = "insert into pannier (`id`,`ref`,`quant`,`taille`,`total_prod`) values ('$id','$ref','$quantity','$taille','$total_prod')";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res;
}
function deletepannier($id){
    $query = "delete from pannier where idpann = $id";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res;
}
function emptypannier($id){
    $query = "DELETE FROM pannier WHERE `id` = $id";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res; 
}
function getpannier($id){
    $req=$this->pdo->prepare("SELECT * from pannier where idpann=$id");
    $req->execute();
    return $req;
}
function updatepannier($id, $qte) {
    $sql = "UPDATE pannier SET quant = ? WHERE idpann = ?";
    
    $res = $this->pdo->prepare($sql);
    $res->execute(array($qte, $id));
    
    return $res;
}

}
?>