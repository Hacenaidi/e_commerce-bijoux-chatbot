-- Migration schema for jewelry e-commerce (compatible with current MVC code)
-- Execute this file in your MySQL database: shopping

CREATE TABLE IF NOT EXISTS client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    prenom VARCHAR(120) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(190) NOT NULL,
    UNIQUE KEY uk_client_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    prenom VARCHAR(120) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(190) NOT NULL,
    UNIQUE KEY uk_admin_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS collection (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    UNIQUE KEY uk_collection_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS produit (
    ref INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    marque VARCHAR(120) NULL,
    couleur VARCHAR(60) NULL,
    prix FLOAT NOT NULL DEFAULT 0,
    description TEXT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'available',
    image VARCHAR(255) NULL,
    collection_id INT NULL,
    metal VARCHAR(80) NULL,
    pierre VARCHAR(80) NULL,
    poids VARCHAR(40) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_produit_nom (nom),
    KEY idx_produit_marque (marque),
    KEY idx_produit_collection (collection_id),
    CONSTRAINT fk_produit_collection FOREIGN KEY (collection_id) REFERENCES collection(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ref_produit INT NOT NULL,
    taille VARCHAR(30) NOT NULL,
    quantite INT NOT NULL DEFAULT 0,
    UNIQUE KEY uk_stock_ref_taille (ref_produit, taille),
    CONSTRAINT fk_stock_produit FOREIGN KEY (ref_produit) REFERENCES produit(ref)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    first_name VARCHAR(120) NOT NULL,
    last_name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    adress VARCHAR(255) NOT NULL,
    zip VARCHAR(20) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    mandate VARCHAR(120) NULL,
    accrediation VARCHAR(120) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_address_client (id_client),
    CONSTRAINT fk_address_client FOREIGN KEY (id_client) REFERENCES client(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address_id INT NOT NULL,
    total FLOAT NOT NULL DEFAULT 0,
    status VARCHAR(40) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_order_address (address_id),
    KEY idx_order_status (status),
    CONSTRAINT fk_order_address FOREIGN KEY (address_id) REFERENCES address(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pannier (
    idpann INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    ref INT NOT NULL,
    quant INT NOT NULL DEFAULT 1,
    taille VARCHAR(30) NULL,
    total_prod FLOAT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_pannier_client (id),
    KEY idx_pannier_produit (ref),
    CONSTRAINT fk_pannier_client FOREIGN KEY (id) REFERENCES client(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pannier_produit FOREIGN KEY (ref) REFERENCES produit(ref)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Optional seed for jewelry collections
INSERT IGNORE INTO collection (nom) VALUES ('Rings');
INSERT IGNORE INTO collection (nom) VALUES ('Necklaces');
INSERT IGNORE INTO collection (nom) VALUES ('Bracelets');
INSERT IGNORE INTO collection (nom) VALUES ('Earrings');
