<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion d'inventaire - Père Canuel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/produits-bruts.css"/>
  <?php require_once "liaisonBD.php"; 
  $sql = "SELECT * FROM produitbrut";
  $stmt = $pdo->query($sql);
  $produits_bruts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
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
            <a class="nav-link dropdown-toggle active" href="#" id="produitsDropdown" role="button" 
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
            <a class="nav-link" href="commandes.php">Commandes</a>
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
      <h1>Produits bruts</h1>
      <p class="text-muted">
        Gestion des matières premières utilisées dans la production.
      </p>
    </div>
  </header>

  <main class="container my-5">
    <section class="mb-5">
      <h2 class="section-title mb-4">Ajouter un produit brut</h2>

      <div class="card shadow-sm p-4">
        <form>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nomProduit" class="form-label">Nom du produit</label>
              <input 
                type="text" 
                class="form-control" 
                id="nomProduit" 
                placeholder="Ex. Pois jaunes"
              >
            </div>

            <div class="col-md-3">
              <label for="quantite" class="form-label">Quantité en stock</label>
              <input 
                type="number" 
                class="form-control" 
                id="quantite" 
                placeholder="Ex. 50"
              >
            </div>

            <div class="col-md-3">
              <label for="unite" class="form-label">Unité</label>
              <select class="form-select" id="unite">
                <option selected>Choisir...</option>
                <option>kg</option>
                <option>g</option>
                <option>L</option>
                <option>unité</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="prix" class="form-label">Prix unitaire moyen</label>
              <input type="number" class="form-control" id="prix" placeholder="Ex. 3.50">
            </div>

            <div class="col-md-4">
              <label for="fournisseur" class="form-label">Fournisseur principal</label>
              <input type="text" class="form-control" id="fournisseur" placeholder="Ex. Ferme locale">
            </div>

            <div class="col-md-4">
              <label for="statut" class="form-label">Statut</label>
              <select class="form-select" id="statut">
                <option>Disponible</option>
                <option>Stock faible</option>
                <option>Rupture de stock</option>
              </select>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-principal">
                <i class="bi bi-plus-circle me-2"></i>
                Ajouter le produit brut
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section>
      <h2 class="section-title mb-4">Liste des produits bruts</h2>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Quantité</th>
              <th>Unité</th>
              <th>Prix unitaire moyen</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($produits_bruts as $produit_brut): ?>
              <tr>
                <td><?= $produit_brut["nom"] ?></td>
                <td><?= $produit_brut["quantite_stock"] ?></td>
                <td><?= $produit_brut["unite_mesure"] ?></td>
                <td><?= $produit_brut["prix_unitaire_moyen"] ?></td>
                <td>
                  <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal">
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
      </hr>
    </div>
  </footer>

  <div class="modal fade" id="modifierModal" tabindex="-1" aria-labelledby="modifierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modifierModalLabel">
            Modifier produit brut
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <form>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nomProduit" class="form-label">Nom du produit</label>
              <input 
                type="text" 
                class="form-control" 
                id="nomProduit" 
                placeholder="Ex. Pois jaunes"
              >
            </div>

            <div class="col-md-3">
              <label for="quantite" class="form-label">Quantité en stock</label>
              <input 
                type="number" 
                class="form-control" 
                id="quantite" 
                placeholder="Ex. 50"
              >
            </div>

            <div class="col-md-3">
              <label for="unite" class="form-label">Unité</label>
              <select class="form-select" id="unite">
                <option selected>Choisir...</option>
                <option>kg</option>
                <option>g</option>
                <option>L</option>
                <option>unité</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="prix" class="form-label">Prix unitaire moyen</label>
              <input type="number" class="form-control" id="prix" placeholder="Ex. 3.50">
            </div>

            <div class="col-md-4">
              <label for="fournisseur" class="form-label">Fournisseur principal</label>
              <input type="text" class="form-control" id="fournisseur" placeholder="Ex. Ferme locale">
            </div>

            <div class="col-md-4">
              <label for="statut" class="form-label">Statut</label>
              <select class="form-select" id="statut">
                <option>Disponible</option>
                <option>Stock faible</option>
                <option>Rupture de stock</option>
              </select>
            </div>
          </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Annuler
          </button>
          <button type="submit" class="btn btn-principal" onclick="">
            <i class="bi bi-check-circle me-2"></i>
            Modifier
          </button>
        </div>
      </div>
    </div>
  </div>


  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
  </script>

</body>
</html>