# Conception fragile

En anglais: _Insecure Design_

Lorsqu'on conçoit une application, il faut penser à la sécurité.
Une stratégie pour sécuriser notre application est de classifier les données de notre application selon la sensibilité des données:

* Données publiques: Disponible aux visiteurs, comme un produit dans une boutique
* Données privées: Disponible aux utilisateurs connectés, comme les informations du profil
* Données restreintes: Disponible à quelques utilisateurs (comme l'admin), comme le total des ventes passées
* Données à haut risque: Ne devrait être disponible à personne, comme le hache d'un mot de passe ou un identifiant de session

Une fois les données séparées, vous avez des indices sur quoi sécuriser et quoi vérifier.

## Cycle de vie du logiciel

Pour plus de sécurité, la sécurité doit être dans le coeur du développement.
Notez que la plupart des points suivants sont surtout pour les équipes (donc moins pratique pour un travail individuel).

* Contrôle de version: Utiliser un logiciel de gestion de version tel que Git, de cette manière on peut analyser chaque commit: est-ce que ce nouveau code introduit une vulnérabilité?
* Déploiement continue: Les entreprises ont souvent un logiciel de déploiement continu tel que Jenkins. Ces logiciels vont compiler et tester à chaque commit, de cette manière un bout de code qui brise le "build" sera détecté rapidement.
* Tests: Il est possible de tester pour les failles, nous allons le voir dans le prochain chapitre.
* Révision de code:
   * En pairs: En équipe de deux, à chaque jour, analysez les commits de votre partenaire.
   * Formel: L'équipe de programmation s'assoit dans une salle de conférence et juge votre code.
   * Informel: Quelqu'un analyse votre code lorsque nécessaire.
* Déploiement automatisé: Faire un script qui publie votre site en production, utiliser un script évite les erreurs humaines.

## Principes de sécurité

* [Principe du moindre privilège](base.md)
* Valider les entrées: Toutes les données reçues en GET ou POST doivent être validés en PHP (même s'il y a une validation HTML ou JS).
* Séparation des environnements: Avec le déploiement continu vous allez avoir un "nightly build" pour tester. Sur une toute autre machine, vous allez avoir la version de production. Il faut bien séparer les deux car si la version de test introduit une faille, il ne faut pas nuire à la version de production.
* Encryption: Utiliser SSL empêche les attaques "man-in-the-middle".
* Échec sécuritaire: Il faut "catch" toutes les erreurs possibles, afficher une erreur PHP, MariaDB ou autre pourrait donner des indices sur la structure du site et donner des indices de "comment hacker".
* Observabilité: Faire des journaux (logs) de ce qui se passe sur le serveur. Habituellement Apache s'en occupe.

## Garder l'usage simple

Il est important de ne pas rendre les protocoles de sécurité trop contraignants pour l'équipe de développement ou pour les utilisateurs, sans quoi ils seront négligés. Une sécurité trop stricte finit par nuire à l'adoption et à l'utilisation de l'application.

Référence: https://www.hacksplaining.com/app/lessons/insecure-design/prevention