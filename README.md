# La fermenterie du Père Canuel

## Description du projet

Ce projet est une application web de gestion d’inventaire réalisée en PHP et MySQL.  
Elle permet de gérer les produits bruts, les produits transformés, les fournisseurs, les clients, les commandes, la production et les rapports.

L’objectif est de faciliter le suivi des stocks, des ventes, des coûts de production et des besoins en matières premières pour une petite entreprise de transformation alimentaire.

---

## Technologies utilisées

- HTML5
- CSS3
- Bootstrap 5
- PHP
- MySQL
- phpMyAdmin
- PDO pour la connexion à la base de données

---

## Structure générale du projet

projet/
    index.php
    connexion.php
    liaisonBD.php
    clients.php
    fournisseurs.php
    commandes.php
    production.php
    rapports.php
    produits-bruts.php
    produits-transformes.php

styles/
    index.css
    commandes.css
    connexion.css
    clients.css
    fournisseurs.css
    commandes.css
    production.css
    rapports.css
    produits-bruts.css
    produits-transformes.css

 README.md

---

## Base de données

Le projet utilise une base de données MySQL.

Nom possible de la base de données : travail_session

Si votre base porte un autre nom, il faut modifier le nom de la base dans le fichier `liaisonBD.php`.

### Tables principales

Les principales tables utilisées sont :

- `client`
- `fournisseur`
- `produitbrut`
- `produittransforme`
- `fournisseurproduit`
- `recette`
- `commandebrut`
- `productionplanifiee`
- `commandeclient`
- `lignecommandeclient`

### Relations importantes

La table `recette` permet de faire le lien entre les produits transformés et les produits bruts.
Cela permet de calculer les besoins en matières premières et les coûts de production.

## Installation du projet

### 1. Installer XAMPP

### 2. Placer le projet dans le serveur local

### 3. Démarrer Apache et MySQL (Port 3306)

Avec XAMPP, placer le dossier du projet dans :
C:\xampp\htdocs\

### 2. Créer la base de données

Dans phpMyAdmin :

1. Créer une base de données.
2. Nommer la base, par exemple : travail_session
3. Importer le fichier SQL contenant la structure et les données.

---

### 3. Configurer la connexion

Dans le fichier `liaisonBD.php`, vérifier les informations suivantes :

```php
<?php
    $host = "localhost";
    $port = "3306";
    $dbname = "travail_session";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
```
Si votre base ne s’appelle pas `travail_session`, remplacer ce nom par le nom réel de votre base.

---

## Fonctionnalités principales

### Produits bruts

La page des produits bruts permet de :

- afficher les produits bruts ;
- consulter les quantités en stock ;
- voir l’unité de mesure ;
- voir le prix unitaire moyen ;
- ajouter, modifier ou supprimer un produit brut selon les fonctionnalités présentes dans la page.

---

### Produits transformés

La page des produits transformés permet de :

- afficher les produits finis ;
- consulter les stocks ;
- voir le prix unitaire moyen ;
- ajouter des commentaires ;
- gérer les produits transformés.

---

### Fournisseurs

La page fournisseurs permet de :

- afficher les fournisseurs ;
- enregistrer leurs informations ;
- associer certains fournisseurs aux produits bruts ;
- modifier ou supprimer un fournisseur.

---

### Clients

La page clients permet de :

- afficher les clients ;
- enregistrer leurs coordonnées ;
- modifier ou supprimer un client.

---

### Commandes

La page commandes permet de :

- créer une commande client ;
- choisir un client ;
- choisir un produit transformé ;
- indiquer une quantité ;
- indiquer une date de commande ;
- définir le statut de la commande ;
- afficher la liste des commandes ;
- modifier ou supprimer une commande.

Les commandes utilisent principalement les tables :

```text
commandeclient
lignecommandeclient
client
produittransforme
```

---

### Production

La page production permet de :

- planifier une production ;
- choisir un produit transformé ;
- indiquer une quantité prévue ;
- indiquer une durée prévue ;
- indiquer un taux horaire ;
- afficher les productions planifiées ;
- afficher les besoins en produits bruts ;
- comparer les besoins avec les stocks disponibles.

Cette page utilise principalement :

```text
productionplanifiee
produittransforme
recette
produitbrut
```

---

### Rapports

La page rapports permet de visualiser :

- les ventes par produit ;
- les coûts estimés des produits vendus ;
- le profit estimé ;
- les coûts de production ;
- la consommation des produits bruts.

Le profit estimé est calculé ainsi : 
Profit estimé = ventes totales - coûts estimés des produits vendus
Le rapport des coûts de production affiche plutôt les coûts liés aux productions planifiées :
Coût total = coût matières + coût main-d’œuvre

## Calculs utilisés

### Coût des matières

Le coût des matières est calculé à partir de la recette :
quantité utilisée dans la recette × quantité produite × prix unitaire moyen du produit brut

### Coût de main-d’œuvre
durée × taux horaire

### Coût total de production
coût matières + coût main-d’œuvre

### Profit estimé
ventes totales - coûts estimés des produits vendus

---

## Tests à effectuer

Après l’installation, tester les éléments suivants :

1. Afficher les produits bruts.
2. Afficher les produits transformés.
3. Ajouter une commande.
4. Modifier une commande.
5. Supprimer une commande.
6. Planifier une production.
7. Vérifier la vue production-inventaire.
8. Générer le rapport des ventes.
9. Générer le rapport des coûts de production.
10. Vérifier le calcul du profit estimé.

---

## Problèmes fréquents

### Erreur : Unknown database

Cela signifie que le nom de la base dans `liaisonBD.php` n’est pas le même que celui dans phpMyAdmin.

Solution :
```php
$dbname = "nom_reel_de_la_base";
```

## Membres du groupe
Olivier Messan Animaka
Matthew Philibert-Jones
Thomas Beaulieu
Thierno Amadou Sadio Sow

---
