# Assignation massive

En anglais: _Mass Assignment_

Plusieurs frameworks web automatisent l'assignation des paramètres d'une requête HTTP vers les champs d'un objet en mémoire.
Si les propriétés autorisées ne sont pas explicitement définies dans le code, un attaquant peut modifier des champs qu'il ne devrait pas pouvoir toucher.

## Risques

Une faille d'assignation massive permet à un attaquant de modifier des données auxquelles il ne devrait pas avoir accès.
C'est souvent un moyen simple d'escalader ses privilèges, par exemple en se donnant le rôle d'administrateur.

## Comment ça fonctionne

Un patron de conception courant consiste à prendre les données d'une requête HTTP (paramètres ou JSON dans le corps) et à mettre à jour un objet en mémoire ou en base de données.

Par exemple, un formulaire de mise à jour du profil utilisateur contient les champs `nom` et `courriel`.
Si le serveur applique aveuglément tous les paramètres reçus à l'objet utilisateur, un attaquant n'a qu'à ajouter un champ `role=admin` à la requête pour s'accorder des droits administrateur.

## Protection

Lors du traitement des données d'une requête HTTP, énumérez explicitement les propriétés autorisées dans le code côté serveur.
Ne jamais passer directement les données brutes de la requête à un objet ou une requête SQL.

Par exemple en PHP, au lieu de faire :

```php
// Dangereux : toutes les colonnes peuvent être modifiées
$user->fill($_POST);
```

Faites plutôt :

```php
// Sécuritaire : seulement les champs autorisés
$user->nom    = $_POST['nom'];
$user->courriel = $_POST['courriel'];
```

> Ce document a été rédigé avec l'aide de l'intelligence artificielle.

Référence: https://www.hacksplaining.com/app/lessons/mass-assignment/prevention
