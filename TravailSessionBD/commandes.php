<?php
require_once "liaisonBD.php";

// celui qui a nommer cette fonction, je le hais
// je modifie pas parce que la rien marche de mon côté...
function e($value) 
{
  return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

if(isset($_POST["ajouter"])) 
{
  $sql = "INSERT INTO commande 
          (client, produit, quantite, date_commande, statut)
          VALUES (:client, :produit, :quantite, :date_commande, :statut)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":client" => $_POST["client"],
    ":produit" => $_POST["produit"],
    ":quantite" => $_POST["quantite"],
    ":date_commande" => $_POST["date_commande"],
    ":statut" => $_POST["statut"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

if(isset($_POST["modifier"]))
{
  $sql = "UPDATE commande
          SET client = :client,
              produit = :produit,
              quantite = :quantite,
              date_commande = :date_commande,
              statut = :statut
          WHERE id_commande = :id_commande";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":client" => $_POST["client"],
    ":produit" => $_POST["produit"],
    ":quantite" => $_POST["quantite"],
    ":date_commande" => $_POST["date_commande"],
    ":statut" => $_POST["statut"],
    ":id_commande" => $_POST["id_commande"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

if(isset($_POST["supprimer"]))
{
  $sql = "DELETE FROM commande WHERE id_commande = :id_commande";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([":id_commande" => $_POST["id_commande"]]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

$sql = "SELECT * FROM commandeclient";
$stmt = $pdo->query($sql);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
          <a class="nav-link dropdown-toggle" href="#" id="produitsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
  <section class="mb-5">
    <h2 class="section-title mb-4">Nouvelle commande</h2>

    <div class="card p-4 shadow-sm">
      <form method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nom du client</label>
            <input type="text" class="form-control" name="client" placeholder="Thomas Beaulieu" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Produit commandé</label>
            <select class="form-select" name="produit" required>
              <option value="">Choisir un produit...</option>
              <option value="Choucroute nature 500 g">Choucroute nature 500 g</option>
              <option value="Kimchi traditionnel 500 g">Kimchi traditionnel 500 g</option>
              <option value="Cornichons à l’aneth 1 L">Cornichons à l’aneth 1 L</option>
              <option value="Betteraves lacto-fermentées 500 g">Betteraves lacto-fermentées 500 g</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Quantité</label>
            <input type="number" class="form-control" name="quantite" placeholder="Ex. 20" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Date de commande</label>
            <input type="date" class="form-control" name="date_commande" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Statut</label>
            <select class="form-select" name="statut" required>
              <option value="En préparation">En préparation</option>
              <option value="Confirmée">Confirmée</option>
              <option value="Expédiée">Expédiée</option>
              <option value="Livrée">Livrée</option>
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
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($commandes as $commande): ?>
            <tr>
              <td><?= e($commande["date_commande"]) ?></td>
              <td><?= e($commande["id_client"]) ?></td>
              <td><?= e($commande["produit"]) ?></td>
              <td><?= e($commande["quantite"]) ?></td>
              <td>
                <?php if ($commande["statut"] == "En préparation"): ?>
                  <span class="badge bg-warning text-dark">En préparation</span>
                <?php elseif ($commande["statut"] == "Confirmée"): ?>
                  <span class="badge bg-success">Confirmée</span>
                <?php elseif ($commande["statut"] == "Expédiée"): ?>
                  <span class="badge bg-primary">Expédiée</span>
                <?php elseif ($commande["statut"] == "Livrée"): ?>
                  <span class="badge bg-success">Livrée</span>
                <?php else: ?>
                  <span class="badge bg-secondary"><?= e($commande["statut"]) ?></span>
                <?php endif; ?>
              </td>

              <td>
                <button 
                  type="button"
                  class="btn btn-sm btn-warning me-1"
                  data-bs-toggle="modal"
                  data-bs-target="#modifierModal"
                  data-id="<?= e($commande["id_commande"]) ?>"
                  data-client="<?= e($commande["client"]) ?>"
                  data-produit="<?= e($commande["produit"]) ?>"
                  data-quantite="<?= e($commande["quantite"]) ?>"
                  data-date="<?= e($commande["date_commande"]) ?>"
                  data-statut="<?= e($commande["statut"]) ?>"
                >
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button 
                  type="button"
                  class="btn btn-sm btn-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#supprimerModal"
                  data-id="<?= e($commande["id_commande"]) ?>"
                  data-client="<?= e($commande["client"]) ?>"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
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
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierModalLabel">
          Modifier commande
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id_commande" id="modifier_id">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nom du client</label>
            <input type="text" class="form-control" name="client" id="modifier_client" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Produit commandé</label>
            <select class="form-select" name="produit" id="modifier_produit" required>
              <option value="">Choisir un produit...</option>
              <option value="Choucroute nature 500 g">Choucroute nature 500 g</option>
              <option value="Kimchi traditionnel 500 g">Kimchi traditionnel 500 g</option>
              <option value="Cornichons à l’aneth 1 L">Cornichons à l’aneth 1 L</option>
              <option value="Betteraves lacto-fermentées 500 g">Betteraves lacto-fermentées 500 g</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Quantité</label>
            <input type="number" class="form-control" name="quantite" id="modifier_quantite" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Date de commande</label>
            <input type="date" class="form-control" name="date_commande" id="modifier_date" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Statut</label>
            <select class="form-select" name="statut" id="modifier_statut" required>
              <option value="En préparation">En préparation</option>
              <option value="Confirmée">Confirmée</option>
              <option value="Expédiée">Expédiée</option>
              <option value="Livrée">Livrée</option>
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

<div class="modal fade" id="supprimerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id_commande" id="supprimer_id">
        Supprimer la commande de <strong id="supprimer_client"></strong> ?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" name="supprimer" class="btn btn-danger">Supprimer</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const modifierModal = document.getElementById("modifierModal");

modifierModal.addEventListener("show.bs.modal", function(event)
{
  const button = event.relatedTarget;

  document.getElementById("modifier_id").value = button.getAttribute("data-id");
  document.getElementById("modifier_client").value = button.getAttribute("data-client");
  document.getElementById("modifier_produit").value = button.getAttribute("data-produit");
  document.getElementById("modifier_quantite").value = button.getAttribute("data-quantite");
  document.getElementById("modifier_date").value = button.getAttribute("data-date");
  document.getElementById("modifier_statut").value = button.getAttribute("data-statut");
});

const supprimerModal = document.getElementById("supprimerModal");

supprimerModal.addEventListener("show.bs.modal", function(event)
{
  const button = event.relatedTarget;

  document.getElementById("supprimer_id").value = button.getAttribute("data-id");
  document.getElementById("supprimer_client").textContent = button.getAttribute("data-client");
});
</script>

</body>
</html>