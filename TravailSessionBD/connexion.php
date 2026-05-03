<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion d'inventaire - Père Canuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="styles/connexion.css"/>
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
    </div>
  </nav>

  <main class="login-container">

    <div class="card login-card p-4">

      <div class="text-center mb-4">
        <i class="bi bi-person-circle display-4 text-success"></i>
        <h1 class="h3 mt-3">Connexion</h1>
        <p class="text-muted">
          Connectez-vous pour accéder au système.
        </p>
      </div>

      <form>
        <div class="mb-3">
          <label for="email" class="form-label">Adresse courriel</label>
          <input 
            type="email" 
            class="form-control" 
            id="email" 
            placeholder="matthewjones@gmail.com"
          >
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <input 
            type="password" 
            class="form-control" 
            id="password" 
            placeholder="Votre mot de passe"
          >
        </div>

        <div class="mb-3 form-check">
          <input 
            type="checkbox" 
            class="form-check-input" 
            id="remember"
          >
          <label class="form-check-label" for="remember">
            Se souvenir de moi
          </label>
        </div>

        <a href="index.html" class="btn btn-principal w-100">
          <i class="bi bi-box-arrow-in-right me-2"></i>
          Se connecter
        </a>
      </form>
    </div>
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

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
  </script>

</body>
</html>