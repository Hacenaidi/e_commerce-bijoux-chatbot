-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2026 at 09:21 PM
-- Server version: 4.1.22
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `first_name` varchar(20) NOT NULL default '',
  `last_name` varchar(20) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `adress` varchar(30) NOT NULL default '',
  `telephone` varchar(8) NOT NULL default '',
  `mandate` varchar(30) NOT NULL default '',
  `accrediation` varchar(30) NOT NULL default '',
  `zip` varchar(4) NOT NULL default '',
  `id` int(11) NOT NULL auto_increment,
  `id_client` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_client` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`first_name`, `last_name`, `email`, `adress`, `telephone`, `mandate`, `accrediation`, `zip`, `id`, `id_client`) VALUES
('Nour Elhouda', 'Guedri', 'guedrinour545@gmail.com', 'residence horizon plus', '+2165672', 'Nabeul', 'Mrezgua', '8040', 10, 4),
('Nour Elhouda', 'Guedri', 'guedrinour545@gmail.com', 'residence horizon plus', '+2165672', 'Nabeul', 'Mrezgua', '8040', 11, 4),
('Samira', 'Ben Ahmed', 'samira@gmail.com', 'residence breeze', '72666444', 'Nabeul', 'Mrezgua', '8041', 12, 5),
('Nour Elhouda', 'Guedri', 'guedrinour545@gmail.com', 'residence horizon plus', '+2165672', 'Nabeul', 'Mrezgua', '8040', 14, 4),
('maria', 'hindi', 'maria@gmail.com', 'residence palace', '+2165273', 'Nabeul', 'hammamet', '8012', 18, 10),
('Nour Elhouda', 'Guedri', 'guedrinour545@gmail.com', 'qwerty', '+2165672', 'Nabeul', 'Mrezgua', '1234', 19, 5),
('Samira', 'Ben Ahmed', 'samira@gmail.com', 'residence breeze', '72666444', 'Nabeul', 'hammamet', '8041', 20, 5);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(20) NOT NULL default '',
  `prenom` varchar(20) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `mot_de_passe` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`) VALUES
(4, 'admin', 'admin', 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(20) NOT NULL default '',
  `prenom` varchar(20) NOT NULL default '',
  `mot_de_passe` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `mot_de_passe`, `email`) VALUES
(4, 'Guedri', 'Nour Elhouda', '123', 'guedrinour545@gmail.com'),
(5, 'Ben Ahmed', 'Samira', '0000', 'samira@gmail.com'),
(9, 'maria', 'hindi', '123', 'guedrinour545@gmail.com'),
(10, 'maria', 'hindi', 'maria123', 'maria@gmail.com'),
(11, 'Nour Elhouda', 'Guedri', '123', 'guedrinour545@gmail.com'),
(12, 'nour', 'hindi', 'fD2UFVZ4CHCyysJ', 'hacenaidi4455@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(120) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uk_collection_nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`id`, `nom`) VALUES
(3, 'Bracelets'),
(4, 'Earrings'),
(2, 'Necklaces'),
(1, 'Rings');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id_contact` int(11) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `message` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL auto_increment,
  `address_id` int(11) NOT NULL default '0',
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `address_id` (`address_id`,`total`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `order`
--


-- --------------------------------------------------------

--
-- Table structure for table `pannier`
--

CREATE TABLE IF NOT EXISTS `pannier` (
  `idpann` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `ref` int(11) NOT NULL default '0',
  `quant` int(11) NOT NULL default '0',
  `taille` varchar(20) NOT NULL default '',
  `total_prod` decimal(9,3) NOT NULL default '0.000',
  PRIMARY KEY  (`idpann`),
  KEY `id` (`id`,`ref`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `pannier`
--


-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `ref` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `couleur` varchar(20) NOT NULL default '',
  `status` varchar(20) NOT NULL default '',
  `nom` varchar(20) NOT NULL default '',
  `prix` decimal(9,3) NOT NULL default '0.000',
  `image` varchar(60) NOT NULL default '',
  `id_collection` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ref`),
  KEY `id_collection` (`id_collection`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`ref`, `description`, `couleur`, `status`, `nom`, `prix`, `image`, `id_collection`) VALUES
(115, 'Women jacket', 'brown', 'sale', 'nour', 1500.000, 'images/1778345384__cran_2025-12-01_201241.png', 0),
(116, 'fesfezf', 'fef', 'sale', 'Jackets', 1500.000, 'images/1778352287__cran_2025-07-28_225804.png', 0),
(117, 'Women jacket', 'brown', 'new', 'Jackets', 1500.000, 'images/1778353484_tp_rag_langchain_faiss_groq_1__1_.pdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL auto_increment,
  `ref` int(11) NOT NULL default '0',
  `taille` varchar(20) NOT NULL default '',
  `quantite` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `ref`, `taille`, `quantite`) VALUES
(6, 115, 'Adjustable', 55),
(7, 116, 'Adjustable', 5),
(8, 117, 'Adjustable', 77);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pannier`
--
ALTER TABLE `pannier`
  ADD CONSTRAINT `pannier_ibfk_1` FOREIGN KEY (`id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pannier_ibfk_2` FOREIGN KEY (`ref`) REFERENCES `produit` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`ref`) REFERENCES `produit` (`ref`) ON DELETE CASCADE ON UPDATE CASCADE;
