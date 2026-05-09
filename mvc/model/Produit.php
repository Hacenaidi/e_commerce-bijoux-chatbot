<?php

class Produit {
private $ref,$description,$couleur,$status,$nom,$prix,$image,$collection;
public function __construct($ref="",$description="",$couleur="",$status="",$nom="",$prix="",$image="",$collection="") {
    $this->ref = $ref;
    $this->description = $description;
   
    $this->couleur = $couleur;
    $this->status = $status;
    $this->nom = $nom;
    $this->prix = $prix;
    $this->image = $image;
    $this->collection = $collection;
    }
    
    public function getRef() {
    return $this->ref;
    }
    
    public function getDescription() {
    return $this->description;
    }
    
   
    
    public function getCouleur() {
    return $this->couleur;
    }
    
    public function getStatus() {
    return $this->status;
    }
    
    public function getNom() {
    return $this->nom;
    }
    
    public function getPrix() {
    return $this->prix;
    }
    
    public function getImage() {
    return $this->image;
    }

    public function getCollection() {
    return $this->collection;
    }

   
    
    public function setRef($ref) {
    $this->ref = $ref;
    }
    
    public function setDescription($description) {
    $this->description = $description;
    }
    
 
    public function setCouleur($couleur) {
    $this->couleur = $couleur;
    }
    
    public function setStatus($status) {
    $this->status = $status;
    }
    
    public function setNom($nom) {
    $this->nom = $nom;
    }
    
    public function setPrix($prix) {
    $this->prix = $prix;
    }
    
    public function setImage($image) {
    $this->image = $image;
    }

    public function setCollection($collection) {
    $this->collection = $collection;
    }

    
}?>