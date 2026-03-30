# Redirection ouverte

Essayez d'accéder au calendrier du cours qui est sur Moodle sans être connecté.
Moodle va dire que vous n'êtes pas connecté, une fois connecté il vous envoie vers la page désirée initialement.

Sur plusieurs sites on peut voir en GET un paramètre "returnURL" qui permet de faire une redirection après une connexion.

Le problème? Si vous faites le code suivant:

```php
header('location: ' . $_GET['returnurl']);
```

Le pirate pourrait envoyer un lien qui redirigerait vers un site frauduleux ou à caractère problématique.
Si vous implémentez cette fonctionnalité, gardez le "returnurl" dans la session ou assurez vous qu'il s'agit bien d'un url relatif et non un absolu.

Référence: https://www.hacksplaining.com/app/lessons/open-redirects/prevention