<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rapports - Père Canuel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="styles/index.css"/>
  <?php require_once "liaisonBD.php"; ?>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.html">
        <i class="bi bi-basket2-fill me-2"></i>
        La fermenterie du Père Canuel
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.html">Accueil</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="produitsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Produits
            </a>
            <ul class="dropdown-menu" aria-labelledby="produitsDropdown">
              <li><a class="dropdown-item" href="produits-bruts.html">Produits bruts</a></li>
              <li><a class="dropdown-item" href="produits-transformes.html">Produits transformés</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="fournisseurs.html">Fournisseurs</a></li>
          <li class="nav-item"><a class="nav-link" href="clients.html">Clients</a></li>
          <li class="nav-item"><a class="nav-link" href="commandes.html">Commandes</a></li>
          <li class="nav-item"><a class="nav-link" href="production.html">Production</a></li>
          <li class="nav-item"><a class="nav-link active" href="rapports.html">Rapports</a></li>

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
                <a class="dropdown-item text-danger" href="connexion.html">
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
      <h1>Rapports</h1>
      <p class="text-muted">Visualisation des ventes, des coûts de production et de la consommation des produits bruts.</p>
    </div>
  </header>

  <main class="container my-5">
    <section class="mb-5">
      <h2 class="section-title mb-4">Filtres du rapport</h2>

      <div class="card shadow-sm p-4">
        <form>
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label for="typeRapport" class="form-label">Type de rapport</label>
              <select class="form-select" id="typeRapport">
                <option selected>Rapport des ventes par produit</option>
                <option>Rapport des coûts de production</option>
                <option>Rapport de consommation des produits bruts</option>
              </select>
            </div>

            <div class="col-md-3">
              <label for="dateDebut" class="form-label">Date de début</label>
              <input type="date" class="form-control" id="dateDebut">
            </div>

            <div class="col-md-3">
              <label for="dateFin" class="form-label">Date de fin</label>
              <input type="date" class="form-control" id="dateFin">
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
            <p class="display-6 fw-bold mb-1">12 450 $</p>
            <p class="text-muted mb-0">Pour la période sélectionnée</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm p-4 h-100">
            <h5><i class="bi bi-box-seam me-2 text-warning"></i>Coûts de production</h5>
            <p class="display-6 fw-bold mb-1">7 320 $</p>
            <p class="text-muted mb-0">Produits bruts et main-d’œuvre</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm p-4 h-100">
            <h5><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Profit estimé</h5>
            <p class="display-6 fw-bold mb-1">5 130 $</p>
            <p class="text-muted mb-0">Ventes moins coûts estimés</p>
          </div>
        </div>
      </div>
    </section>

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
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Choucroute nature</td>
              <td>240 unités</td>
              <td>1 920 $</td>
              <td>1 050 $</td>
              <td><span class="badge bg-success">870 $</span></td>
              <td>
                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>Kimchi traditionnel</td>
              <td>180 unités</td>
              <td>1 710 $</td>
              <td>980 $</td>
              <td><span class="badge bg-success">730 $</span></td>
              <td>
                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>Tempeh de pois</td>
              <td>300 unités</td>
              <td>2 175 $</td>
              <td>1 420 $</td>
              <td><span class="badge bg-success">755 $</span></td>
              <td>
                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>Betteraves lacto-fermentées</td>
              <td>90 pots</td>
              <td>607.50 $</td>
              <td>430 $</td>
              <td><span class="badge bg-warning text-dark">177.50 $</span></td>
              <td>
                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modifierModal">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

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
            <tr>
              <td>Pois jaunes</td>
              <td>120 kg</td>
              <td>40 kg</td>
              <td><span class="badge bg-success">Stable</span></td>
            </tr>
            <tr>
              <td>Chou vert</td>
              <td>210 kg</td>
              <td>70 kg</td>
              <td><span class="badge bg-primary">En hausse</span></td>
            </tr>
            <tr>
              <td>Sel de mer</td>
              <td>25 kg</td>
              <td>8.3 kg</td>
              <td><span class="badge bg-warning text-dark">À surveiller</span></td>
            </tr>
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
        <div class="modal-header">
          <h5 class="modal-title" id="modifierModalLabel">
            Modifier fournisseur
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <form>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nomFournisseur" class="form-label">Nom du fournisseur</label>
              <input type="text" class="form-control" id="nomFournisseur">
            </div>

            <div class="col-md-6">
              <label for="contactFournisseur" class="form-label">Personne contact</label>
              <input type="text" class="form-control" id="contactFournisseur">
            </div>

            <div class="col-md-6">
              <label for="telephoneFournisseur" class="form-label">Téléphone</label>
              <input type="text" class="form-control" id="telephoneFournisseur">
            </div>

            <div class="col-md-6">
              <label for="courrielFournisseur" class="form-label">Courriel</label>
              <input type="email" class="form-control" id="courrielFournisseur">
            </div>

            <div class="col-md-6">
              <label for="siteFournisseur" class="form-label">Site web</label>
              <input type="text" class="form-control" id="siteFournisseur">
            </div>

            <div class="col-md-6">
              <label for="produitFourni" class="form-label">Produit fourni</label>
              <input type="text" class="form-control" id="produitFourni">
            </div>

            <div class="col-md-6">
              <label for="prixProduit" class="form-label">Prix unitaire</label>
              <input type="number" class="form-control" id="prixProduit">
            </div>

            <div class="col-md-6">
              <label for="uniteProduit" class="form-label">Unité</label>
              <select class="form-select" id="uniteProduit">
                <option selected>Choisir...</option>
                <option>kg</option>
                <option>g</option>
                <option>L</option>
                <option>unité</option>
              </select>
            </div>
          </div>
        </form>        </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
