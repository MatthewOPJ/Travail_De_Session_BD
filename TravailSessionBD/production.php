<?php
require_once "liaisonBD.php";

// On récupère la connexion à la base de données.
// Selon ton fichier liaisonBD.php, le nom de la variable peut être différent.
if (isset($pdo)) {
    $bd = $pdo;
} elseif (isset($connexion)) {
    $bd = $connexion;
} elseif (isset($conn)) {
    $bd = $conn;
} elseif (isset($bdd)) {
    $bd = $bdd;
} else {
    die("Erreur : connexion à la base de données introuvable.");
}

$message = "";
$messageErreur = "";

try {
    /* =========================
       Ajouter une production
       ========================= */
    if (isset($_POST['ajouter'])) {
        $idProduit = $_POST['id_produit_transforme'];
        $quantite = $_POST['quantite'];
        $unite = $_POST['unite_mesure'];
        $datePrevue = $_POST['date_prevue'];
        $dureePrevue = $_POST['duree_prevue'];
        $tauxHoraire = $_POST['taux_horaire'];

        if ($idProduit != "" && $quantite != "" && $unite != "" && $datePrevue != "" && $dureePrevue != "" && $tauxHoraire != "") {
            $sql = "INSERT INTO productionplanifiee
                    (id_produit_transforme, quantite, unite_mesure, date_prevue, duree_prevue, duree_reelle, taux_horaire)
                    VALUES
                    (:idProduit, :quantite, :unite, :datePrevue, :dureePrevue, NULL, :tauxHoraire)";

            $requete = $bd->prepare($sql);
            $requete->execute([
                'idProduit' => $idProduit,
                'quantite' => $quantite,
                'unite' => $unite,
                'datePrevue' => $datePrevue,
                'dureePrevue' => $dureePrevue,
                'tauxHoraire' => $tauxHoraire
            ]);

            $message = "Production ajoutée avec succès.";
        } else {
            $messageErreur = "Veuillez remplir tous les champs du formulaire.";
        }
    }

    /* =========================
       Modifier une production
       ========================= */
    if (isset($_POST['modifier'])) {
        $idProduction = $_POST['id_production'];
        $idProduit = $_POST['id_produit_transforme'];
        $quantite = $_POST['quantite'];
        $unite = $_POST['unite_mesure'];
        $datePrevue = $_POST['date_prevue'];
        $dureePrevue = $_POST['duree_prevue'];
        $tauxHoraire = $_POST['taux_horaire'];

        $sql = "UPDATE productionplanifiee
                SET id_produit_transforme = :idProduit,
                    quantite = :quantite,
                    unite_mesure = :unite,
                    date_prevue = :datePrevue,
                    duree_prevue = :dureePrevue,
                    taux_horaire = :tauxHoraire
                WHERE id_production = :idProduction";

        $requete = $bd->prepare($sql);
        $requete->execute([
            'idProduit' => $idProduit,
            'quantite' => $quantite,
            'unite' => $unite,
            'datePrevue' => $datePrevue,
            'dureePrevue' => $dureePrevue,
            'tauxHoraire' => $tauxHoraire,
            'idProduction' => $idProduction
        ]);

        $message = "Production modifiée avec succès.";
    }

    /* =========================
       Supprimer une production
       ========================= */
    if (isset($_POST['supprimer'])) {
        $idProduction = $_POST['id_production'];

        $sql = "DELETE FROM productionplanifiee WHERE id_production = :idProduction";
        $requete = $bd->prepare($sql);
        $requete->execute(['idProduction' => $idProduction]);

        $message = "Production supprimée avec succès.";
    }

    /* =========================
       Liste des produits transformés
       ========================= */
    $sqlProduits = "SELECT id_produit_transforme, nom, unite_mesure
                    FROM produittransforme
                    ORDER BY nom";
    $requete = $bd->prepare($sqlProduits);
    $requete->execute();
    $produitsTransformes = $requete->fetchAll(PDO::FETCH_ASSOC);

    /* =========================
       Liste des productions planifiées
       ========================= */
    $sqlProductions = "SELECT pp.id_production,
                              pp.id_produit_transforme,
                              pp.quantite,
                              pp.unite_mesure,
                              pp.date_prevue,
                              pp.duree_prevue,
                              pp.taux_horaire,
                              pt.nom AS nom_produit
                       FROM productionplanifiee pp
                       INNER JOIN produittransforme pt
                           ON pp.id_produit_transforme = pt.id_produit_transforme
                       ORDER BY pp.date_prevue ASC, pp.id_production DESC";

    $requete = $bd->prepare($sqlProductions);
    $requete->execute();
    $productions = $requete->fetchAll(PDO::FETCH_ASSOC);

    /* =========================
       Besoins en produits bruts
       ========================= */
    $sqlInventaire = "SELECT pb.id_produit_brut,
                             pb.nom AS produit_brut,
                             pb.unite_mesure,
                             pb.quantite_stock,
                             IFNULL(SUM(r.quantite * pp.quantite), 0) AS besoin_prevu
                      FROM produitbrut pb
                      LEFT JOIN recette r
                          ON pb.id_produit_brut = r.id_produit_brut
                      LEFT JOIN productionplanifiee pp
                          ON r.id_produit_transforme = pp.id_produit_transforme
                          AND pp.date_prevue >= CURDATE()
                      GROUP BY pb.id_produit_brut, pb.nom, pb.unite_mesure, pb.quantite_stock
                      ORDER BY pb.nom";

    $requete = $bd->prepare($sqlInventaire);
    $requete->execute();
    $inventaire = $requete->fetchAll(PDO::FETCH_ASSOC);

    /* =========================
       Produits bruts en commande
       ========================= */
    $sqlCommandes = "SELECT id_produit_brut, IFNULL(SUM(quantite), 0) AS quantite_commandee
                     FROM commandebrut
                     WHERE statut != 'reçu'
                     GROUP BY id_produit_brut";

    $requete = $bd->prepare($sqlCommandes);
    $requete->execute();
    $commandes = $requete->fetchAll(PDO::FETCH_ASSOC);

    $produitsEnCommande = [];

    foreach ($commandes as $commande) {
        $produitsEnCommande[$commande['id_produit_brut']] = $commande['quantite_commandee'];
    }

    /* =========================
       Vérification des manques
       ========================= */
    $ilYAManque = false;

    foreach ($inventaire as $ligne) {
        $idBrut = $ligne['id_produit_brut'];
        $besoin = $ligne['besoin_prevu'];
        $stock = $ligne['quantite_stock'];
        $enCommande = $produitsEnCommande[$idBrut] ?? 0;

        if ($besoin > ($stock + $enCommande)) {
            $ilYAManque = true;
        }
    }

} catch (PDOException $e) {
    $messageErreur = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Production - Père Canuel</title>
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
          <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>

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
          <li class="nav-item"><a class="nav-link active" href="production.php">Production</a></li>
          <li class="nav-item"><a class="nav-link" href="rapports.php">Rapports</a></li>

          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="user-avatar me-2">
                <i class="bi bi-person-fill"></i>
              </span>
              Admin
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-person-circle me-2"></i>
                  Mon profil
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bi bi-gear me-2"></i>
                  Paramètres
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="connexion.php">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Se déconnecter
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <header class="page-header">
    <div class="container">
      <h1>Production planifiée</h1>
      <p class="text-muted">Planification des productions et suivi des besoins en matières premières.</p>
    </div>
  </header>

  <main class="container my-5">
    <?php if ($message != '') { ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php } ?>

    <?php if ($messageErreur != '') { ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($messageErreur); ?>
      </div>
    <?php } ?>

    <section class="mb-5">
      <h2 class="section-title mb-4">Planifier une production</h2>

      <div class="card shadow-sm p-4">
        <form method="post" action="">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="produitProduction" class="form-label">Produit transformé</label>
              <select class="form-select" id="produitProduction" name="id_produit_transforme" required>
                <option value="">Choisir...</option>
                <?php foreach ($produitsTransformes as $produit) { ?>
                  <option value="<?php echo $produit['id_produit_transforme']; ?>">
                    <?php echo htmlspecialchars($produit['nom']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="quantitePlanifiee" class="form-label">Quantité planifiée</label>
              <input type="number" class="form-control" id="quantitePlanifiee" name="quantite" placeholder="Ex. 100" required>
            </div>

            <div class="col-md-3">
              <label for="uniteProduction" class="form-label">Unité</label>
              <select class="form-select" id="uniteProduction" name="unite_mesure" required>
                <option value="">Choisir...</option>
                <option value="unités">unités</option>
                <option value="pots">pots</option>
                <option value="kg">kg</option>
                <option value="L">L</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="dateProduction" class="form-label">Date prévue</label>
              <input type="date" class="form-control" id="dateProduction" name="date_prevue" required>
            </div>

            <div class="col-md-4">
              <label for="dureePrevue" class="form-label">Durée prévue</label>
              <input type="number" step="0.5" class="form-control" id="dureePrevue" name="duree_prevue" placeholder="Ex. 4" required>
            </div>

            <div class="col-md-4">
              <label for="tauxHoraire" class="form-label">Taux horaire</label>
              <input type="number" step="0.01" class="form-control" id="tauxHoraire" name="taux_horaire" placeholder="Ex. 22" required>
            </div>

            <div class="col-12">
              <button type="submit" name="ajouter" class="btn btn-principal">
                <i class="bi bi-calendar-plus me-2"></i>
                Planifier la production
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section class="mb-5">
      <h2 class="section-title mb-4">Productions planifiées</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Date prévue</th>
              <th>Produit</th>
              <th>Quantité</th>
              <th>Durée prévue</th>
              <th>Taux horaire</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (count($productions) == 0) { ?>
              <tr>
                <td colspan="7" class="text-center text-muted">Aucune production planifiée.</td>
              </tr>
            <?php } ?>

            <?php foreach ($productions as $production) { ?>
              <?php
                $dateAujourdhui = date('Y-m-d');
                $statut = "Planifiée";
                $classeBadge = "bg-warning text-dark";

                if ($production['date_prevue'] < $dateAujourdhui) {
                    $statut = "Passée";
                    $classeBadge = "bg-secondary";
                } elseif ($production['date_prevue'] == $dateAujourdhui) {
                    $statut = "Aujourd'hui";
                    $classeBadge = "bg-info text-dark";
                }
              ?>
              <tr>
                <td><?php echo date('d/m/Y', strtotime($production['date_prevue'])); ?></td>
                <td><?php echo htmlspecialchars($production['nom_produit']); ?></td>
                <td><?php echo htmlspecialchars($production['quantite']); ?> <?php echo htmlspecialchars($production['unite_mesure']); ?></td>
                <td><?php echo htmlspecialchars($production['duree_prevue']); ?> h</td>
                <td><?php echo number_format($production['taux_horaire'], 2, ',', ' '); ?> $ / h</td>
                <td><span class="badge <?php echo $classeBadge; ?>"><?php echo $statut; ?></span></td>
                <td>
                  <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal<?php echo $production['id_production']; ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <form method="post" action="" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette production ?');">
                    <input type="hidden" name="id_production" value="<?php echo $production['id_production']; ?>">
                    <button type="submit" name="supprimer" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </section>

    <section>
      <h2 class="section-title mb-4">Vue production-inventaire</h2>

      <?php if ($ilYAManque) { ?>
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Certains produits bruts peuvent être insuffisants pour les productions planifiées.
        </div>
      <?php } else { ?>
        <div class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i>
          Les stocks semblent suffisants pour les productions planifiées.
        </div>
      <?php } ?>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Produit brut</th>
              <th>Besoin prévu</th>
              <th>Stock actuel</th>
              <th>En commande</th>
              <th>Situation</th>
            </tr>
          </thead>

          <tbody>
            <?php if (count($inventaire) == 0) { ?>
              <tr>
                <td colspan="5" class="text-center text-muted">Aucune donnée d'inventaire.</td>
              </tr>
            <?php } ?>

            <?php foreach ($inventaire as $ligne) { ?>
              <?php
                $idBrut = $ligne['id_produit_brut'];
                $besoin = $ligne['besoin_prevu'];
                $stock = $ligne['quantite_stock'];
                $enCommande = $produitsEnCommande[$idBrut] ?? 0;

                if ($besoin == 0) {
                    $situation = "Aucun besoin";
                    $badge = "bg-secondary";
                } elseif ($stock >= $besoin) {
                    $situation = "Suffisant";
                    $badge = "bg-success";
                } elseif (($stock + $enCommande) >= $besoin) {
                    $situation = "Suffisant avec commande";
                    $badge = "bg-warning text-dark";
                } else {
                    $situation = "Manquant";
                    $badge = "bg-danger";
                }
              ?>
              <tr>
                <td><?php echo htmlspecialchars($ligne['produit_brut']); ?></td>
                <td><?php echo number_format($besoin, 2, ',', ' '); ?> <?php echo htmlspecialchars($ligne['unite_mesure']); ?></td>
                <td><?php echo number_format($stock, 2, ',', ' '); ?> <?php echo htmlspecialchars($ligne['unite_mesure']); ?></td>
                <td><?php echo number_format($enCommande, 2, ',', ' '); ?> <?php echo htmlspecialchars($ligne['unite_mesure']); ?></td>
                <td><span class="badge <?php echo $badge; ?>"><?php echo $situation; ?></span></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <?php foreach ($productions as $production) { ?>
    <div class="modal fade" id="modifierModal<?php echo $production['id_production']; ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="post" action="">
            <div class="modal-header">
              <h5 class="modal-title">Modifier la production</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" name="id_production" value="<?php echo $production['id_production']; ?>">

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Produit transformé</label>
                  <select class="form-select" name="id_produit_transforme" required>
                    <?php foreach ($produitsTransformes as $produit) { ?>
                      <option value="<?php echo $produit['id_produit_transforme']; ?>" <?php if ($produit['id_produit_transforme'] == $production['id_produit_transforme']) { echo 'selected'; } ?>>
                        <?php echo htmlspecialchars($produit['nom']); ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Quantité</label>
                  <input type="number" class="form-control" name="quantite" value="<?php echo htmlspecialchars($production['quantite']); ?>" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Unité</label>
                  <select class="form-select" name="unite_mesure" required>
                    <option value="unités" <?php if ($production['unite_mesure'] == 'unités') { echo 'selected'; } ?>>unités</option>
                    <option value="pots" <?php if ($production['unite_mesure'] == 'pots') { echo 'selected'; } ?>>pots</option>
                    <option value="kg" <?php if ($production['unite_mesure'] == 'kg') { echo 'selected'; } ?>>kg</option>
                    <option value="L" <?php if ($production['unite_mesure'] == 'L') { echo 'selected'; } ?>>L</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Date prévue</label>
                  <input type="date" class="form-control" name="date_prevue" value="<?php echo htmlspecialchars($production['date_prevue']); ?>" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Durée prévue</label>
                  <input type="number" step="0.5" class="form-control" name="duree_prevue" value="<?php echo htmlspecialchars($production['duree_prevue']); ?>" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Taux horaire</label>
                  <input type="number" step="0.01" class="form-control" name="taux_horaire" value="<?php echo htmlspecialchars($production['taux_horaire']); ?>" required>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" name="modifier" class="btn btn-principal">
                <i class="bi bi-check-circle me-2"></i>
                Modifier
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>

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
