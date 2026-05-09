<?php
include_once('./../database/config.php');

class ChatDataController extends Connexion {
    public function __construct() {
        parent::__construct();
    }

    private function hasProduitColumn($columnName) {
        $query = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'produit' AND COLUMN_NAME = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($columnName));
        return ((int) $res->fetchColumn() > 0);
    }

    // Regenerate chatbot data files from DB
    public function regenerate() {
        $dataDir = realpath('./../../chatbot/data');
        // Backup existing files
        $timestamp = date('Ymd_His');
        $backupDir = $dataDir . '/backup_' . $timestamp;
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }
        foreach (array('lulu_collection.txt','style_guide.txt') as $f) {
            $src = $dataDir . '/' . $f;
            if (file_exists($src)) copy($src, $backupDir . '/' . $f);
        }

        // Build lulu_collection: one product per block with description, prix and other characteristics
        // Note: Produit model doesn't include marque, only collection_id
        $hasCollection = $this->hasProduitColumn('collection_id');
        
        if ($hasCollection) {
            $query = "SELECT p.ref,p.nom,p.couleur,p.prix,p.description, c.nom AS collection_nom, p.image FROM produit p LEFT JOIN collection c ON p.collection_id = c.id ORDER BY p.nom ASC";
        } else {
            $query = "SELECT p.ref,p.nom,p.couleur,p.prix,p.description,p.image FROM produit p ORDER BY p.nom ASC";
        }
        
        $productsStmt = $this->pdo->prepare($query);
        $productsStmt->execute();
        $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

        $luluContent = "";
        foreach ($products as $p) {
            $luluContent .= "Product: " . $p['nom'] . "\n";
            if (isset($p['collection_nom']) && !empty($p['collection_nom'])) $luluContent .= "Category: " . $p['collection_nom'] . "\n";
            if (!empty($p['couleur'])) $luluContent .= "Color: " . $p['couleur'] . "\n";
            $luluContent .= "Price: " . $p['prix'] . "\n";
            if (!empty($p['description'])) $luluContent .= "Description: " . strip_tags($p['description']) . "\n";
            $luluContent .= "---\n";
        }

        // Build style_guide: categories with their products
        $styleContent = "";
        if ($hasCollection) {
            $collectionsStmt = $this->pdo->prepare("SELECT id, nom FROM collection ORDER BY nom ASC");
            $collectionsStmt->execute();
            $collections = $collectionsStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($collections as $col) {
                $styleContent .= "Category: " . $col['nom'] . "\n";
                $prodStmt = $this->pdo->prepare("SELECT nom FROM produit WHERE collection_id = ? ORDER BY nom ASC");
                $prodStmt->execute(array($col['id']));
                $prods = $prodStmt->fetchAll(PDO::FETCH_COLUMN);
                foreach ($prods as $pn) {
                    $styleContent .= " - " . $pn . "\n";
                }
                $styleContent .= "\n";
            }

            // If there are products without collection, add them under 'Uncategorized'
            $uncatStmt = $this->pdo->prepare("SELECT nom FROM produit WHERE collection_id IS NULL OR collection_id = '' ORDER BY nom ASC");
            $uncatStmt->execute();
            $uncat = $uncatStmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($uncat)) {
                $styleContent .= "Category: Uncategorized\n";
                foreach ($uncat as $pn) $styleContent .= " - " . $pn . "\n";
                $styleContent .= "\n";
            }
        } else {
            // If no collection field, just list all products
            $styleContent = "All Products:\n";
            $prodStmt = $this->pdo->prepare("SELECT nom FROM produit ORDER BY nom ASC");
            $prodStmt->execute();
            $prods = $prodStmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($prods as $pn) {
                $styleContent .= " - " . $pn . "\n";
            }
        }

        // Write files atomically
        $luluTmp = $dataDir . '/lulu_collection.txt.tmp';
        $luluFinal = $dataDir . '/lulu_collection.txt';
        if (file_put_contents($luluTmp, $luluContent, LOCK_EX) === false) {
            error_log('ChatDataController: failed to write ' . $luluTmp);
            return false;
        }
        if (!@rename($luluTmp, $luluFinal)) {
            @copy($luluTmp, $luluFinal);
            @unlink($luluTmp);
        }

        $styleTmp = $dataDir . '/style_guide.txt.tmp';
        $styleFinal = $dataDir . '/style_guide.txt';
        if (file_put_contents($styleTmp, $styleContent, LOCK_EX) === false) {
            error_log('ChatDataController: failed to write ' . $styleTmp);
            return false;
        }
        if (!@rename($styleTmp, $styleFinal)) {
            @copy($styleTmp, $styleFinal);
            @unlink($styleTmp);
        }

        return true;
    }
}

?>
