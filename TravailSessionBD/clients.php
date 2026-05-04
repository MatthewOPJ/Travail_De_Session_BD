<?php
require_once "liaisonBD.php";

/* AJOUTER */
if (isset($_POST["ajouter"])) {
  $sql = "INSERT INTO client 
          (nom, telephone, email, site_web, contact, type_client, adresse, ville, region)
          VALUES (:nom, :telephone, :email, :site_web, :contact, :type_client, :adresse, :ville, :region)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":nom" => $_POST["nom"],
    ":telephone" => $_POST["telephone"],
    ":email" => $_POST["email"],
    ":site_web" => $_POST["site_web"],
    ":contact" => $_POST["contact"],
    ":type_client" => $_POST["type_client"],
    ":adresse" => $_POST["adresse"],
    ":ville" => $_POST["ville"],
    ":region" => $_POST["region"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* MODIFIER */
if (isset($_POST["modifier"])) {
  $sql = "UPDATE client
          SET nom = :nom,
              telephone = :telephone,
              email = :email,
              site_web = :site_web,
              contact = :contact,
              type_client = :type_client,
              adresse = :adresse,
              ville = :ville,
              region = :region
          WHERE id_client = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":nom" => $_POST["nom"],
    ":telephone" => $_POST["telephone"],
    ":email" => $_POST["email"],
    ":site_web" => $_POST["site_web"],
    ":contact" => $_POST["contact"],
    ":type_client" => $_POST["type_client"],
    ":adresse" => $_POST["adresse"],
    ":ville" => $_POST["ville"],
    ":region" => $_POST["region"],
    ":id" => $_POST["id_client"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* SUPPRIMER */
if (isset($_POST["supprimer"])) {
  $sql = "DELETE FROM client WHERE id_client = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id" => $_POST["id_client"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* LISTE */
$sql = "SELECT * FROM client";
$stmt = $pdo->query($sql);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Clients - Père Canuel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/clients.css"/>
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
          <li class="nav-item"><a class="nav-link active" href="clients.php">Clients</a></li>
          <li class="nav-item"><a class="nav-link" href="commandes.php">Commandes</a></li>
          <li class="nav-item"><a class="nav-link" href="production.php">Production</a></li>
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
      <h1>Gestion des clients</h1>
      <p class="text-muted">Consultez, ajoutez et suivez les clients de l’entreprise.</p>
    </div>
  </header>

  <main class="container my-5">
    <section class="mb-5">
      <h2 class="section-title mb-4">Ajouter un client</h2>

      <div class="card shadow-sm p-4">
        <form method="POST">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Nom du client</label>
              <input type="text" class="form-control" name="nom" placeholder="Ex. Épicerie Chez Nous" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Type de client</label>
              <select class="form-select" name="type_client">
                <option value="">Choisir...</option>
                <option value="Particulier">Particulier</option>
                <option value="Épicerie">Épicerie</option>
                <option value="Restaurant">Restaurant</option>
                <option value="Marché public">Marché public</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" class="form-control" name="telephone" placeholder="Ex. 418-555-1234">
            </div>

            <div class="col-md-6">
              <label class="form-label">Courriel</label>
              <input type="email" class="form-control" name="email" placeholder="client@email.com">
            </div>

            <div class="col-md-6">
              <label class="form-label">Personne contact</label>
              <input type="text" class="form-control" name="contact" placeholder="Ex. Marie Tremblay">
            </div>

            <div class="col-md-6">
              <label class="form-label">Site web</label>
              <input type="text" class="form-control" name="site_web" placeholder="www.exemple.com">
            </div>

            <div class="col-md-6">
              <label class="form-label">Ville</label>
              <input type="text" class="form-control" name="ville" placeholder="Ex. Rimouski">
            </div>

            <div class="col-md-6">
              <label class="form-label">Région</label>
              <input type="text" class="form-control" name="region" placeholder="Ex. Bas-Saint-Laurent">
            </div>

            <div class="col-12">
              <label class="form-label">Adresse</label>
              <input type="text" class="form-control" name="adresse" placeholder="Adresse complète">
            </div>

            <div class="col-12">
              <button type="submit" name="ajouter" class="btn btn-principal">
                <i class="bi bi-plus-circle me-2"></i>
                Ajouter le client
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section>
      <h2 class="section-title mb-4">Liste des clients</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Type</th>
              <th>Téléphone</th>
              <th>Courriel</th>
              <th>Contact</th>
              <th>Site web</th>
              <th>Adresse</th>
              <th>Ville</th>
              <th>Région</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($clients as $client): ?>
              <tr>
                <td><?= htmlspecialchars($client["nom"]) ?></td>
                <td><?= htmlspecialchars($client["type_client"]) ?></td>
                <td><?= htmlspecialchars($client["telephone"]) ?></td>
                <td><?= htmlspecialchars($client["email"]) ?></td>
                <td><?= htmlspecialchars($client["contact"]) ?></td>
                <td><?= htmlspecialchars($client["site_web"]) ?></td>
                <td><?= htmlspecialchars($client["adresse"]) ?></td>
                <td><?= htmlspecialchars($client["ville"]) ?></td>
                <td><?= htmlspecialchars($client["region"]) ?></td>

                <td>
                  <button 
                    type="button"
                    class="btn btn-sm btn-warning me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modifierModal"
                    data-id="<?= $client["id_client"] ?>"
                    data-nom="<?= htmlspecialchars($client["nom"]) ?>"
                    data-type="<?= htmlspecialchars($client["type_client"]) ?>"
                    data-telephone="<?= htmlspecialchars($client["telephone"]) ?>"
                    data-email="<?= htmlspecialchars($client["email"]) ?>"
                    data-contact="<?= htmlspecialchars($client["contact"]) ?>"
                    data-site="<?= htmlspecialchars($client["site_web"]) ?>"
                    data-adresse="<?= htmlspecialchars($client["adresse"]) ?>"
                    data-ville="<?= htmlspecialchars($client["ville"]) ?>"
                    data-region="<?= htmlspecialchars($client["region"]) ?>"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button 
                    type="button"
                    class="btn btn-sm btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#supprimerModal"
                    data-id="<?= $client["id_client"] ?>"
                    data-nom="<?= htmlspecialchars($client["nom"]) ?>"
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

  <!-- MODIFIER MODAL -->
  <div class="modal fade" id="modifierModal" tabindex="-1" aria-labelledby="modifierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form method="POST" class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="modifierModalLabel">
            Modifier client
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_client" id="modifier_id">

          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Nom du client</label>
              <input type="text" class="form-control" name="nom" id="modifier_nom" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Type de client</label>
              <select class="form-select" name="type_client" id="modifier_type">
                <option value="">Choisir...</option>
                <option value="Particulier">Particulier</option>
                <option value="Épicerie">Épicerie</option>
                <option value="Restaurant">Restaurant</option>
                <option value="Marché public">Marché public</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" class="form-control" name="telephone" id="modifier_telephone">
            </div>

            <div class="col-md-6">
              <label class="form-label">Courriel</label>
              <input type="email" class="form-control" name="email" id="modifier_email">
            </div>

            <div class="col-md-6">
              <label class="form-label">Personne contact</label>
              <input type="text" class="form-control" name="contact" id="modifier_contact">
            </div>

            <div class="col-md-6">
              <label class="form-label">Site web</label>
              <input type="text" class="form-control" name="site_web" id="modifier_site">
            </div>

            <div class="col-md-6">
              <label class="form-label">Ville</label>
              <input type="text" class="form-control" name="ville" id="modifier_ville">
            </div>

            <div class="col-md-6">
              <label class="form-label">Région</label>
              <input type="text" class="form-control" name="region" id="modifier_region">
            </div>

            <div class="col-12">
              <label class="form-label">Adresse</label>
              <input type="text" class="form-control" name="adresse" id="modifier_adresse">
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

  <!-- SUPPRIMER MODAL -->
  <div class="modal fade" id="supprimerModal" tabindex="-1" aria-labelledby="supprimerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="supprimerModalLabel">Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_client" id="supprimer_id">

          <p>
            Voulez-vous vraiment supprimer
            <strong id="supprimer_nom"></strong> ?
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Annuler
          </button>

          <button type="submit" name="supprimer" class="btn btn-danger">
            <i class="bi bi-trash me-2"></i>
            Supprimer
          </button>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  const modifierModal = document.getElementById("modifierModal");

  modifierModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("modifier_id").value = button.getAttribute("data-id");
    document.getElementById("modifier_nom").value = button.getAttribute("data-nom");
    document.getElementById("modifier_type").value = button.getAttribute("data-type");
    document.getElementById("modifier_telephone").value = button.getAttribute("data-telephone");
    document.getElementById("modifier_email").value = button.getAttribute("data-email");
    document.getElementById("modifier_contact").value = button.getAttribute("data-contact");
    document.getElementById("modifier_site").value = button.getAttribute("data-site");
    document.getElementById("modifier_adresse").value = button.getAttribute("data-adresse");
    document.getElementById("modifier_ville").value = button.getAttribute("data-ville");
    document.getElementById("modifier_region").value = button.getAttribute("data-region");
  });

  const supprimerModal = document.getElementById("supprimerModal");

  supprimerModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("supprimer_id").value = button.getAttribute("data-id");
    document.getElementById("supprimer_nom").textContent = button.getAttribute("data-nom");
  });
  </script>

</body>
</html>