<?php
include_once('../model/Produit.php') ;
include_once('../database/config.php');
include_once('ChatDataController.php');
include_once('StockController.php');
class ProduitController extends Connexion{
function __construct() {
parent::__construct();
}

private function hasProduitColumn($columnName) {
    $query = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'produit' AND COLUMN_NAME = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($columnName));
    return ((int) $res->fetchColumn() > 0);
}

function listCollections() {
    $query = "SELECT id, nom FROM collection ORDER BY nom ASC";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res;
}

function getAllnom()
{
    $query = "select nom,image from produit group by nom";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res; 
}
function listproduit($nom, $collectionId = ""){
    if ($this->hasProduitColumn('collection_id')) {
        $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.collection_id = c.id WHERE p.nom = ?";
    } else {
        $query = "SELECT p.* FROM produit p WHERE p.nom = ?";
    }
    $params = array($nom);

    if ($collectionId !== "" && $this->hasProduitColumn('collection_id')) {
        $query .= " AND p.collection_id = ?";
        $params[] = $collectionId;
    }

    $res = $this->pdo->prepare($query);
    $res->execute($params);
    return $res;
}
function listproduitparprix($nom,$min,$max,$collectionId = ""){
    if ($this->hasProduitColumn('collection_id')) {
        $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.collection_id = c.id WHERE p.nom = ? AND p.prix BETWEEN ? AND ?";
    } else {
        $query = "SELECT p.* FROM produit p WHERE p.nom = ? AND p.prix BETWEEN ? AND ?";
    }
    $params = array($nom, $min, $max);

    if ($collectionId !== "" && $this->hasProduitColumn('collection_id')) {
        $query .= " AND p.collection_id = ?";
        $params[] = $collectionId;
    }

    $res = $this->pdo->prepare($query);
    $res->execute($params);
    return $res;
}


function produit($id){
    if ($this->hasProduitColumn('collection_id')) {
        $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.collection_id = c.id WHERE p.ref = ?";
    } else {
        $query = "SELECT p.* FROM produit p WHERE p.ref = ?";
    }
    $res = $this->pdo->prepare($query);
    $res->execute(array($id));
    return $res; 
}
function listAllProduit(){
    if ($this->hasProduitColumn('collection_id')) {
        $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.collection_id = c.id";
    } else {
        $query = "SELECT p.* FROM produit p";
    }
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res; 
}
function createProduit(Produit $produit, $stockTaille = null, $stockQuantite = null) {
        $nom = $produit->getNom();
        $prix = $produit->getPrix();
        $couleur = $produit->getCouleur();
        $description = $produit->getDescription();
        $status = $produit->getStatus();
        $image = $produit->getImage();
        $collectionId = $produit->getCollection();

        if ($this->hasProduitColumn('collection_id')) {
            $query = "INSERT INTO produit (`nom`, `couleur`, `prix`, `description`, `status`, `image`, `collection_id`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $res = $this->pdo->prepare($query);
            $res->execute(array($nom, $couleur, $prix, $description, $status, $image, $collectionId !== '' ? $collectionId : null));
        } else {
            $query = "INSERT INTO produit (`nom`, `couleur`, `prix`, `description`, `status`, `image`) VALUES (?, ?, ?, ?, ?, ?)";
            $res = $this->pdo->prepare($query);
            $res->execute(array($nom, $couleur, $prix, $description, $status, $image));
        }

        $lastId = $this->pdo->lastInsertId();
        // handle stock creation if provided
        if ($stockTaille !== null && $stockQuantite !== null) {
            try {
                $sc = new StockController();
                $sc->upsertStock($lastId, $stockTaille, (int)$stockQuantite);
            } catch (Exception $e) {
                error_log('Stock upsert failed after createProduit: ' . $e->getMessage());
            }
        }
        // regenerate chatbot data files after insert
        try {
            $chatGen = new ChatDataController();
            $chatGen->regenerate();
        } catch (Exception $e) {
            error_log('ChatData regenerate failed after create: ' . $e->getMessage());
        }

        return $lastId;
    }
    function updateproduit(Produit $produit, $stockTaille = null, $stockQuantite = null) {
        $nom = $produit->getNom();
        $prix = $produit->getPrix();
        $couleur = $produit->getCouleur();
        $description = $produit->getDescription();
        $status = $produit->getStatus();
        $id = $produit->getRef();
        $collectionId = $produit->getCollection();
        
        if ($this->hasProduitColumn('collection_id')) {
            $query = "UPDATE produit SET nom = ?, prix = ?, couleur = ?, description = ?, status = ?, collection_id = ? WHERE ref = ?";
            $res = $this->pdo->prepare($query);
            $res->execute(array($nom, $prix, $couleur, $description, $status, $collectionId !== '' ? $collectionId : null, $id));
        } else {
            $query = "UPDATE produit SET nom = ?, prix = ?, couleur = ?, description = ?, status = ? WHERE ref = ?";
            $res = $this->pdo->prepare($query);
            $res->execute(array($nom, $prix, $couleur, $description, $status, $id));
        }

        // handle stock update if provided
        if ($stockTaille !== null && $stockQuantite !== null) {
            try {
                $sc = new StockController();
                $sc->upsertStock($id, $stockTaille, (int)$stockQuantite);
            } catch (Exception $e) {
                error_log('Stock upsert failed after updateProduit: ' . $e->getMessage());
            }
        }

        // regenerate chatbot data files after update
        try {
            $chatGen = new ChatDataController();
            $chatGen->regenerate();
        } catch (Exception $e) {
            error_log('ChatData regenerate failed after update: ' . $e->getMessage());
        }

        return $res;
    }
    
function deleteProduit($ref) {
        $query = "DELETE FROM produit WHERE `ref` = '$ref'";
        $res = $this->pdo->prepare($query);
        $res->execute();
        // regenerate chatbot data files after delete
        try {
            $chatGen = new ChatDataController();
            $chatGen->regenerate();
        } catch (Exception $e) {
            error_log('ChatData regenerate failed after delete: ' . $e->getMessage());
        }
        return $res; 
    }
}
?>