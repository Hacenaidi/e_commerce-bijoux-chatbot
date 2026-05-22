<?php
$prodModel = __DIR__ . '../../model/Produit.php';
if (file_exists($prodModel)) {
    include_once($prodModel);
}
$dbConfig = __DIR__ . '../../database/config.php';
if (file_exists($dbConfig)) {
    include_once($dbConfig);
}
$chatDataCtrl = __DIR__ . '/ChatDataController.php';
if (file_exists($chatDataCtrl)) {
    include_once($chatDataCtrl);
}
$stockCtrl = __DIR__ . '/StockController.php';
if (file_exists($stockCtrl)) {
    include_once($stockCtrl);
}

class ProduitController extends Connexion{
function __construct() {
parent::__construct();
}

    private function getProduitCollectionColumn() {
        $query = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'produit' AND COLUMN_NAME = ?";
        foreach (array('id_collection', 'collection_id') as $col) {
            $res = $this->pdo->prepare($query);
            $res->execute(array($col));
            if (((int) $res->fetchColumn()) > 0) {
                return $col;
            }
        }
        return null;
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


public function listAllProduits() {
  
    $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.id_collection = c.id";
    $res = $this->pdo->prepare($query);
    $res->execute();
    return $res;
}

function listproduit($collectionId = "", $collectionFilter = "") {
    
    $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.id_collection = c.id";
    $params = array();

    if ($collectionId !== "") {
        $query .= " WHERE p.id_collection = ?";
        $params[] = $collectionId;
    }

    // If you want to add more filters, add them here

    $res = $this->pdo->prepare($query);
    $res->execute($params);
    return $res;
}

function listproduitparprix($min, $max, $collectionId = "") {
   
    $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.id_collection = c.id WHERE p.prix BETWEEN ? AND ?";
    $params = array($min, $max);

    if ($collectionId !== "") {
        $query .= " AND p.id_collection = ?";
        $params[] = $collectionId;
    }

    $res = $this->pdo->prepare($query);
    $res->execute($params);
    return $res;
}

function produit($id){
   
    $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.id_collection = c.id WHERE p.ref = ?";
    $res = $this->pdo->prepare($query);
    $res->execute(array($id));
    return $res; 
}

public function produitByName($name) {
    $query = "SELECT * FROM produit WHERE nom = ?";
    $res = $this->pdo->prepare($query);
    $res->execute([$name]);
    return $res->fetch(PDO::FETCH_ASSOC);
}
function listAllProduit(){
   
    $query = "SELECT p.*, c.nom AS collection_nom FROM produit p LEFT JOIN collection c ON p.id_collection = c.id";
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

        $query = "INSERT INTO produit (`nom`, `couleur`, `prix`, `description`, `status`, `image`, `id_collection`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $res = $this->pdo->prepare($query);
        $res->execute(array($nom, $couleur, $prix, $description, $status, $image, $collectionId !== '' ? $collectionId : null));

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
        
        
        $query = "UPDATE produit SET nom = ?, prix = ?, couleur = ?, description = ?, status = ?, `id_collection` = ? WHERE ref = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($nom, $prix, $couleur, $description, $status, $collectionId !== '' ? $collectionId : null, $id));

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