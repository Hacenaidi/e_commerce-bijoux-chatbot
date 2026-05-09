-- Migration script to add collection_id and marque fields to produit table
-- Execute this in your MySQL database to align with the MVC model
-- Compatible with MySQL 5.5+

-- Add collection_id column (compatible with older MySQL)
SET @exist_collection := (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'produit' AND COLUMN_NAME = 'collection_id'
);

SET @sql_collection := IF(@exist_collection = 0, 
    'ALTER TABLE produit ADD COLUMN collection_id INT NULL AFTER status',
    'SELECT "collection_id column already exists" as message'
);

PREPARE stmt FROM @sql_collection;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add marque column (compatible with older MySQL)
SET @exist_marque := (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'produit' AND COLUMN_NAME = 'marque'
);

SET @sql_marque := IF(@exist_marque = 0,
    'ALTER TABLE produit ADD COLUMN marque VARCHAR(120) NULL AFTER nom',
    'SELECT "marque column already exists" as message'
);

PREPARE stmt FROM @sql_marque;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add indexes
ALTER TABLE produit ADD INDEX idx_produit_collection (collection_id);
ALTER TABLE produit ADD INDEX idx_produit_marque (marque);

-- Add foreign key constraint
ALTER TABLE produit
ADD CONSTRAINT fk_produit_collection FOREIGN KEY (collection_id) REFERENCES collection(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Verify the structure
DESCRIBE produit;
