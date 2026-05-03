-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 10:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_travail_de_session_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site_web` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `type_client` varchar(50) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commandebrut`
--

CREATE TABLE `commandebrut` (
  `id_commande_brut` int(11) NOT NULL,
  `id_produit_brut` int(11) DEFAULT NULL,
  `id_fournisseur` int(11) DEFAULT NULL,
  `quantite` float DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `date_reception_prevue` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commandeclient`
--

CREATE TABLE `commandeclient` (
  `id_commande_client` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `date_reception_prevue` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site_web` varchar(100) DEFAULT NULL,
  `personne_de_contact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fournisseurproduit`
--

CREATE TABLE `fournisseurproduit` (
  `id_fournisseur` int(11) NOT NULL,
  `id_produit_brut` int(11) NOT NULL,
  `prix_unitaire` float DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lignecommandeclient`
--

CREATE TABLE `lignecommandeclient` (
  `id_ligne` int(11) NOT NULL,
  `id_commande_client` int(11) DEFAULT NULL,
  `id_produit_transforme` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_vente` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productionplanifiee`
--

CREATE TABLE `productionplanifiee` (
  `id_production` int(11) NOT NULL,
  `id_produit_transforme` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `date_prevue` date DEFAULT NULL,
  `duree_prevue` float DEFAULT NULL,
  `duree_reelle` float DEFAULT NULL,
  `taux_horaire` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produitbrut`
--

CREATE TABLE `produitbrut` (
  `id_produit_brut` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `quantite_stock` int(11) DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `prix_unitaire_moyen` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produittransforme`
--

CREATE TABLE `produittransforme` (
  `id_produit_transforme` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `quantite_stock` float DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `rapportconsommationbruts`
-- (See below for the actual view)
--
CREATE TABLE `rapportconsommationbruts` (
`id_produit_brut` int(11)
,`nom` varchar(100)
,`periode` varchar(7)
,`consommation_totale` double
,`consommation_moyenne` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rapportcoutsproduction`
-- (See below for the actual view)
--
CREATE TABLE `rapportcoutsproduction` (
`id_production` int(11)
,`produit` varchar(100)
,`cout_matieres` double
,`cout_main_oeuvre` double
,`cout_total` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rapportventesparproduit`
-- (See below for the actual view)
--
CREATE TABLE `rapportventesparproduit` (
`id_produit_transforme` int(11)
,`nom` varchar(100)
,`periode` varchar(7)
,`quantite_vendue` decimal(32,0)
,`revenu_total` double
,`cout_estime` double
,`profit` double
);

-- --------------------------------------------------------

--
-- Table structure for table `recette`
--

CREATE TABLE `recette` (
  `id_produit_transforme` int(11) NOT NULL,
  `id_produit_brut` int(11) NOT NULL,
  `quantite` float DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `quantite_resultat` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vuecommandeinventaire`
-- (See below for the actual view)
--
CREATE TABLE `vuecommandeinventaire` (
`id_produit_transforme` int(11)
,`nom` varchar(100)
,`quantite_commandee` decimal(32,0)
,`quantite_stock` float
,`production_prevue` decimal(32,0)
,`ecart` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vueproductioninventaire`
-- (See below for the actual view)
--
CREATE TABLE `vueproductioninventaire` (
`id_production` int(11)
,`id_produit_brut` int(11)
,`produit_brut` varchar(100)
,`besoin_total` double
,`quantite_stock` int(11)
,`quantite_commandee` double
,`ecart` double
);

-- --------------------------------------------------------

--
-- Structure for view `rapportconsommationbruts`
--
DROP TABLE IF EXISTS `rapportconsommationbruts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportconsommationbruts`  AS SELECT `pb`.`id_produit_brut` AS `id_produit_brut`, `pb`.`nom` AS `nom`, date_format(`pp`.`date_prevue`,'%Y-%m') AS `periode`, sum(`r`.`quantite` * `pp`.`quantite`) AS `consommation_totale`, avg(`r`.`quantite` * `pp`.`quantite`) AS `consommation_moyenne` FROM ((`productionplanifiee` `pp` join `recette` `r` on(`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pb`.`id_produit_brut`, date_format(`pp`.`date_prevue`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Structure for view `rapportcoutsproduction`
--
DROP TABLE IF EXISTS `rapportcoutsproduction`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportcoutsproduction`  AS SELECT `pp`.`id_production` AS `id_production`, `pt`.`nom` AS `produit`, sum(`r`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `cout_matieres`, `pp`.`duree_reelle`* `pp`.`taux_horaire` AS `cout_main_oeuvre`, sum(`r`.`quantite` * `pb`.`prix_unitaire_moyen`) + `pp`.`duree_reelle` * `pp`.`taux_horaire` AS `cout_total` FROM (((`productionplanifiee` `pp` join `produittransforme` `pt` on(`pp`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) join `recette` `r` on(`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pp`.`id_production` ;

-- --------------------------------------------------------

--
-- Structure for view `rapportventesparproduit`
--
DROP TABLE IF EXISTS `rapportventesparproduit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportventesparproduit`  AS SELECT `pt`.`id_produit_transforme` AS `id_produit_transforme`, `pt`.`nom` AS `nom`, date_format(`cc`.`date_commande`,'%Y-%m') AS `periode`, sum(`lcc`.`quantite`) AS `quantite_vendue`, sum(`lcc`.`quantite` * `lcc`.`prix_vente`) AS `revenu_total`, sum(`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `cout_estime`, sum(`lcc`.`quantite` * `lcc`.`prix_vente`) - sum(`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `profit` FROM ((((`lignecommandeclient` `lcc` join `commandeclient` `cc` on(`lcc`.`id_commande_client` = `cc`.`id_commande_client`)) join `produittransforme` `pt` on(`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) join `recette` `r` on(`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pt`.`id_produit_transforme`, date_format(`cc`.`date_commande`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Structure for view `vuecommandeinventaire`
--
DROP TABLE IF EXISTS `vuecommandeinventaire`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vuecommandeinventaire`  AS SELECT `lcc`.`id_produit_transforme` AS `id_produit_transforme`, `pt`.`nom` AS `nom`, sum(`lcc`.`quantite`) AS `quantite_commandee`, `pt`.`quantite_stock` AS `quantite_stock`, ifnull(sum(`pp`.`quantite`),0) AS `production_prevue`, `pt`.`quantite_stock`+ ifnull(sum(`pp`.`quantite`),0) - sum(`lcc`.`quantite`) AS `ecart` FROM ((`lignecommandeclient` `lcc` join `produittransforme` `pt` on(`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) left join `productionplanifiee` `pp` on(`pt`.`id_produit_transforme` = `pp`.`id_produit_transforme`)) GROUP BY `lcc`.`id_produit_transforme` ;

-- --------------------------------------------------------

--
-- Structure for view `vueproductioninventaire`
--
DROP TABLE IF EXISTS `vueproductioninventaire`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vueproductioninventaire`  AS SELECT `pp`.`id_production` AS `id_production`, `r`.`id_produit_brut` AS `id_produit_brut`, `pb`.`nom` AS `produit_brut`, sum(`r`.`quantite` * `pp`.`quantite`) AS `besoin_total`, `pb`.`quantite_stock` AS `quantite_stock`, ifnull(sum(`cb`.`quantite`),0) AS `quantite_commandee`, `pb`.`quantite_stock`+ ifnull(sum(`cb`.`quantite`),0) - sum(`r`.`quantite` * `pp`.`quantite`) AS `ecart` FROM (((`productionplanifiee` `pp` join `recette` `r` on(`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) left join `commandebrut` `cb` on(`pb`.`id_produit_brut` = `cb`.`id_produit_brut`)) GROUP BY `pp`.`id_production`, `r`.`id_produit_brut` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Indexes for table `commandebrut`
--
ALTER TABLE `commandebrut`
  ADD PRIMARY KEY (`id_commande_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`),
  ADD KEY `id_fournisseur` (`id_fournisseur`);

--
-- Indexes for table `commandeclient`
--
ALTER TABLE `commandeclient`
  ADD PRIMARY KEY (`id_commande_client`),
  ADD KEY `id_client` (`id_client`);

--
-- Indexes for table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`);

--
-- Indexes for table `fournisseurproduit`
--
ALTER TABLE `fournisseurproduit`
  ADD PRIMARY KEY (`id_fournisseur`,`id_produit_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`);

--
-- Indexes for table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_commande_client` (`id_commande_client`),
  ADD KEY `id_produit_transforme` (`id_produit_transforme`);

--
-- Indexes for table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  ADD PRIMARY KEY (`id_production`),
  ADD KEY `id_produit_transforme` (`id_produit_transforme`);

--
-- Indexes for table `produitbrut`
--
ALTER TABLE `produitbrut`
  ADD PRIMARY KEY (`id_produit_brut`);

--
-- Indexes for table `produittransforme`
--
ALTER TABLE `produittransforme`
  ADD PRIMARY KEY (`id_produit_transforme`);

--
-- Indexes for table `recette`
--
ALTER TABLE `recette`
  ADD PRIMARY KEY (`id_produit_transforme`,`id_produit_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commandebrut`
--
ALTER TABLE `commandebrut`
  MODIFY `id_commande_brut` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commandeclient`
--
ALTER TABLE `commandeclient`
  MODIFY `id_commande_client` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  MODIFY `id_production` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produitbrut`
--
ALTER TABLE `produitbrut`
  MODIFY `id_produit_brut` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produittransforme`
--
ALTER TABLE `produittransforme`
  MODIFY `id_produit_transforme` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commandebrut`
--
ALTER TABLE `commandebrut`
  ADD CONSTRAINT `commandebrut_ibfk_1` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`),
  ADD CONSTRAINT `commandebrut_ibfk_2` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Constraints for table `commandeclient`
--
ALTER TABLE `commandeclient`
  ADD CONSTRAINT `commandeclient_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Constraints for table `fournisseurproduit`
--
ALTER TABLE `fournisseurproduit`
  ADD CONSTRAINT `fournisseurproduit_ibfk_1` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fournisseurproduit_ibfk_2` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`) ON DELETE CASCADE;

--
-- Constraints for table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD CONSTRAINT `lignecommandeclient_ibfk_1` FOREIGN KEY (`id_commande_client`) REFERENCES `commandeclient` (`id_commande_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignecommandeclient_ibfk_2` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`);

--
-- Constraints for table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  ADD CONSTRAINT `productionplanifiee_ibfk_1` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`);

--
-- Constraints for table `recette`
--
ALTER TABLE `recette`
  ADD CONSTRAINT `recette_ibfk_1` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`) ON DELETE CASCADE,
  ADD CONSTRAINT `recette_ibfk_2` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
