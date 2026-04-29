# TP2

Travail individuel, à remettre avant le 27 mai 8h00. Compte pour 10\% de votre session.

Le code doit être sur le git disponible ici: https://classroom.github.com/a/b8_NP1XD. Aucune autre méthode de remise ne sera acceptée.

## Mise en situation

Vous décidez de compétitionner Marketplace en créant un site de vente en ligne. Chaque visiteur pourra se créer un compte et vendre et acheter des produits.

## Fonctionnalités

Un utilisateur connecté est à la fois acheteur et vendeur.

 1. Un utilisateur anonyme peut:
    1. Créer son compte
    2. Se connecter
    3. Réinitialiser son mot de passe (j'ai oublié mon mot de passe)
       1. L'activation du compte par courriel est incluse dans ce point
    4. Se connecter avec l'option "Se souvenir de moi"
    5. Voir les articles à vendre

 2. Un utilisateur connecté peut:
    1. Se déconnecter
    2. Modifier son mot de passe

 3. Un acheteur peut:
    1. Ajouter un produit dans le panier, le "panier" ne peut avoir qu'un seul produit. Il est aussi acceptable d'envoyer directement l'acheteur au paiement sans passer par un panier.
       1. On ne gère pas le cas où 2 personnes ajoutent le même produit en même temps dans le panier
    2. Procéder au paiement, il doit saisir: Informations personnelles (nom et prénom), Adresse de facturation, Adresse de livraison, Informations de carte de crédit.
    3. Voir l'historique des achats, affichez au minimum: nom du produit, prix après taxes et frais, date de l'achat

 4. Un vendeur peut:
    1. Ajouter un article à vendre, un article doit avoir au minimum: Nom, description, image et prix (pas de quantité, un produit vendu n'est plus disponible)
       1. L'image doit être téléversée par l'utilisateur
       2. Acceptez au moins les formats jpeg et png
    2. Modifier et supprimer ses articles (seulement si l'article n'a pas encore été vendu)
    3. Voir l'historique des ventes, affichez au minimum: nom du produit, prix avant taxes et frais, date de la vente

 5. Points techniques:
    1. Vous devez vendre les produits au Québec, vous devez donc appliquer les taxes et les montants doivent être en dollars canadiens. Les taxes doivent être inscrites à part des achats, calculées à partir du sous-total.
    2. Vous devez garder des frais de service sur les produits vendus, affichez le montant (pas le pourcentage) des frais lors de l'ajout et de la modification de produit à vendre. Les frais doivent au moins couvrir les frais de transaction de Stripe, que le produit soit vendu 0.01\$ ou 1_000_000\$.
    3. Vous devez garder un journal (log) de toutes les ventes qui contient les informations utiles pour déboguer, par exemple: ID de l'utilisateur actuel, ID des produits, informations de paiements, etc.
    4. Votre site doit être protégé contre toutes les failles de sécurité vues en classe et listées dans [securite.md](securite.md).
    5. Vous devez avoir au moins 5 tests unitaires (incluant au moins 1 mock au total) et 1 test d'acceptation écrit deux fois : une fois en Gherkin et une fois en PHP.
    6. Les images téléversées doivent être redimensionnées pour respecter le format de votre site et la taille maximale d'affichage.
    7. Le paiement doit se faire avec Stripe.
    8. Les courriels doivent s'envoyer normalement sur le CPanel. Si les courriels ne fonctionnent pas ou si votre site n'est pas déployé, ajoutez une section dans votre README.md qui explique comment tester l'activation de compte et la réinitialisation de mot de passe en local. Ma limite MailTrap ne suffira pas pour tester tout le groupe.

## Spécifications

 1. Votre site doit être publié sur le CPanel (même que celui du TP1) et tous les fichiers nécessaires doivent être sur le Github (incluant les fichiers Docker). Je teste sur le CPanel s'il fonctionne, s'il y a des problèmes je teste en local, je ne dois pas chercher comment faire fonctionner votre projet.
 2. Le site doit être ergonomique, réactif et accessible. Dans ce point on inclut: remettre les informations dans les champs lorsqu'il y a une erreur (par exemple si le mot de passe est mauvais, remettre automatiquement le courriel dans le champ correspondant).
 3. Vous devez me fournir un fichier de migration qui crée les tables et insère des données de test permettant de tester toutes les fonctionnalités
 4. Ne pas mettre les fichiers/dossiers suivants dans votre git: dbdata, vendor, node_modules, journaux, images de produits qui ne sont pas dans votre migration, fichiers temporaires des tests et les fichiers de config de votre IDE (.idea, .vs)
 5. L'interface doit être en français, tout terme anglais fait perdre des points en français jusqu'à 10% de pénalité. Le code peut être en français ou en anglais (doit être uniforme).
 6. Toutes les validations doivent être effectuées en PHP. Les validations HTML/JavaScript ajoutent seulement de l'ergonomie.
 7. Vous devez fournir une vidéo qui montre toutes les fonctionnalités, incluant les messages d'erreurs possibles. Ce n'est pas un oral, je veux voir ce qui fonctionne de votre côté au cas où votre projet ne fonctionne pas de mon côté.

## README.md

À la racine de votre git vous devez avoir un README.md qui contient les informations suivantes:

 1. Comment initialiser votre site sur mon ordinateur: rouler les migrations, emplacement de la configuration (BD, BASE_URL, etc.), copier certains fichiers, etc.
 2. Auto-évaluation. Donnez-vous des points pour chaque point de la grille de correction. Expliquez brièvement si nécessaire (Exemple: 5/10, j'ai juste fait le CR du CRUD).
 3. Si les courriels ne fonctionnent pas: Explication de comment tester l'activation de compte et la réinitialisation de mot de passe en local.

## Correction

| Fonctionnalité | Fonctionnel | Fonctionnel avec bug| Fait mais non fonctionnel | Incomplet ou inexistant |
| --- | --- | --- | --- | --- |
| Authentification de base (1.1, 1.2 et 2.1) | 10 | 7 | 4 | 0 |
| Authentification avancée (1.3, 1.4 et 5.8) | 10 | 7 | 4 | 0 |
| Modification du mot de passe (2.2) | 5 | 3 | 1 | 0 |
| CRUD produits (1.5, 4.1, 4.2 et 5.2) | 15 | 10 | 6 | 0 |
| Panier et achat (3.1, 3.2 et 5.1) | 10 | 7 | 4 | 0 |
| Paiement Stripe (5.7) | 5 | 3 | 1 | 0 |
| Historiques (3.3 et 4.3) | 5 | 3 | 1 | 0 |
| Journaux (5.3) | 5 | 3 | 1 | 0 |

| Code | Fait | Partiellement | Problématique | Non-fait |
| --- | --- | --- | --- | --- |
| MVC avancé | Complet (30) | Une mauvaise séparation (24) | Plusieurs mauvaises séparations (15) | Une couche manquante (0) |
| Validations | Toutes les validations en PHP (10) | Une validation manquante (7) | Quelques validations manquantes (4) | Plusieurs validations PHP manquantes (0) |
| Sécurité, XSS | Parfait (10) | 1 oubli (8) | 1 type de XSS non protégé (4) | Plusieurs failles (0) |
| Sécurité, CSRF | Parfait (10) | 1 oubli (8) | Pas selon la technique vue en classe (4) | Plusieurs failles (0) |
| Sécurité, autres | Aucune faille (20) | 1 type de faille mal protégé (12) | 2 types de failles mal protégés (6) | Plusieurs failles (0) |
| Normes de programmation | 0 ou 1 erreur (5) | 2 ou 3 erreurs (3) | 3 à 5 erreurs (1) | 5 erreurs ou plus (0) |
| Apparence (ergonomie, réactivité et accessibilité) | Parfait (10) | 1 erreur (7) | 2 erreurs (4) | Plusieurs erreurs (0) |
| Tests (5.5) | Complet (10) | Partiellement (6) | Problématique (3) | Non-fait (0) |
| Redimensionnement des images (5.6) | Fait (5) | - | - | Non-fait (0) |
| Fichiers | Git propre, migration présente, README, etc. (5) | - | - | Non-fait (0) |

| Autre | Fait | Partiellement | Non-fait |
| --- | --- | --- | --- |
| Vidéo | Complet, incluant les erreurs possibles (10) | Fait en majorité (4) | Non-fait (0) |
| Auto-évaluation | Réaliste et détaillée (5) | - | Non-représentative (0) |

Pénalité pour le français allant jusqu'à 10\% de la note totale. Chaque terme anglais compte pour une faute.

Total:

 - Fonctionnalités: 70
 - Code: 115
 - Autre: 15
 - Total: 200

Seuil:

 * Si vous n'avez pas terminé toutes les fonctionnalités, vous serez pénalisés sur la partie code. Par exemple, si vous avez fait 75% du projet, vous aurez seulement 75% des points pour le code.
 * Le code possède un double seuil, si vous n'avez pas 50% dans le code, vous ne pouvez pas avoir plus de 50% dans le projet