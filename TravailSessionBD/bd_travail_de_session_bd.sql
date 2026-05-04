-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 04 mai 2026 à 19:26
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `travail_session`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
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

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `telephone`, `email`, `site_web`, `contact`, `type_client`, `adresse`, `ville`, `region`) VALUES
(1, 'Épicerie du coin', '4184444444', 'contact@epicerie.ca', 'www.epicerie.ca', 'Paul Roy', 'épicerie', '123 rue Main', 'Rimouski', 'Bas-Saint-Laurent'),
(2, 'Restaurant BonGoût', '4185555555', 'info@bongout.ca', 'www.bongout.ca', 'Julie Bouchard', 'restaurant', '456 rue Chef', 'Rimouski', 'Bas-Saint-Laurent'),
(3, 'Client privé', '4186666666', 'client@mail.com', '', 'Marc Leblanc', 'Particulier', '789 rue Perso', 'Rimouski', 'Bas-Saint-Laurent'),
(4, 'Olivier ANIMAKA', '93869366', 'olivieranimaka1995@gmail.com', 'www.olivier.com', 'Thomas', 'Particulier', '1234', 'Lomé', 'Maritime');

-- --------------------------------------------------------

--
-- Structure de la table `commandebrut`
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

--
-- Déchargement des données de la table `commandebrut`
--

INSERT INTO `commandebrut` (`id_commande_brut`, `id_produit_brut`, `id_fournisseur`, `quantite`, `prix`, `date_commande`, `date_reception_prevue`, `statut`) VALUES
(1, 1, 1, 30, 50, '2026-05-07', '2026-05-14', 'expédié');

-- --------------------------------------------------------

--
-- Structure de la table `commandeclient`
--

CREATE TABLE `commandeclient` (
  `id_commande_client` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `date_reception_prevue` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandeclient`
--

INSERT INTO `commandeclient` (`id_commande_client`, `id_client`, `date_commande`, `date_reception_prevue`, `statut`) VALUES
(2, 4, '2026-05-04', NULL, 'Confirmée'),
(3, 1, '2026-05-04', NULL, 'En préparation');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site_web` varchar(100) DEFAULT NULL,
  `personne_de_contact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `nom`, `telephone`, `email`, `site_web`, `personne_de_contact`) VALUES
(1, 'AgriNord', '4181111111', 'contact@agrinord.ca', 'www.agrinord.ca', 'Jean Tremblay'),
(2, 'SelPlus', '4182222222', 'info@selplus.ca', 'www.selplus.ca', 'Marie Gagnon'),
(3, 'HydroSource', '4183333333', 'support@hydro.ca', 'www.hydro.ca', 'Luc Martin');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurproduit`
--

CREATE TABLE `fournisseurproduit` (
  `id_fournisseur` int(11) NOT NULL,
  `id_produit_brut` int(11) NOT NULL,
  `prix_unitaire` float DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseurproduit`
--

INSERT INTO `fournisseurproduit` (`id_fournisseur`, `id_produit_brut`, `prix_unitaire`, `unite_mesure`) VALUES
(1, 1, 2.5, 'kg'),
(2, 2, 0.8, 'kg'),
(3, 3, 0.01, 'L');

-- --------------------------------------------------------

--
-- Structure de la table `lignecommandeclient`
--

CREATE TABLE `lignecommandeclient` (
  `id_ligne` int(11) NOT NULL,
  `id_commande_client` int(11) DEFAULT NULL,
  `id_produit_transforme` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_vente` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lignecommandeclient`
--

INSERT INTO `lignecommandeclient` (`id_ligne`, `id_commande_client`, `id_produit_transforme`, `quantite`, `prix_vente`) VALUES
(2, 2, 1, 25, 9.5),
(3, 3, 18, 50, 8);

-- --------------------------------------------------------

--
-- Structure de la table `productionplanifiee`
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

--
-- Déchargement des données de la table `productionplanifiee`
--

INSERT INTO `productionplanifiee` (`id_production`, `id_produit_transforme`, `quantite`, `unite_mesure`, `date_prevue`, `duree_prevue`, `duree_reelle`, `taux_horaire`) VALUES
(2, 1, 100, 'unités', '2026-05-05', 8, NULL, 21),
(3, 18, 150, 'unités', '2026-05-06', 3, NULL, 21);

-- --------------------------------------------------------

--
-- Structure de la table `produitbrut`
--

CREATE TABLE `produitbrut` (
  `id_produit_brut` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `quantite_stock` int(11) DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `prix_unitaire_moyen` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produitbrut`
--

INSERT INTO `produitbrut` (`id_produit_brut`, `nom`, `quantite_stock`, `unite_mesure`, `prix_unitaire_moyen`) VALUES
(1, 'Tomates fraîches', 150, 'kg', 2.5),
(2, 'Oignons', 100, 'kg', 1.8),
(3, 'Pommes de terre', 250, 'kg', 1.2),
(4, 'Carottes', 180, 'kg', 1.6),
(5, 'Poivrons rouges', 75, 'kg', 3.25),
(6, 'Farine de blé', 300, 'kg', 0.95),
(7, 'Sucre', 200, 'kg', 1.1),
(8, 'Sel', 120, 'kg', 0.45),
(9, 'Huile végétale', 80, 'litre', 4.75),
(10, 'Lait cru', 500, 'litre', 1.35),
(11, 'Cacao brut', 60, 'kg', 6.8),
(12, 'Maïs', 400, 'kg', 0.85),
(13, 'Riz brut', 350, 'kg', 1.4),
(14, 'Poisson frais', 90, 'kg', 7.5),
(15, 'Mangues fraîches', 130, 'kg', 2.2),
(16, 'oeuf', 10, 'unité', 6.5);

-- --------------------------------------------------------

--
-- Structure de la table `produittransforme`
--

CREATE TABLE `produittransforme` (
  `id_produit_transforme` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `quantite_stock` float DEFAULT NULL,
  `unite_mesure` varchar(50) DEFAULT NULL,
  `prix_unitaire_moyen` float DEFAULT 0,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produittransforme`
--

INSERT INTO `produittransforme` (`id_produit_transforme`, `nom`, `quantite_stock`, `unite_mesure`, `prix_unitaire_moyen`, `commentaire`) VALUES
(1, 'Sauce tomate', 200, 'unités', 10.5, 'Qui n\'utilise pas la sauce tomate quand il cuisine. Hahaha!'),
(12, 'Choucroute nature', 120, 'pots', 8, 'Produit fermenté à base de chou blanc.'),
(13, 'Kimchi traditionnel', 95, 'pots', 9.5, 'Produit épicé inspiré de la fermentation coréenne.'),
(14, 'Tempeh de pois', 80, 'unités', 7.25, 'Produit végétal riche en protéines.'),
(15, 'Betteraves lacto-fermentées', 70, 'pots', 6.75, 'Produit à base de betteraves fermentées.'),
(16, 'Cornichons à l’aneth', 110, 'pots', 7, 'Cornichons fermentés avec aneth et épices.'),
(17, 'Carottes lacto-fermentées', 85, 'pots', 6.5, 'Carottes fermentées naturellement.'),
(18, 'Sauerkraut rouge', 60, 'pots', 8.25, 'Choucroute rouge à base de chou rouge fermenté.'),
(19, 'Kombucha gingembre', 150, 'bouteilles', 5.75, 'Boisson fermentée au gingembre.'),
(20, 'Kombucha framboise', 130, 'bouteilles', 6, 'Boisson fermentée aromatisée à la framboise.'),
(21, 'Miso de pois jaunes', 45, 'pots', 10.5, 'Pâte fermentée à base de pois jaunes.');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `rapportconsommationbruts`
-- (Voir ci-dessous la vue réelle)
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
-- Doublure de structure pour la vue `rapportcoutsproduction`
-- (Voir ci-dessous la vue réelle)
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
-- Doublure de structure pour la vue `rapportventesparproduit`
-- (Voir ci-dessous la vue réelle)
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
-- Structure de la table `recette`
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
-- Doublure de structure pour la vue `vuecommandeinventaire`
-- (Voir ci-dessous la vue réelle)
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
-- Doublure de structure pour la vue `vueproductioninventaire`
-- (Voir ci-dessous la vue réelle)
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
-- Structure de la vue `rapportconsommationbruts`
--
DROP TABLE IF EXISTS `rapportconsommationbruts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportconsommationbruts`  AS SELECT `pb`.`id_produit_brut` AS `id_produit_brut`, `pb`.`nom` AS `nom`, date_format(`pp`.`date_prevue`,'%Y-%m') AS `periode`, sum(`r`.`quantite` * `pp`.`quantite`) AS `consommation_totale`, avg(`r`.`quantite` * `pp`.`quantite`) AS `consommation_moyenne` FROM ((`productionplanifiee` `pp` join `recette` `r` on(`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pb`.`id_produit_brut`, date_format(`pp`.`date_prevue`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapportcoutsproduction`
--
DROP TABLE IF EXISTS `rapportcoutsproduction`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportcoutsproduction`  AS SELECT `pp`.`id_production` AS `id_production`, `pt`.`nom` AS `produit`, sum(`r`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `cout_matieres`, `pp`.`duree_reelle`* `pp`.`taux_horaire` AS `cout_main_oeuvre`, sum(`r`.`quantite` * `pb`.`prix_unitaire_moyen`) + `pp`.`duree_reelle` * `pp`.`taux_horaire` AS `cout_total` FROM (((`productionplanifiee` `pp` join `produittransforme` `pt` on(`pp`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) join `recette` `r` on(`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pp`.`id_production` ;

-- --------------------------------------------------------

--
-- Structure de la vue `rapportventesparproduit`
--
DROP TABLE IF EXISTS `rapportventesparproduit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rapportventesparproduit`  AS SELECT `pt`.`id_produit_transforme` AS `id_produit_transforme`, `pt`.`nom` AS `nom`, date_format(`cc`.`date_commande`,'%Y-%m') AS `periode`, sum(`lcc`.`quantite`) AS `quantite_vendue`, sum(`lcc`.`quantite` * `lcc`.`prix_vente`) AS `revenu_total`, sum(`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `cout_estime`, sum(`lcc`.`quantite` * `lcc`.`prix_vente`) - sum(`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`) AS `profit` FROM ((((`lignecommandeclient` `lcc` join `commandeclient` `cc` on(`lcc`.`id_commande_client` = `cc`.`id_commande_client`)) join `produittransforme` `pt` on(`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) join `recette` `r` on(`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) GROUP BY `pt`.`id_produit_transforme`, date_format(`cc`.`date_commande`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Structure de la vue `vuecommandeinventaire`
--
DROP TABLE IF EXISTS `vuecommandeinventaire`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vuecommandeinventaire`  AS SELECT `lcc`.`id_produit_transforme` AS `id_produit_transforme`, `pt`.`nom` AS `nom`, sum(`lcc`.`quantite`) AS `quantite_commandee`, `pt`.`quantite_stock` AS `quantite_stock`, ifnull(sum(`pp`.`quantite`),0) AS `production_prevue`, `pt`.`quantite_stock`+ ifnull(sum(`pp`.`quantite`),0) - sum(`lcc`.`quantite`) AS `ecart` FROM ((`lignecommandeclient` `lcc` join `produittransforme` `pt` on(`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`)) left join `productionplanifiee` `pp` on(`pt`.`id_produit_transforme` = `pp`.`id_produit_transforme`)) GROUP BY `lcc`.`id_produit_transforme` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vueproductioninventaire`
--
DROP TABLE IF EXISTS `vueproductioninventaire`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vueproductioninventaire`  AS SELECT `pp`.`id_production` AS `id_production`, `r`.`id_produit_brut` AS `id_produit_brut`, `pb`.`nom` AS `produit_brut`, sum(`r`.`quantite` * `pp`.`quantite`) AS `besoin_total`, `pb`.`quantite_stock` AS `quantite_stock`, ifnull(sum(`cb`.`quantite`),0) AS `quantite_commandee`, `pb`.`quantite_stock`+ ifnull(sum(`cb`.`quantite`),0) - sum(`r`.`quantite` * `pp`.`quantite`) AS `ecart` FROM (((`productionplanifiee` `pp` join `recette` `r` on(`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`)) join `produitbrut` `pb` on(`r`.`id_produit_brut` = `pb`.`id_produit_brut`)) left join `commandebrut` `cb` on(`pb`.`id_produit_brut` = `cb`.`id_produit_brut`)) GROUP BY `pp`.`id_production`, `r`.`id_produit_brut` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commandebrut`
--
ALTER TABLE `commandebrut`
  ADD PRIMARY KEY (`id_commande_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`),
  ADD KEY `id_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `commandeclient`
--
ALTER TABLE `commandeclient`
  ADD PRIMARY KEY (`id_commande_client`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`);

--
-- Index pour la table `fournisseurproduit`
--
ALTER TABLE `fournisseurproduit`
  ADD PRIMARY KEY (`id_fournisseur`,`id_produit_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`);

--
-- Index pour la table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_commande_client` (`id_commande_client`),
  ADD KEY `id_produit_transforme` (`id_produit_transforme`);

--
-- Index pour la table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  ADD PRIMARY KEY (`id_production`),
  ADD KEY `id_produit_transforme` (`id_produit_transforme`);

--
-- Index pour la table `produitbrut`
--
ALTER TABLE `produitbrut`
  ADD PRIMARY KEY (`id_produit_brut`);

--
-- Index pour la table `produittransforme`
--
ALTER TABLE `produittransforme`
  ADD PRIMARY KEY (`id_produit_transforme`);

--
-- Index pour la table `recette`
--
ALTER TABLE `recette`
  ADD PRIMARY KEY (`id_produit_transforme`,`id_produit_brut`),
  ADD KEY `id_produit_brut` (`id_produit_brut`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandebrut`
--
ALTER TABLE `commandebrut`
  MODIFY `id_commande_brut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commandeclient`
--
ALTER TABLE `commandeclient`
  MODIFY `id_commande_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  MODIFY `id_production` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `produitbrut`
--
ALTER TABLE `produitbrut`
  MODIFY `id_produit_brut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `produittransforme`
--
ALTER TABLE `produittransforme`
  MODIFY `id_produit_transforme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandebrut`
--
ALTER TABLE `commandebrut`
  ADD CONSTRAINT `commandebrut_ibfk_1` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`),
  ADD CONSTRAINT `commandebrut_ibfk_2` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Contraintes pour la table `commandeclient`
--
ALTER TABLE `commandeclient`
  ADD CONSTRAINT `commandeclient_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Contraintes pour la table `fournisseurproduit`
--
ALTER TABLE `fournisseurproduit`
  ADD CONSTRAINT `fournisseurproduit_ibfk_1` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fournisseurproduit_ibfk_2` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lignecommandeclient`
--
ALTER TABLE `lignecommandeclient`
  ADD CONSTRAINT `lignecommandeclient_ibfk_1` FOREIGN KEY (`id_commande_client`) REFERENCES `commandeclient` (`id_commande_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignecommandeclient_ibfk_2` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`);

--
-- Contraintes pour la table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  ADD CONSTRAINT `productionplanifiee_ibfk_1` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`);

--
-- Contraintes pour la table `recette`
--
ALTER TABLE `recette`
  ADD CONSTRAINT `recette_ibfk_1` FOREIGN KEY (`id_produit_transforme`) REFERENCES `produittransforme` (`id_produit_transforme`) ON DELETE CASCADE,
  ADD CONSTRAINT `recette_ibfk_2` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
