<?php
class Stock{
    private $id,$ref,$taille,$quantite;
    public function __construct($id="",$ref="",$taille="",$quantite=""){
        $this->id = $id;
        $this->ref = $ref;
        $this->taille = $taille;
        $this->quantite = $quantite;
    }

    public function getId(){
        return $this->id;
    }

    public function getRef(){
        return $this->ref;
    }

    public function getRef_produit(){
        return $this->ref;
    }

    public function getTaille(){
        return $this->taille;
    }

    public function getQuantite(){
        return $this->quantite;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setRef($ref){
        $this->ref = $ref;
    }

    public function setRef_produit($ref){
        $this->ref = $ref;
    }

    public function setTaille($taille){
        $this->taille = $taille;
    }

    public function setQuantite($quantite){
        $this->quantite = $quantite;
    }
}
?>