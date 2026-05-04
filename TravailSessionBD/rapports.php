<?php
require_once "liaisonBD.php";

// On récupère la connexion créée dans liaisonBD.php.
// Selon ton fichier, la variable peut s'appeler $pdo ou $connexion.
if(isset($pdo))
{
    $bd = $pdo;
} 
elseif(isset($connexion))
{
    $bd = $connexion;
}
elseif (isset($conn))
{
    $bd = $conn;
}
elseif (isset($bdd))
{
    $bd = $bdd;
}
else
{
    die("Erreur : connexion à la base de données introuvable.");
}

// Récupération des filtres du formulaire
$typeRapport = $_GET['typeRapport'] ?? 'tous';
$dateDebut = $_GET['dateDebut'] ?? '';
$dateFin = $_GET['dateFin'] ?? '';

// Variables utilisées pour afficher seulement le rapport choisi
$afficherVentes = ($typeRapport == 'tous' || $typeRapport == 'ventes');
$afficherCouts = ($typeRapport == 'tous' || $typeRapport == 'couts');
$afficherConsommation = ($typeRapport == 'tous' || $typeRapport == 'consommation');

// Tableaux qui vont recevoir les résultats
$ventes = [];
$coutsProduction = [];
$consommations = [];

// Indicateurs du résumé
$ventesTotales = 0;
$coutsTotaux = 0;
$profitTotal = 0;

$messageErreur = '';

try
{
    // 1. Rapport des ventes

    $conditionVentes = " WHERE 1=1 ";
    $paramsVentes = [];

    if($dateDebut != '')
    {
        $conditionVentes .= " AND cc.date_commande >= :dateDebut ";
        $paramsVentes['dateDebut'] = $dateDebut;
    }

    if($dateFin != '')
    {
        $conditionVentes .= " AND cc.date_commande <= :dateFin ";
        $paramsVentes['dateFin'] = $dateFin;
    }

    $sqlVentes = "
        SELECT
            pt.nom AS produit,
            pt.unite_mesure,
            SUM(lcc.quantite) AS quantite_vendue,
            SUM(lcc.quantite * lcc.prix_vente) AS produit_ventes,
            SUM(lcc.quantite * IFNULL(cout.cout_unitaire, 0)) AS cout_estime,
            SUM(lcc.quantite * lcc.prix_vente) - SUM(lcc.quantite * IFNULL(cout.cout_unitaire, 0)) AS profit_estime
        FROM lignecommandeclient lcc
        INNER JOIN commandeclient cc
            ON lcc.id_commande_client = cc.id_commande_client
        INNER JOIN produittransforme pt
            ON lcc.id_produit_transforme = pt.id_produit_transforme
        LEFT JOIN (
            SELECT
                r.id_produit_transforme,
                SUM(r.quantite * pb.prix_unitaire_moyen) AS cout_unitaire
            FROM recette r
            INNER JOIN produitbrut pb
                ON r.id_produit_brut = pb.id_produit_brut
            GROUP BY r.id_produit_transforme
        ) cout
            ON pt.id_produit_transforme = cout.id_produit_transforme
        $conditionVentes
        GROUP BY pt.id_produit_transforme, pt.nom, pt.unite_mesure
        ORDER BY produit_ventes DESC
    ";

    $requete = $bd->prepare($sqlVentes);
    $requete->execute($paramsVentes);
    $ventes = $requete->fetchAll(PDO::FETCH_ASSOC);

    foreach($ventes as $ligne)
    {
        $ventesTotales += $ligne['produit_ventes'];
        $profitTotal += $ligne['profit_estime'];
    }

    /* =========================
       2. Rapport des coûts
       ========================= */

    $conditionProduction = " WHERE 1=1 ";
    $paramsProduction = [];

    if($dateDebut != '')
    {
        $conditionProduction .= " AND pp.date_prevue >= :dateDebut ";
        $paramsProduction['dateDebut'] = $dateDebut;
    }

    if($dateFin != '')
    {
        $conditionProduction .= " AND pp.date_prevue <= :dateFin ";
        $paramsProduction['dateFin'] = $dateFin;
    }

    $sqlCouts = "
        SELECT
            pp.date_prevue,
            pt.nom AS produit,
            pp.quantite,
            pp.unite_mesure,
            IFNULL(SUM(r.quantite * pp.quantite * pb.prix_unitaire_moyen), 0) AS cout_matieres,
            IFNULL(pp.duree_reelle, 0) * IFNULL(pp.taux_horaire, 0) AS cout_main_oeuvre,
            IFNULL(SUM(r.quantite * pp.quantite * pb.prix_unitaire_moyen), 0) + (IFNULL(pp.duree_reelle, 0) * IFNULL(pp.taux_horaire, 0)) AS cout_total
        FROM productionplanifiee pp
        INNER JOIN produittransforme pt
            ON pp.id_produit_transforme = pt.id_produit_transforme
        LEFT JOIN recette r
            ON pt.id_produit_transforme = r.id_produit_transforme
        LEFT JOIN produitbrut pb
            ON r.id_produit_brut = pb.id_produit_brut
        $conditionProduction
        GROUP BY pp.id_production, pp.date_prevue, pt.nom, pp.quantite, pp.unite_mesure, pp.duree_reelle, pp.taux_horaire
        ORDER BY pp.date_prevue DESC
    ";

    $requete = $bd->prepare($sqlCouts);
    $requete->execute($paramsProduction);
    $coutsProduction = $requete->fetchAll(PDO::FETCH_ASSOC);

    foreach($coutsProduction as $ligne)
    {
        $coutsTotaux += $ligne['cout_total'];
    }

    /* =========================
       3. Rapport de consommation
       ========================= */

    $sqlConsommation = "
        SELECT
            pb.nom AS produit_brut,
            pb.unite_mesure,
            pb.quantite_stock,
            SUM(r.quantite * pp.quantite) AS quantite_utilisee,
            AVG(r.quantite * pp.quantite) AS moyenne_mensuelle
        FROM productionplanifiee pp
        INNER JOIN recette r
            ON pp.id_produit_transforme = r.id_produit_transforme
        INNER JOIN produitbrut pb
            ON r.id_produit_brut = pb.id_produit_brut
        $conditionProduction
        GROUP BY pb.id_produit_brut, pb.nom, pb.unite_mesure, pb.quantite_stock
        ORDER BY quantite_utilisee DESC
    ";

    $requete = $bd->prepare($sqlConsommation);
    $requete->execute($paramsProduction);
    $consommations = $requete->fetchAll(PDO::FETCH_ASSOC);

}
catch(PDOException $e)
{
    $messageErreur = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rapports - Père Canuel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/index.css"/>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">
        <i class="bi bi-basket2-fill me-2"></i>
        La fermenterie du Père Canuel
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="produitsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Produits
            </a>
            <ul class="dropdown-menu" aria-labelledby="produitsDropdown">
              <li><a class="dropdown-item" href="produits-bruts.php">Produits bruts</a></li>
              <li><a class="dropdown-item" href="produits-transformes.php">Produits transformés</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="fournisseurs.php">Fournisseurs</a></li>
          <li class="nav-item"><a class="nav-link" href="clients.php">Clients</a></li>
          <li class="nav-item"><a class="nav-link" href="commandes.php">Commandes</a></li>
          <li class="nav-item"><a class="nav-link" href="production.php">Production</a></li>
          <li class="nav-item"><a class="nav-link active" href="rapports.php">Rapports</a></li>

          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="user-avatar me-2"><i class="bi bi-person-fill"></i></span>
              Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>Mon profil</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="connexion.php"><i class="bi bi-box-arrow-right me-2"></i>Se déconnecter</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="page-header">
    <div class="container">
      <h1>Rapports</h1>
      <p class="text-muted">Visualisation des ventes, des coûts de production et de la consommation des produits bruts.</p>
    </div>
  </header>

  <main class="container my-5">

    <?php if ($messageErreur != ''): ?>
      <div class="alert alert-danger">
        Erreur : <?= htmlspecialchars($messageErreur) ?>
      </div>
    <?php endif; ?>

    <section class="mb-5">
      <h2 class="section-title mb-4">Filtres du rapport</h2>

      <div class="card shadow-sm p-4">
        <form method="GET" action="rapports.php">
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label for="typeRapport" class="form-label">Type de rapport</label>
              <select class="form-select" id="typeRapport" name="typeRapport">
                <option value="tous" <?php if ($typeRapport == 'tous') echo 'selected'; ?>>Tous les rapports</option>
                <option value="ventes" <?php if ($typeRapport == 'ventes') echo 'selected'; ?>>Rapport des ventes par produit</option>
                <option value="couts" <?php if ($typeRapport == 'couts') echo 'selected'; ?>>Rapport des coûts de production</option>
                <option value="consommation" <?php if ($typeRapport == 'consommation') echo 'selected'; ?>>Rapport de consommation des produits bruts</option>
              </select>
            </div>

            <div class="col-md-3">
              <label for="dateDebut" class="form-label">Date de début</label>
              <input type="date" class="form-control" id="dateDebut" name="dateDebut" value="<?= htmlspecialchars($dateDebut) ?>">
            </div>

            <div class="col-md-3">
              <label for="dateFin" class="form-label">Date de fin</label>
              <input type="date" class="form-control" id="dateFin" name="dateFin" value="<?= htmlspecialchars($dateFin) ?>">
            </div>

            <div class="col-md-2">
              <button type="submit" class="btn btn-principal w-100">
                <i class="bi bi-search me-2"></i>
                Générer
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section class="mb-5">
      <h2 class="section-title mb-4">Résumé des indicateurs</h2>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card shadow-sm p-4 h-100">
            <h5><i class="bi bi-cash-coin me-2 text-success"></i>Ventes totales</h5>
            <p class="display-6 fw-bold mb-1"><?= number_format($ventesTotales, 2, ',', ' ') ?> $</p>
            <p class="text-muted mb-0">Pour la période sélectionnée</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm p-4 h-100">
            <h5><i class="bi bi-box-seam me-2 text-warning"></i>Coûts de production</h5>
            <p class="display-6 fw-bold mb-1"><?= number_format($coutsTotaux, 2, ',', ' ') ?> $</p>
            <p class="text-muted mb-0">Produits bruts et main-d’œuvre</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm p-4 h-100">
            <h5><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Profit estimé</h5>
            <p class="display-6 fw-bold mb-1"><?= number_format($profitTotal, 2, ',', ' ') ?> $</p>
            <p class="text-muted mb-0">Ventes moins coûts estimés</p>
          </div>
        </div>
      </div>
    </section>

    <?php if($afficherVentes): ?>
    <section class="mb-5">
      <h2 class="section-title mb-4">Rapport des ventes par produit</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Produit</th>
              <th>Quantité vendue</th>
              <th>Produit des ventes</th>
              <th>Coût de production</th>
              <th>Profit estimé</th>
            </tr>
          </thead>

          <tbody>
            <?php if(count($ventes) == 0): ?>
              <tr>
                <td colspan="5" class="text-center text-muted">Aucune vente trouvée.</td>
              </tr>
            <?php endif; ?>

            <?php foreach($ventes as $vente): ?>
              <tr>
                <td><?= htmlspecialchars($vente['produit']) ?></td>
                <td><?= number_format($vente['quantite_vendue'], 2, ',', ' ') ?> <?= htmlspecialchars($vente['unite_mesure']) ?></td>
                <td><?= number_format($vente['produit_ventes'], 2, ',', ' ') ?> $</td>
                <td><?= number_format($vente['cout_estime'], 2, ',', ' ') ?> $</td>
                <td>
                  <?php if($vente['profit_estime'] >= 0): ?>
                    <span class="badge bg-success"><?= number_format($vente['profit_estime'], 2, ',', ' ') ?> $</span>
                  <?php else: ?>
                    <span class="badge bg-danger"><?= number_format($vente['profit_estime'], 2, ',', ' ') ?> $</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?php if($afficherCouts): ?>
    <section class="mb-5">
      <h2 class="section-title mb-4">Rapport des coûts de production</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Date prévue</th>
              <th>Produit</th>
              <th>Quantité produite</th>
              <th>Coût matières</th>
              <th>Coût main-d’œuvre</th>
              <th>Coût total</th>
            </tr>
          </thead>

          <tbody>
            <?php if(count($coutsProduction) == 0): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Aucune production trouvée.</td>
              </tr>
            <?php endif; ?>

            <?php foreach($coutsProduction as $cout): ?>
              <tr>
                <td><?= htmlspecialchars($cout['date_prevue']) ?></td>
                <td><?= htmlspecialchars($cout['produit']) ?></td>
                <td><?= number_format($cout['quantite'], 2, ',', ' ') ?> <?= htmlspecialchars($cout['unite_mesure']) ?></td>
                <td><?= number_format($cout['cout_matieres'], 2, ',', ' ') ?> $</td>
                <td><?= number_format($cout['cout_main_oeuvre'], 2, ',', ' ') ?> $</td>
                <td><span class="badge bg-primary"><?= number_format($cout['cout_total'], 2, ',', ' ') ?> $</span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?php if($afficherConsommation): ?>
    <section>
      <h2 class="section-title mb-4">Consommation des produits bruts</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Produit brut</th>
              <th>Quantité utilisée</th>
              <th>Moyenne mensuelle</th>
              <th>Tendance</th>
            </tr>
          </thead>

          <tbody>
            <?php if(count($consommations) == 0): ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Aucune consommation trouvée.</td>
              </tr>
            <?php endif; ?>

            <?php foreach($consommations as $consommation): ?>
              <?php
                $quantiteUtilisee = $consommation['quantite_utilisee'];
                $moyenne = $consommation['moyenne_mensuelle'];
                $stock = $consommation['quantite_stock'];

                if($quantiteUtilisee == 0)
                {
                    $tendance = "Aucune donnée";
                    $couleur = "bg-secondary";
                }
                elseif($stock < $quantiteUtilisee)
                {
                    $tendance = "À surveiller";
                    $couleur = "bg-warning text-dark";
                }
                else
                {
                    $tendance = "Stable";
                    $couleur = "bg-success";
                }
              ?>

              <tr>
                <td><?= htmlspecialchars($consommation['produit_brut']) ?></td>
                <td><?= number_format($quantiteUtilisee, 2, ',', ' ') ?> <?= htmlspecialchars($consommation['unite_mesure']) ?></td>
                <td><?= number_format($moyenne, 2, ',', ' ') ?> <?= htmlspecialchars($consommation['unite_mesure']) ?></td>
                <td><span class="badge <?= $couleur ?>"><?= $tendance ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>
  </main>

  <footer class="footer-pro">
    <div class="container py-4">
      <div class="row gy-4 align-items-center">

        <div class="col-lg-5 col-md-12">
          <h5 class="footer-title">
            <i class="bi bi-basket2-fill me-2"></i>
            La fermenterie du Père Canuel
          </h5>
          <p class="footer-text mb-0">
            Système de gestion d’inventaire pour le suivi des produits,
            des stocks, des commandes et de la production.
          </p>
        </div>

        <div class="col-lg-4 col-md-12">
          <h6 class="footer-subtitle">Contact</h6>
          <p class="footer-text mb-2">
            <i class="bi bi-envelope me-2"></i>
            contact@perecanuel.com
          </p>
          <p class="footer-text mb-0">
            <i class="bi bi-globe me-2"></i>
            www.perecanuel.com
          </p>
        </div>

        <div class="col-lg-3 col-md-12">
          <h6 class="footer-subtitle">Adresse</h6>
          <p class="footer-text mb-2">
            <i class="bi bi-geo-alt me-2"></i>
            Bas-Saint-Laurent, Québec
          </p>
          <p class="footer-text mb-0">
            <i class="bi bi-telephone me-2"></i>
            +1 418-555-1234
          </p>
        </div>
      </div>
      <hr class="footer-line">
      <div class="text-center">
        <p class="footer-bottom mb-1">
          © 2026 La fermenterie du Père Canuel. Tous droits réservés.
        </p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
