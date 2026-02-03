# Variables superglobales

Les variables superglobales sont des variables accessibles dans tous vos scripts. Ces variables sont des tableaux en lecture seule. Je vais faire un résumé ici, mais consultez la liste complète sur le site de [php.net](https://www.php.net/manual/fr/language.variables.superglobals.php).

## $_SERVER

Contient des informations sur le serveur (adresse IP, domaine, etc.) et la requête (verbe HTTP, port, en-tête HTTP, etc.). Je m'en sert pour rediriger de http vers https, valider le chemin d'accès pour le /fr ou /en, etc.

## $_COOKIE

Tableau des cookies actifs. Si votre nom de cookie contient des '.', ils sont remplacés par des '_'. Si vous voulez ajouter ou modifier un cookie, vous devez utiliser la fonction [setcookie](https://www.php.net/manual/fr/function.setcookie.php). Attention, un cookie sans expiration va expirer à la fin de la session.

## Passer des valeurs de HTML vers PHP

Pour que l'utilisateur envoie des données au serveur PHP, la manière la plus simple est de passer par un form. Un form par défaut envoie en GET, mais vous pouvez changer la méthode pour envoyer en POST.

Visiter un lien (en cliquant sur le lien ou en entrant l'URL) est un appel GET aussi.

## $_GET

Les paramètres GET sont disponibles dans l'URL. Si votre form HTML n'a pas de method de spécifié, ça fonctionne en GET. Le premier paramètre GET est précédé d'un '?' et les suivants de '&'. On va utiliser GET pour: la recherche, voir un produit spécifique, etc. Bref, ce qui peut être intéressant à un utilisateur de partager ou sauvegarder un lien. Ces paramètres sont visibles même si on utilise HTTPS!

## $_POST

Les paramètres POST passent dans le body de la requête HTTP. Donc si vous utilisez HTTPS les paramètres seront encryptés. Il faut spécifier la méthode POST dans votre form HTML. Très important d'utiliser POST pour les connections et autres opérations sensibles. Habituellement toutes les opérations CRUD et login/logout sont en POST.

Les autres verbes HTTP (HEAD, PUT, DELETE, etc.) n'existent pas en PHP

## $_SESSION

Le tableau session est sauvegardé sur le serveur, son seul lien avec le client est un cookie de session. Le cookie va permettre au serveur de remplir ce tableau avec les bonnes informations, tel que l'ID de l'utilisateur connecté. De cette manière on peut faire un lien entre chaque appel au serveur (rappelez vous que chaque appel au serveur est indépendant).

Pour démarrer la session et/ou remplir le tableau avec les informations de session, il faut appeler la fonction session_start(). Ensuite, le tableau $_SESSION est accessible en lecture/écriture pour manipuler la session de l'utilisateur.

Pour terminer une session, il faut détruire le cookie de session, le nom du cookie est disponible avec la fonction "session_get_cookie_params()". Pour détruire un cookie on lui met une valeur vide et une date de fin qui est passée. Ensuite il faut utiliser la fonction session_destroy() avant de rediriger l'utilisateur.

Pour rediriger un utilisateur utilisez la fonction suivante:

```php
header('Location: index.php');
die();
```