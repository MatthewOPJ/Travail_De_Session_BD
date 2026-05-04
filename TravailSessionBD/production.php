<?php
require_once "liaisonBD.php";

function e($value) {
  return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

/* AJOUTER */
if (isset($_POST["ajouter"])) {
  $sql = "INSERT INTO production 
          (id_produit_transforme, quantite, unite_mesure, date_prevue, duree_prevue, duree_reelle, taux_horaire)
          VALUES (:id_produit_transforme, :quantite, :unite_mesure, :date_prevue, :duree_prevue, :duree_reelle, :taux_horaire)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id_produit_transforme" => $_POST["id_produit_transforme"],
    ":quantite" => $_POST["quantite"],
    ":unite_mesure" => $_POST["unite_mesure"],
    ":date_prevue" => $_POST["date_prevue"],
    ":duree_prevue" => $_POST["duree_prevue"],
    ":duree_reelle" => $_POST["duree_reelle"],
    ":taux_horaire" => $_POST["taux_horaire"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* MODIFIER */
if (isset($_POST["modifier"])) {
  $sql = "UPDATE production
          SET id_produit_transforme = :id_produit_transforme,
              quantite = :quantite,
              unite_mesure = :unite_mesure,
              date_prevue = :date_prevue,
              duree_prevue = :duree_prevue,
              duree_reelle = :duree_reelle,
              taux_horaire = :taux_horaire
          WHERE id_production = :id_production";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id_produit_transforme" => $_POST["id_produit_transforme"],
    ":quantite" => $_POST["quantite"],
    ":unite_mesure" => $_POST["unite_mesure"],
    ":date_prevue" => $_POST["date_prevue"],
    ":duree_prevue" => $_POST["duree_prevue"],
    ":duree_reelle" => $_POST["duree_reelle"],
    ":taux_horaire" => $_POST["taux_horaire"],
    ":id_production" => $_POST["id_production"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* SUPPRIMER */
if (isset($_POST["supprimer"])) {
  $sql = "DELETE FROM production WHERE id_production = :id_production";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id_production" => $_POST["id_production"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* LISTE PRODUITS TRANSFORMÉS */
$sqlProduits = "SELECT id_produit_transforme, nom FROM produittransforme ORDER BY nom";
$stmtProduits = $pdo->query($sqlProduits);
$produits_transformes = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);


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
    <section class="mb-5">
      <h2 class="section-title mb-4">Planifier une production</h2>

      <div class="card shadow-sm p-4">
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Produit transformé</label>
              <select class="form-select" name="id_produit_transforme" required>
                <option value="">Choisir...</option>
                <?php foreach ($produits_transformes as $produit): ?>
                  <option value="<?= e($produit["id_produit_transforme"]) ?>">
                    <?= e($produit["nom"]) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Quantité planifiée</label>
              <input type="number" class="form-control" name="quantite" placeholder="Ex. 100" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Unité</label>
              <select class="form-select" name="unite_mesure" required>
                <option value="">Choisir...</option>
                <option value="unités">unités</option>
                <option value="pots">pots</option>
                <option value="kg">kg</option>
                <option value="L">L</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Date prévue</label>
              <input type="date" class="form-control" name="date_prevue">
            </div>

            <div class="col-md-3">
              <label class="form-label">Durée prévue</label>
              <input type="number" step="0.01" class="form-control" name="duree_prevue" placeholder="Ex. 4">
            </div>

            <div class="col-md-3">
              <label class="form-label">Durée réelle</label>
              <input type="number" step="0.01" class="form-control" name="duree_reelle" placeholder="Ex. 4.5">
            </div>

            <div class="col-md-3">
              <label class="form-label">Taux horaire</label>
              <input type="number" step="0.01" class="form-control" name="taux_horaire" placeholder="Ex. 22">
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
      <h2 class="section-title mb-4">Productions à venir</h2>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Date prévue</th>
              <th>Produit</th>
              <th>Quantité</th>
              <th>Unité</th>
              <th>Durée prévue</th>
              <th>Durée réelle</th>
              <th>Taux horaire</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($productions as $production): ?>
              <tr>
                <td><?= e($production["date_prevue"]) ?></td>
                <td><?= e($production["nom_produit"]) ?></td>
                <td><?= e($production["quantite"]) ?></td>
                <td><?= e($production["unite_mesure"]) ?></td>
                <td><?= e($production["duree_prevue"]) ?> h</td>
                <td><?= e($production["duree_reelle"]) ?> h</td>
                <td><?= e($production["taux_horaire"]) ?> $ / h</td>
                <td>
                  <button 
                    type="button"
                    class="btn btn-sm btn-warning me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modifierModal"
                    data-id="<?= e($production["id_production"]) ?>"
                    data-produit="<?= e($production["id_produit_transforme"]) ?>"
                    data-quantite="<?= e($production["quantite"]) ?>"
                    data-unite="<?= e($production["unite_mesure"]) ?>"
                    data-date="<?= e($production["date_prevue"]) ?>"
                    data-duree-prevue="<?= e($production["duree_prevue"]) ?>"
                    data-duree-reelle="<?= e($production["duree_reelle"]) ?>"
                    data-taux="<?= e($production["taux_horaire"]) ?>"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button 
                    type="button"
                    class="btn btn-sm btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#supprimerModal"
                    data-id="<?= e($production["id_production"]) ?>"
                    data-produit-nom="<?= e($production["nom_produit"]) ?>"
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
            Modifier production
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_production" id="modifier_id">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Produit transformé</label>
              <select class="form-select" name="id_produit_transforme" id="modifier_produit" required>
                <option value="">Choisir...</option>
                <?php foreach ($produits_transformes as $produit): ?>
                  <option value="<?= e($produit["id_produit_transforme"]) ?>">
                    <?= e($produit["nom"]) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Quantité planifiée</label>
              <input type="number" class="form-control" name="quantite" id="modifier_quantite" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Unité</label>
              <select class="form-select" name="unite_mesure" id="modifier_unite" required>
                <option value="unités">unités</option>
                <option value="pots">pots</option>
                <option value="kg">kg</option>
                <option value="L">L</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Date prévue</label>
              <input type="date" class="form-control" name="date_prevue" id="modifier_date">
            </div>

            <div class="col-md-3">
              <label class="form-label">Durée prévue</label>
              <input type="number" step="0.01" class="form-control" name="duree_prevue" id="modifier_duree_prevue">
            </div>

            <div class="col-md-3">
              <label class="form-label">Durée réelle</label>
              <input type="number" step="0.01" class="form-control" name="duree_reelle" id="modifier_duree_reelle">
            </div>

            <div class="col-md-3">
              <label class="form-label">Taux horaire</label>
              <input type="number" step="0.01" class="form-control" name="taux_horaire" id="modifier_taux">
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
          <input type="hidden" name="id_production" id="supprimer_id">

          <p>
            Voulez-vous vraiment supprimer la production de
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
    document.getElementById("modifier_produit").value = button.getAttribute("data-produit");
    document.getElementById("modifier_quantite").value = button.getAttribute("data-quantite");
    document.getElementById("modifier_unite").value = button.getAttribute("data-unite");
    document.getElementById("modifier_date").value = button.getAttribute("data-date");
    document.getElementById("modifier_duree_prevue").value = button.getAttribute("data-duree-prevue");
    document.getElementById("modifier_duree_reelle").value = button.getAttribute("data-duree-reelle");
    document.getElementById("modifier_taux").value = button.getAttribute("data-taux");
  });

  const supprimerModal = document.getElementById("supprimerModal");

  supprimerModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("supprimer_id").value = button.getAttribute("data-id");
    document.getElementById("supprimer_nom").textContent = button.getAttribute("data-produit-nom");
  });
  </script>
</body>
</html>