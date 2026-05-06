<?php
require_once "liaisonBD.php";

// On récupère la connexion à la base de données.
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
    if (isset($_POST['ajouter'])) {
        $idClient = $_POST['id_client'];
        $idProduit = $_POST['id_produit_transforme'];
        $quantite = $_POST['quantite'];
        $prixVente = $_POST['prix_vente'];
        $dateCommande = $_POST['date_commande'];
        $statut = $_POST['statut'];

        if ($idClient != "" && $idProduit != "" && $quantite != "" && $prixVente != "" && $dateCommande != "" && $statut != "") {
            // On ajoute d'abord la commande du client.
            $sql = "INSERT INTO commandeclient (id_client, date_commande, date_reception_prevue, statut)
                    VALUES (:idClient, :dateCommande, NULL, :statut)";

            $requete = $bd->prepare($sql);
            $requete->execute([
                'idClient' => $idClient,
                'dateCommande' => $dateCommande,
                'statut' => $statut
            ]);

            // On récupère l'identifiant de la commande qui vient d'être ajoutée.
            $idCommande = $bd->lastInsertId();

            // On ajoute ensuite le produit commandé dans la table lignecommandeclient.
            $sql = "INSERT INTO lignecommandeclient (id_commande_client, id_produit_transforme, quantite, prix_vente)
                    VALUES (:idCommande, :idProduit, :quantite, :prixVente)";

            $requete = $bd->prepare($sql);
            $requete->execute([
                'idCommande' => $idCommande,
                'idProduit' => $idProduit,
                'quantite' => $quantite,
                'prixVente' => $prixVente
            ]);

            $message = "Commande ajoutée avec succès.";
        } else {
            $messageErreur = "Veuillez remplir tous les champs du formulaire.";
        }
    }

    if (isset($_POST['modifier'])) {
        $idCommande = $_POST['id_commande_client'];
        $idLigne = $_POST['id_ligne'];
        $idClient = $_POST['id_client'];
        $idProduit = $_POST['id_produit_transforme'];
        $quantite = $_POST['quantite'];
        $prixVente = $_POST['prix_vente'];
        $dateCommande = $_POST['date_commande'];
        $statut = $_POST['statut'];

        if ($idCommande != "" && $idLigne != "" && $idClient != "" && $idProduit != "" && $quantite != "" && $prixVente != "" && $dateCommande != "" && $statut != "") {
            // Mise à jour de la commande.
            $sql = "UPDATE commandeclient
                    SET id_client = :idClient,
                        date_commande = :dateCommande,
                        statut = :statut
                    WHERE id_commande_client = :idCommande";

            $requete = $bd->prepare($sql);
            $requete->execute([
                'idClient' => $idClient,
                'dateCommande' => $dateCommande,
                'statut' => $statut,
                'idCommande' => $idCommande
            ]);

            // Mise à jour du produit commandé.
            $sql = "UPDATE lignecommandeclient
                    SET id_produit_transforme = :idProduit,
                        quantite = :quantite,
                        prix_vente = :prixVente
                    WHERE id_ligne = :idLigne";

            $requete = $bd->prepare($sql);
            $requete->execute([
                'idProduit' => $idProduit,
                'quantite' => $quantite,
                'prixVente' => $prixVente,
                'idLigne' => $idLigne
            ]);

            $message = "Commande modifiée avec succès.";
        } else {
            $messageErreur = "Impossible de modifier la commande : certains champs sont vides.";
        }
    }

    if (isset($_POST['supprimer'])) {
        $idCommande = $_POST['id_commande_client'];

        if ($idCommande != "") {
            // Grâce au ON DELETE CASCADE, les lignes de commande seront supprimées aussi.
            $sql = "DELETE FROM commandeclient WHERE id_commande_client = :idCommande";
            $requete = $bd->prepare($sql);
            $requete->execute(['idCommande' => $idCommande]);

            $message = "Commande supprimée avec succès.";
        }
    }


    $sqlClients = "SELECT id_client, nom FROM client ORDER BY nom";
    $requete = $bd->prepare($sqlClients);
    $requete->execute();
    $clients = $requete->fetchAll(PDO::FETCH_ASSOC);

    $sqlProduits = "SELECT id_produit_transforme, nom, unite_mesure FROM produittransforme ORDER BY nom";
    $requete = $bd->prepare($sqlProduits);
    $requete->execute();
    $produits = $requete->fetchAll(PDO::FETCH_ASSOC);


    $sqlCommandes = "
        SELECT
            cc.id_commande_client,
            cc.id_client,
            cc.date_commande,
            cc.statut,
            c.nom AS nom_client,
            lcc.id_ligne,
            lcc.id_produit_transforme,
            lcc.quantite,
            lcc.prix_vente,
            pt.nom AS nom_produit,
            pt.unite_mesure
        FROM commandeclient cc
        INNER JOIN client c
            ON cc.id_client = c.id_client
        LEFT JOIN lignecommandeclient lcc
            ON cc.id_commande_client = lcc.id_commande_client
        LEFT JOIN produittransforme pt
            ON lcc.id_produit_transforme = pt.id_produit_transforme
        ORDER BY cc.date_commande DESC, cc.id_commande_client DESC
    ";

    $requete = $bd->prepare($sqlCommandes);
    $requete->execute();
    $commandes = $requete->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $messageErreur = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion d'inventaire - Père Canuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="styles/commandes.css"/>
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
            <a class="nav-link dropdown-toggle" href="#" id="produitsDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              Produits
            </a>

            <ul class="dropdown-menu" aria-labelledby="produitsDropdown">
              <li>
                <a class="dropdown-item" href="produits-bruts.php">
                  Produits bruts
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="produits-transformes.php">
                  Produits transformés
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="fournisseurs.php">Fournisseurs</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="clients.php">Clients</a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="commandes.php">Commandes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="production.php">Production</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="rapports.php">Rapports</a>
          </li>

          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center"
              href="#" id="userDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
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

              <li>
                <hr class="dropdown-divider">
              </li>

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
      <h1>Gestion des commandes</h1>
      <p class="text-muted">
        Créez et suivez les commandes des clients.
      </p>
    </div>
  </header>

  <main class="container my-5">

    <?php if ($message != "") { ?>
      <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php } ?>

    <?php if ($messageErreur != "") { ?>
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?php echo htmlspecialchars($messageErreur); ?>
      </div>
    <?php } ?>

    <section class="mb-5">
      <h2 class="section-title mb-4">Nouvelle commande</h2>

      <div class="card p-4 shadow-sm">
        <form method="POST" action="">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="id_client" class="form-label">Nom du client</label>
              <select class="form-select" id="id_client" name="id_client" required>
                <option value="">Choisir un client...</option>
                <?php foreach ($clients as $client) { ?>
                  <option value="<?php echo $client['id_client']; ?>">
                    <?php echo htmlspecialchars($client['nom']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="id_produit_transforme" class="form-label">Produit commandé</label>
              <select class="form-select" id="id_produit_transforme" name="id_produit_transforme" required>
                <option value="">Choisir un produit...</option>
                <?php foreach ($produits as $produit) { ?>
                  <option value="<?php echo $produit['id_produit_transforme']; ?>">
                    <?php echo htmlspecialchars($produit['nom']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="quantite" class="form-label">Quantité</label>
              <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Ex. 20" min="1" required>
            </div>

            <div class="col-md-3">
              <label for="prix_vente" class="form-label">Prix unitaire</label>
              <input type="number" step="0.01" class="form-control" id="prix_vente" name="prix_vente" placeholder="Ex. 8.50" min="0" required>
            </div>

            <div class="col-md-3">
              <label for="date_commande" class="form-label">Date de commande</label>
              <input type="date" class="form-control" id="date_commande" name="date_commande" required>
            </div>

            <div class="col-md-3">
              <label for="statut" class="form-label">Statut</label>
              <select class="form-select" id="statut" name="statut" required>
                <option>En préparation</option>
                <option>Confirmée</option>
                <option>Expédiée</option>
                <option>Livrée</option>
              </select>
            </div>

            <div class="col-12">
              <button type="submit" name="ajouter" class="btn btn-principal">
                <i class="bi bi-cart-plus me-2"></i>
                Enregistrer la commande
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section>
      <h2 class="section-title mb-4">Liste des commandes</h2>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <th>Client</th>
              <th>Produit</th>
              <th>Quantité</th>
              <th>Prix unitaire</th>
              <th>Total</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (count($commandes) == 0) { ?>
              <tr>
                <td colspan="8" class="text-center text-muted">
                  Aucune commande enregistrée.
                </td>
              </tr>
            <?php } ?>

            <?php foreach ($commandes as $commande) { ?>
              <?php
                $total = $commande['quantite'] * $commande['prix_vente'];

                if ($commande['statut'] == "En préparation") {
                    $badge = "bg-warning text-dark";
                } elseif ($commande['statut'] == "Confirmée") {
                    $badge = "bg-success";
                } elseif ($commande['statut'] == "Expédiée") {
                    $badge = "bg-primary";
                } elseif ($commande['statut'] == "Livrée") {
                    $badge = "bg-secondary";
                } else {
                    $badge = "bg-info text-dark";
                }
              ?>
              <tr>
                <td>
                  <?php echo htmlspecialchars($commande['date_commande']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($commande['nom_client']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($commande['nom_produit']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($commande['quantite']); ?>
                  <?php echo htmlspecialchars($commande['unite_mesure']); ?>
                </td>
                <td>
                  <?php echo number_format($commande['prix_vente'], 2); ?> $
                </td>
                <td>
                  <?php echo number_format($total, 2); ?> $
                </td>
                <td>
                  <span class="badge <?php echo $badge; ?>">
                    <?php echo htmlspecialchars($commande['statut']); ?>
                  </span>
                </td>
                <td>
                  <button
                    class="btn btn-sm btn-warning me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modifierModal"
                    data-id-commande="<?php echo $commande['id_commande_client']; ?>"
                    data-id-ligne="<?php echo $commande['id_ligne']; ?>"
                    data-id-client="<?php echo $commande['id_client']; ?>"
                    data-id-produit="<?php echo $commande['id_produit_transforme']; ?>"
                    data-quantite="<?php echo $commande['quantite']; ?>"
                    data-prix="<?php echo $commande['prix_vente']; ?>"
                    data-date="<?php echo $commande['date_commande']; ?>"
                    data-statut="<?php echo htmlspecialchars($commande['statut']); ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette commande ?');">
                    <input type="hidden" name="id_commande_client" value="<?php echo $commande['id_commande_client']; ?>">
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

<div class="modal fade" id="modifierModal" tabindex="-1" aria-labelledby="modifierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST" action="">
          <div class="modal-header">
            <h5 class="modal-title" id="modifierModalLabel">
              Modifier commande
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_commande_client" id="mod_id_commande_client">
            <input type="hidden" name="id_ligne" id="mod_id_ligne">

            <div class="row g-3">
              <div class="col-md-6">
                <label for="mod_id_client" class="form-label">Nom du client</label>
                <select class="form-select" id="mod_id_client" name="id_client" required>
                  <option value="">Choisir un client...</option>
                  <?php foreach ($clients as $client) { ?>
                    <option value="<?php echo $client['id_client']; ?>">
                      <?php echo htmlspecialchars($client['nom']); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="mod_id_produit_transforme" class="form-label">Produit commandé</label>
                <select class="form-select" id="mod_id_produit_transforme" name="id_produit_transforme" required>
                  <option value="">Choisir un produit...</option>
                  <?php foreach ($produits as $produit) { ?>
                    <option value="<?php echo $produit['id_produit_transforme']; ?>">
                      <?php echo htmlspecialchars($produit['nom']); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="mod_quantite" class="form-label">Quantité</label>
                <input type="number" class="form-control" id="mod_quantite" name="quantite" min="1" required>
              </div>

              <div class="col-md-3">
                <label for="mod_prix_vente" class="form-label">Prix unitaire</label>
                <input type="number" step="0.01" class="form-control" id="mod_prix_vente" name="prix_vente" min="0" required>
              </div>

              <div class="col-md-3">
                <label for="mod_date_commande" class="form-label">Date de commande</label>
                <input type="date" class="form-control" id="mod_date_commande" name="date_commande" required>
              </div>

              <div class="col-md-3">
                <label for="mod_statut" class="form-label">Statut</label>
                <select class="form-select" id="mod_statut" name="statut" required>
                  <option>En préparation</option>
                  <option>Confirmée</option>
                  <option>Expédiée</option>
                  <option>Livrée</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Annuler
            </button>
            <button type="submit" name="modifier" class="btn btn-principal">
              <i class="bi bi-check-circle me-2"></i>
              Modifier
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    var modifierModal = document.getElementById('modifierModal');

    modifierModal.addEventListener('show.bs.modal', function (event) {
      var bouton = event.relatedTarget;

      document.getElementById('mod_id_commande_client').value = bouton.getAttribute('data-id-commande');
      document.getElementById('mod_id_ligne').value = bouton.getAttribute('data-id-ligne');
      document.getElementById('mod_id_client').value = bouton.getAttribute('data-id-client');
      document.getElementById('mod_id_produit_transforme').value = bouton.getAttribute('data-id-produit');
      document.getElementById('mod_quantite').value = bouton.getAttribute('data-quantite');
      document.getElementById('mod_prix_vente').value = bouton.getAttribute('data-prix');
      document.getElementById('mod_date_commande').value = bouton.getAttribute('data-date');
      document.getElementById('mod_statut').value = bouton.getAttribute('data-statut');
    });
  </script>

</body>
</html>
