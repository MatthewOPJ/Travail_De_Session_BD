-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 06 mai 2026 à 03:02
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
(1, 'Épicerie du coin', '418-444-4444', 'contact@epicerieducoin.ca', 'www.epicerieducoin.ca', 'Paul Roy', 'Épicerie', '123 rue Saint-Germain', 'Rimouski', 'Bas-Saint-Laurent'),
(2, 'Restaurant BonGoût', '418-555-5555', 'info@bongout.ca', 'www.bongout.ca', 'Julie Bouchard', 'Restaurant', '456 rue des Chefs', 'Rimouski', 'Bas-Saint-Laurent'),
(3, 'Marché de l\'Outaouais', '819-222-3333', 'commande@marcheoutaouais.ca', 'www.marcheoutaouais.ca', 'Thomas Beaulieu', 'Marché alimentaire', '45 rue Principale', 'Gatineau', 'Outaouais'),
(4, 'Café du Coin', '418-777-1212', 'achats@cafeducoin.ca', 'www.cafeducoin.ca', 'Sarah Morin', 'Café', '89 avenue de la Gare', 'Rimouski', 'Bas-Saint-Laurent'),
(5, 'Bistro Local', '418-888-9090', 'contact@bistrolocal.ca', 'www.bistrolocal.ca', 'Nadia Fortin', 'Restaurant', '17 rue du Quai', 'Matane', 'Bas-Saint-Laurent'),
(6, 'Client privé', '418-666-6666', 'clientprive@mail.com', '', 'Marc Leblanc', 'Particulier', '789 rue des Érables', 'Rimouski', 'Bas-Saint-Laurent');

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
(1, 1, 1, 80, 92, '2026-05-01', '2026-05-08', 'En commande'),
(2, 8, 2, 40, 28, '2026-05-02', '2026-05-09', 'En commande'),
(3, 10, 3, 25, 102.5, '2026-05-03', '2026-05-11', 'Confirmée'),
(4, 7, 5, 100, 120, '2026-04-28', '2026-05-05', 'Expédiée'),
(5, 14, 4, 40, 176, '2026-05-04', '2026-05-12', 'En commande'),
(6, 17, 5, 20, 116, '2026-04-25', '2026-05-06', 'Confirmée');

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
(1, 1, '2026-05-01', '2026-05-06', 'Confirmée'),
(2, 2, '2026-05-02', '2026-05-07', 'En préparation'),
(3, 3, '2026-05-03', '2026-05-09', 'Expédiée'),
(4, 4, '2026-05-04', '2026-05-10', 'Livrée'),
(5, 5, '2026-05-04', '2026-05-11', 'Confirmée'),
(6, 6, '2026-05-04', '2026-05-12', 'En préparation');

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
(1, 'Ferme du Littoral', '418-111-1111', 'contact@fermedulittoral.ca', 'www.fermedulittoral.ca', 'Jean Tremblay'),
(2, 'Sel du Fleuve', '418-222-2222', 'info@seldufleuve.ca', 'www.seldufleuve.ca', 'Marie Gagnon'),
(3, 'Épices Boréales', '418-333-3333', 'commandes@epicesboreales.ca', 'www.epicesboreales.ca', 'Luc Martin'),
(4, 'Jardins du Bas-Saint-Laurent', '418-444-1212', 'vente@jardinsbsl.ca', 'www.jardinsbsl.ca', 'Anne Côté'),
(5, 'Coop Fermentation', '418-555-7878', 'service@coopfermentation.ca', 'www.coopfermentation.ca', 'Hugo Pelletier');

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
(1, 1, 1.15, 'kg'),
(1, 2, 1.4, 'kg'),
(1, 3, 1.05, 'kg'),
(1, 4, 1.3, 'kg'),
(2, 8, 0.7, 'kg'),
(3, 9, 3.4, 'kg'),
(3, 10, 4.1, 'kg'),
(3, 11, 4.9, 'kg'),
(3, 15, 2.9, 'kg'),
(4, 5, 1.75, 'kg'),
(4, 6, 1.55, 'kg'),
(4, 14, 4.4, 'kg'),
(5, 7, 1.2, 'kg'),
(5, 12, 1.05, 'kg'),
(5, 13, 2.7, 'kg'),
(5, 16, 0.02, 'L'),
(5, 17, 5.8, 'kg');

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
(1, 1, 1, 24, 11.2),
(2, 1, 2, 18, 13.3),
(3, 2, 5, 36, 9.8),
(4, 3, 8, 48, 8.05),
(5, 3, 9, 36, 8.4),
(6, 4, 4, 20, 9.45),
(7, 5, 3, 30, 10.15),
(8, 5, 10, 12, 14.7),
(9, 6, 6, 15, 9.1),
(10, 6, 7, 15, 11.55);

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
(1, 1, 100, 'pots', '2026-05-07', 5, 5.2, 22),
(2, 2, 80, 'pots', '2026-05-09', 4.5, 4.7, 22),
(3, 3, 120, 'unités', '2026-05-10', 6, 6, 23),
(4, 4, 70, 'pots', '2026-05-12', 4, 4.2, 22),
(5, 5, 90, 'pots', '2026-05-14', 4, NULL, 22),
(6, 8, 150, 'bouteilles', '2026-05-16', 5.5, NULL, 21),
(7, 9, 130, 'bouteilles', '2026-05-18', 5.5, NULL, 21),
(8, 10, 60, 'pots', '2026-05-20', 7, NULL, 24);

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
(1, 'Chou blanc', 350, 'kg', 12),
(2, 'Chou rouge', 180, 'kg', 14.5),
(3, 'Carottes', 220, 'kg', 11),
(4, 'Betteraves', 160, 'kg', 13.5),
(5, 'Radis daikon', 90, 'kg', 18),
(6, 'Concombres', 240, 'kg', 16),
(7, 'Pois jaunes', 300, 'kg', 12.5),
(8, 'Sel de mer', 80, 'kg', 7.5),
(9, 'Ail', 50, 'kg', 8),
(10, 'Gingembre', 45, 'kg', 6.5),
(11, 'Piment rouge', 35, 'kg', 5),
(12, 'Sucre', 120, 'kg', 11),
(13, 'Thé noir', 60, 'kg', 8.5),
(14, 'Framboises', 70, 'kg', 6),
(15, 'Aneth', 160, 'kg', 4),
(16, 'Eau filtrée', 1000, 'L', 4),
(17, 'Koji de riz', 40, 'kg', 6);

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
(1, 'Choucroute nature', 120, 'pots', 8, 'Produit fermenté à base de chou blanc.'),
(2, 'Kimchi traditionnel', 95, 'pots', 9.5, 'Produit épicé inspiré de la fermentation coréenne.'),
(3, 'Tempeh de pois', 80, 'unités', 7.25, 'Produit végétal riche en protéines.'),
(4, 'Betteraves lacto-fermentées', 70, 'pots', 6.75, 'Produit à base de betteraves fermentées.'),
(5, 'Cornichons à l\'aneth', 110, 'pots', 4, 'Cornichons fermentés avec aneth et épices.'),
(6, 'Carottes lacto-fermentées', 85, 'pots', 6.5, 'Carottes fermentées naturellement.'),
(7, 'Sauerkraut rouge', 60, 'pots', 8.25, 'Choucroute rouge à base de chou rouge fermenté.'),
(8, 'Kombucha gingembre', 150, 'bouteilles', 5.75, 'Boisson fermentée au gingembre.'),
(9, 'Kombucha framboise', 130, 'bouteilles', 6, 'Boisson fermentée aromatisée à la framboise.'),
(10, 'Miso de pois jaunes', 45, 'pots', 10.5, 'Pâte fermentée à base de pois jaunes et de koji.');

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

--
-- Déchargement des données de la table `recette`
--

INSERT INTO `recette` (`id_produit_transforme`, `id_produit_brut`, `quantite`, `unite_mesure`, `quantite_resultat`) VALUES
(1, 1, 0.6, 'kg', 1),
(1, 8, 0.015, 'kg', 1),
(2, 1, 0.45, 'kg', 1),
(2, 3, 0.12, 'kg', 1),
(2, 5, 0.08, 'kg', 1),
(2, 8, 0.012, 'kg', 1),
(2, 9, 0.02, 'kg', 1),
(2, 10, 0.02, 'kg', 1),
(2, 11, 0.015, 'kg', 1),
(3, 7, 0.5, 'kg', 1),
(3, 16, 0.2, 'L', 1),
(4, 4, 0.55, 'kg', 1),
(4, 8, 0.015, 'kg', 1),
(4, 9, 0.01, 'kg', 1),
(5, 6, 0.6, 'kg', 1),
(5, 8, 0.012, 'kg', 1),
(5, 9, 0.01, 'kg', 1),
(5, 15, 0.015, 'kg', 1),
(6, 3, 0.55, 'kg', 1),
(6, 8, 0.015, 'kg', 1),
(6, 10, 0.01, 'kg', 1),
(7, 2, 0.6, 'kg', 1),
(7, 8, 0.015, 'kg', 1),
(8, 10, 0.03, 'kg', 1),
(8, 12, 0.06, 'kg', 1),
(8, 13, 0.02, 'kg', 1),
(8, 16, 0.75, 'L', 1),
(9, 12, 0.06, 'kg', 1),
(9, 13, 0.02, 'kg', 1),
(9, 14, 0.1, 'kg', 1),
(9, 16, 0.75, 'L', 1),
(10, 7, 0.45, 'kg', 1),
(10, 8, 0.03, 'kg', 1),
(10, 17, 0.15, 'kg', 1);

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
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commandebrut`
--
ALTER TABLE `commandebrut`
  MODIFY `id_commande_brut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `productionplanifiee`
--
ALTER TABLE `productionplanifiee`
  MODIFY `id_production` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `produitbrut`
--
ALTER TABLE `produitbrut`
  MODIFY `id_produit_brut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `produittransforme`
--
ALTER TABLE `produittransforme`
  MODIFY `id_produit_transforme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Contraintes pour la table `fournisseurproduit`
--
ALTER TABLE `fournisseurproduit`
  ADD CONSTRAINT `fournisseurproduit_ibfk_1` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fournisseurproduit_ibfk_2` FOREIGN KEY (`id_produit_brut`) REFERENCES `produitbrut` (`id_produit_brut`) ON DELETE CASCADE;

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
