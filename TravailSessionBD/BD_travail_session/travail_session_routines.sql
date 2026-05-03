CREATE DATABASE  IF NOT EXISTS `travail_session` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `travail_session`;
-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: travail_session
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `vuecommandeinventaire`
--

DROP TABLE IF EXISTS `vuecommandeinventaire`;
/*!50001 DROP VIEW IF EXISTS `vuecommandeinventaire`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vuecommandeinventaire` AS SELECT 
 1 AS `id_produit_transforme`,
 1 AS `nom`,
 1 AS `quantite_commandee`,
 1 AS `quantite_stock`,
 1 AS `production_prevue`,
 1 AS `ecart`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `rapportventesparproduit`
--

DROP TABLE IF EXISTS `rapportventesparproduit`;
/*!50001 DROP VIEW IF EXISTS `rapportventesparproduit`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `rapportventesparproduit` AS SELECT 
 1 AS `id_produit_transforme`,
 1 AS `nom`,
 1 AS `periode`,
 1 AS `quantite_vendue`,
 1 AS `revenu_total`,
 1 AS `cout_estime`,
 1 AS `profit`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `rapportconsommationbruts`
--

DROP TABLE IF EXISTS `rapportconsommationbruts`;
/*!50001 DROP VIEW IF EXISTS `rapportconsommationbruts`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `rapportconsommationbruts` AS SELECT 
 1 AS `id_produit_brut`,
 1 AS `nom`,
 1 AS `periode`,
 1 AS `consommation_totale`,
 1 AS `consommation_moyenne`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vueproductioninventaire`
--

DROP TABLE IF EXISTS `vueproductioninventaire`;
/*!50001 DROP VIEW IF EXISTS `vueproductioninventaire`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vueproductioninventaire` AS SELECT 
 1 AS `id_production`,
 1 AS `id_produit_brut`,
 1 AS `produit_brut`,
 1 AS `besoin_total`,
 1 AS `quantite_stock`,
 1 AS `quantite_commandee`,
 1 AS `ecart`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `rapportcoutsproduction`
--

DROP TABLE IF EXISTS `rapportcoutsproduction`;
/*!50001 DROP VIEW IF EXISTS `rapportcoutsproduction`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `rapportcoutsproduction` AS SELECT 
 1 AS `id_production`,
 1 AS `produit`,
 1 AS `cout_matieres`,
 1 AS `cout_main_oeuvre`,
 1 AS `cout_total`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vuecommandeinventaire`
--

/*!50001 DROP VIEW IF EXISTS `vuecommandeinventaire`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vuecommandeinventaire` AS select `lcc`.`id_produit_transforme` AS `id_produit_transforme`,`pt`.`nom` AS `nom`,sum(`lcc`.`quantite`) AS `quantite_commandee`,`pt`.`quantite_stock` AS `quantite_stock`,ifnull(sum(`pp`.`quantite`),0) AS `production_prevue`,((`pt`.`quantite_stock` + ifnull(sum(`pp`.`quantite`),0)) - sum(`lcc`.`quantite`)) AS `ecart` from ((`lignecommandeclient` `lcc` join `produittransforme` `pt` on((`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`))) left join `productionplanifiee` `pp` on((`pt`.`id_produit_transforme` = `pp`.`id_produit_transforme`))) group by `lcc`.`id_produit_transforme` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rapportventesparproduit`
--

/*!50001 DROP VIEW IF EXISTS `rapportventesparproduit`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rapportventesparproduit` AS select `pt`.`id_produit_transforme` AS `id_produit_transforme`,`pt`.`nom` AS `nom`,date_format(`cc`.`date_commande`,'%Y-%m') AS `periode`,sum(`lcc`.`quantite`) AS `quantite_vendue`,sum((`lcc`.`quantite` * `lcc`.`prix_vente`)) AS `revenu_total`,sum((`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`)) AS `cout_estime`,(sum((`lcc`.`quantite` * `lcc`.`prix_vente`)) - sum((`lcc`.`quantite` * `pb`.`prix_unitaire_moyen`))) AS `profit` from ((((`lignecommandeclient` `lcc` join `commandeclient` `cc` on((`lcc`.`id_commande_client` = `cc`.`id_commande_client`))) join `produittransforme` `pt` on((`lcc`.`id_produit_transforme` = `pt`.`id_produit_transforme`))) join `recette` `r` on((`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`))) join `produitbrut` `pb` on((`r`.`id_produit_brut` = `pb`.`id_produit_brut`))) group by `pt`.`id_produit_transforme`,`periode` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rapportconsommationbruts`
--

/*!50001 DROP VIEW IF EXISTS `rapportconsommationbruts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rapportconsommationbruts` AS select `pb`.`id_produit_brut` AS `id_produit_brut`,`pb`.`nom` AS `nom`,date_format(`pp`.`date_prevue`,'%Y-%m') AS `periode`,sum((`r`.`quantite` * `pp`.`quantite`)) AS `consommation_totale`,avg((`r`.`quantite` * `pp`.`quantite`)) AS `consommation_moyenne` from ((`productionplanifiee` `pp` join `recette` `r` on((`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`))) join `produitbrut` `pb` on((`r`.`id_produit_brut` = `pb`.`id_produit_brut`))) group by `pb`.`id_produit_brut`,`periode` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vueproductioninventaire`
--

/*!50001 DROP VIEW IF EXISTS `vueproductioninventaire`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vueproductioninventaire` AS select `pp`.`id_production` AS `id_production`,`r`.`id_produit_brut` AS `id_produit_brut`,`pb`.`nom` AS `produit_brut`,sum((`r`.`quantite` * `pp`.`quantite`)) AS `besoin_total`,`pb`.`quantite_stock` AS `quantite_stock`,ifnull(sum(`cb`.`quantite`),0) AS `quantite_commandee`,((`pb`.`quantite_stock` + ifnull(sum(`cb`.`quantite`),0)) - sum((`r`.`quantite` * `pp`.`quantite`))) AS `ecart` from (((`productionplanifiee` `pp` join `recette` `r` on((`pp`.`id_produit_transforme` = `r`.`id_produit_transforme`))) join `produitbrut` `pb` on((`r`.`id_produit_brut` = `pb`.`id_produit_brut`))) left join `commandebrut` `cb` on((`pb`.`id_produit_brut` = `cb`.`id_produit_brut`))) group by `pp`.`id_production`,`r`.`id_produit_brut` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rapportcoutsproduction`
--

/*!50001 DROP VIEW IF EXISTS `rapportcoutsproduction`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rapportcoutsproduction` AS select `pp`.`id_production` AS `id_production`,`pt`.`nom` AS `produit`,sum((`r`.`quantite` * `pb`.`prix_unitaire_moyen`)) AS `cout_matieres`,(`pp`.`duree_reelle` * `pp`.`taux_horaire`) AS `cout_main_oeuvre`,(sum((`r`.`quantite` * `pb`.`prix_unitaire_moyen`)) + (`pp`.`duree_reelle` * `pp`.`taux_horaire`)) AS `cout_total` from (((`productionplanifiee` `pp` join `produittransforme` `pt` on((`pp`.`id_produit_transforme` = `pt`.`id_produit_transforme`))) join `recette` `r` on((`pt`.`id_produit_transforme` = `r`.`id_produit_transforme`))) join `produitbrut` `pb` on((`r`.`id_produit_brut` = `pb`.`id_produit_brut`))) group by `pp`.`id_production` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-03 15:48:46
