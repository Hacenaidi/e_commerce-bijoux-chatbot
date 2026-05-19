<?php
include_once('../../database/config.php');

class CollectionController extends Connexion {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Get all collections
    public function getAllCollections() {
        $query = "SELECT id, nom FROM collection ORDER BY nom ASC";
        $res = $this->pdo->prepare($query);
        $res->execute();
        return $res;
    }
    
    // Get collection by ID
    public function getCollectionById($id) {
        $query = "SELECT id, nom FROM collection WHERE id = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($id));
        return $res->fetch(PDO::FETCH_ASSOC);
    }
    
    // Create new collection
    public function createCollection($nom) {
        $query = "INSERT INTO collection (nom) VALUES (?)";
        $res = $this->pdo->prepare($query);
        $res->execute(array($nom));
        return $this->pdo->lastInsertId();
    }
    
    // Update collection
    public function updateCollection($id, $nom) {
        $query = "UPDATE collection SET nom = ? WHERE id = ?";
        $res = $this->pdo->prepare($query);
        return $res->execute(array($nom, $id));
    }
    
    // Delete collection (products linked to this collection will have collection_id set to NULL)
    public function deleteCollection($id) {
        $query = "DELETE FROM collection WHERE id = ?";
        $res = $this->pdo->prepare($query);
        return $res->execute(array($id));
    }
    
    // Check if collection exists
    public function collectionExists($id) {
        $query = "SELECT COUNT(*) FROM collection WHERE id = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($id));
        return ((int) $res->fetchColumn() > 0);
    }
    
    // Get count of products in collection
    public function getProductCountInCollection($collectionId) {
        $query = "SELECT COUNT(*) FROM produit WHERE id_collection = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($collectionId));
        return (int) $res->fetchColumn();
    }

    // Get product names in a collection
    public function getProductsInCollection($collectionId) {
        $query = "SELECT nom FROM produit WHERE id_collection = ? ORDER BY nom ASC";
        $res = $this->pdo->prepare($query);
        $res->execute(array($collectionId));
        return $res->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>
