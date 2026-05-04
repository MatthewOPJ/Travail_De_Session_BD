<?php
require_once "liaisonBD.php";

/* AJOUTER */
if (isset($_POST["ajouter"])) {
  $sql = "INSERT INTO produittransforme 
          (nom, quantite_stock, unite_mesure, prix_vente, commentaire)
          VALUES (:nom, :quantite_stock, :unite_mesure, :prix_unitaire_moyen, :commentaire)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":nom" => $_POST["nom"],
    ":quantite_stock" => $_POST["quantite_stock"],
    ":unite_mesure" => $_POST["unite_mesure"],
    ":prix_vente" => $_POST["prix_unitaire_moyen"],
    ":commentaire" => $_POST["commentaire"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* MODIFIER */
if (isset($_POST["modifier"])) {
  $sql = "UPDATE produittransforme
          SET nom = :nom,
              quantite_stock = :quantite_stock,
              unite_mesure = :unite_mesure,
              prix_vente = :prix_unitaire_moyen,
              commentaire = :commentaire
          WHERE id_produit_transforme = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":nom" => $_POST["nom"],
    ":quantite_stock" => $_POST["quantite_stock"],
    ":unite_mesure" => $_POST["unite_mesure"],
    ":prix_vente" => $_POST["prix_unitaire_moyen"],
    ":commentaire" => $_POST["commentaire"],
    ":id" => $_POST["id_produit_transforme"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* SUPPRIMER */
if (isset($_POST["confirmer_supprimer"])) {
  $sql = "DELETE FROM produittransforme
          WHERE id_produit_transforme = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id" => $_POST["id_produit_transforme"]
  ]);

  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

/* PRODUIT À MODIFIER */
$produit_a_modifier = null;

if (isset($_GET["modifier"])) {
  $sql = "SELECT * FROM produittransforme
          WHERE id_produit_transforme = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id" => $_GET["modifier"]
  ]);

  $produit_a_modifier = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* PRODUIT À SUPPRIMER */
$produit_a_supprimer = null;

if (isset($_GET["supprimer"])) {
  $sql = "SELECT * FROM produittransforme
          WHERE id_produit_transforme = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":id" => $_GET["supprimer"]
  ]);

  $produit_a_supprimer = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* LISTE */
$sql = "SELECT * FROM produittransforme";
$stmt = $pdo->query($sql);
$produits_transformes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion d'inventaire - Père Canuel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/produits-transformes.css"/>
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
          <a class="nav-link dropdown-toggle active" href="#" id="produitsDropdown" role="button" data-bs-toggle="dropdown">
            Produits
          </a>

          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="produits-bruts.php">Produits bruts</a>
            </li>
            <li>
              <a class="dropdown-item" href="produits-transformes.php">Produits transformés</a>
            </li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="fournisseurs.php">Fournisseurs</a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link" href="commandes.php">Commandes</a></li>
        <li class="nav-item"><a class="nav-link" href="production.php">Production</a></li>
        <li class="nav-item"><a class="nav-link" href="rapports.php">Rapports</a></li>

        <li class="nav-item dropdown ms-lg-3">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <span class="user-avatar me-2">
              <i class="bi bi-person-fill"></i>
            </span>
            Admin
          </a>

          <ul class="dropdown-menu dropdown-menu-end">
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
    <h1>Produits transformés</h1>
    <p class="text-muted">
      Gestion des produits fabriqués et vendus aux clients.
    </p>
  </div>
</header>

<main class="container my-5">

  <!-- AJOUTER -->
  <section class="mb-5">
    <h2 class="section-title mb-4">Ajouter un produit transformé</h2>

    <div class="card shadow-sm p-4">
      <form method="POST">
        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label">Nom du produit</label>
            <input 
              type="text" 
              class="form-control" 
              name="nom" 
              placeholder="Ex. Choucroute nature"
              required
            >
          </div>

          <div class="col-md-3">
            <label class="form-label">Quantité en inventaire</label>
            <input 
              type="number" 
              class="form-control" 
              name="quantite_stock" 
              placeholder="Ex. 40"
              required
            >
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

          <div class="col-md-4">
            <label class="form-label">Prix unitaire moyen</label>
            <input 
              type="number" 
              step="0.01" 
              class="form-control" 
              name="prix_unitaire_moyen" 
              placeholder="Ex. 8.00"
              required
            >
          </div>

          <div class="col-12">
            <label class="form-label">Commentaire</label>
            <textarea 
              class="form-control" 
              name="commentaire" 
              rows="3" 
              placeholder="Ex. Produit très demandé par les restaurants."
            ></textarea>
          </div>

          <div class="col-12">
            <button type="submit" name="ajouter" class="btn btn-principal">
              <i class="bi bi-plus-circle me-2"></i>
              Ajouter le produit transformé
            </button>
          </div>

        </div>
      </form>
    </div>
  </section>

  <!-- LISTE -->
  <section>
    <h2 class="section-title mb-4">Liste des produits transformés</h2>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Nom</th>
            <th>Quantité</th>
            <th>Unité</th>
            <th>Prix de vente</th>
            <th>Commentaire</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($produits_transformes as $produit): ?>
            <tr>
              <td><?= htmlspecialchars($produit["nom"]) ?></td>
              <td><?= htmlspecialchars($produit["quantite_stock"]) ?></td>
              <td><?= htmlspecialchars($produit["unite_mesure"]) ?></td>
              <td><?= htmlspecialchars($produit["prix_unitaire_moyen"]) ?> $</td>
              <td><?= htmlspecialchars($produit["commentaire"]) ?></td>

              <td class="actions-cell">
                <a 
                  href="?modifier=<?= $produit["id_produit_transforme"] ?>" 
                  class="btn btn-sm btn-warning"
                >
                  <i class="bi bi-pencil-square"></i>
                </a>

                <a 
                  href="?supprimer=<?= $produit["id_produit_transforme"] ?>" 
                  class="btn btn-sm btn-danger"
                >
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>
    </div>
  </section>

  <!-- MODIFIER SANS JS -->
  <?php if ($produit_a_modifier): ?>
    <section class="mb-5 mt-5">
      <h2 class="section-title mb-4">Modifier un produit transformé</h2>

      <div class="card shadow-sm p-4">
        <form method="POST">

          <input 
            type="hidden" 
            name="id_produit_transforme" 
            value="<?= $produit_a_modifier["id_produit_transforme"] ?>"
          >

          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Nom du produit</label>
              <input 
                type="text" 
                class="form-control" 
                name="nom"
                value="<?= htmlspecialchars($produit_a_modifier["nom"]) ?>"
                required
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">Quantité en inventaire</label>
              <input 
                type="number" 
                class="form-control" 
                name="quantite_stock"
                value="<?= htmlspecialchars($produit_a_modifier["quantite_stock"]) ?>"
                required
              >
            </div>

            <div class="col-md-3">
              <label class="form-label">Unité</label>
              <select class="form-select" name="unite_mesure" required>
                <option value="unités" <?= $produit_a_modifier["unite_mesure"] == "unités" ? "selected" : "" ?>>unités</option>
                <option value="pots" <?= $produit_a_modifier["unite_mesure"] == "pots" ? "selected" : "" ?>>pots</option>
                <option value="kg" <?= $produit_a_modifier["unite_mesure"] == "kg" ? "selected" : "" ?>>kg</option>
                <option value="L" <?= $produit_a_modifier["unite_mesure"] == "L" ? "selected" : "" ?>>L</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Prix de vente</label>
              <input 
                type="number" 
                step="0.01" 
                class="form-control" 
                name="prix_vente"
                value="<?= htmlspecialchars($produit_a_modifier["prix_vente"]) ?>"
                required
              >
            </div>

            <div class="col-12">
              <label class="form-label">Commentaire</label>
              <textarea 
                class="form-control" 
                name="commentaire" 
                rows="3"
              ><?= htmlspecialchars($produit_a_modifier["commentaire"]) ?></textarea>
            </div>

            <div class="col-12">
              <button type="submit" name="modifier" class="btn btn-principal">
                <i class="bi bi-check-circle me-2"></i>
                Enregistrer les modifications
              </button>

              <a href="<?= $_SERVER["PHP_SELF"] ?>" class="btn btn-secondary">
                Annuler
              </a>
            </div>

          </div>
        </form>
      </div>
    </section>
  <?php endif; ?>

  <!-- SUPPRIMER SANS JS -->
  <?php if ($produit_a_supprimer): ?>
    <section class="mb-5 mt-5">
      <h2 class="section-title mb-4">Supprimer un produit transformé</h2>

      <div class="card shadow-sm p-4 border-danger">
        <p>
          Voulez-vous vraiment supprimer 
          <strong><?= htmlspecialchars($produit_a_supprimer["nom"]) ?></strong> ?
        </p>

        <form method="POST">
          <input 
            type="hidden" 
            name="id_produit_transforme" 
            value="<?= $produit_a_supprimer["id_produit_transforme"] ?>"
          >

          <button type="submit" name="confirmer_supprimer" class="btn btn-danger">
            <i class="bi bi-trash me-2"></i>
            Confirmer la suppression
          </button>

          <a href="<?= $_SERVER["PHP_SELF"] ?>" class="btn btn-secondary">
            Annuler
          </a>
        </form>
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