# Fonctionnement

On appel un script PHP via un navigateur (ou ligne de commande, mais c'est plus rare), le script PHP retourne (majoritairement) du HTML. Le script PHP génère donc une page HTML, d'où l'importance de bien séparer le HTML, CSS et JavaScript pour ne pas avoir 4 technologies dans un même fichier...

Chaque appel PHP est indépendant, chaque appel à une page PHP va (par exemple): démarrer la session, ouvrir la BD, faire les requête SQL et afficher le résultat.

## Fichier HTML/PHP

Vous pouvez mélanger le HTML et le PHP (en séparant bien la logique de l'affichage). Les scripts PHP débutent par `<?php` et terminent par `?>`. Par exemple:

```php
<?php
// Votre logique ici
?>
<HTML>
<BODY>
<?php
// code d'affichage ici
?>
</BODY>
</HTML>
```

## Fichier PHP

Un fichier qui ne contient que du PHP débute par les caractère: `<?php` mais n'aura pas la fermeture de script à la fin. Parfois l'interpréteur n'aime pas s'il y a un espace ou un enter après la fermeture de la balise PHP, ce qui cause un bug difficile à trouver...

Assurez-vous de ne pas avoir de caractère avant le `<?php`, sinon vous pouvez avoir une erreur de type "headers already sent".

## Déverminage

Parfois, selon votre configuration, PHP n'affiche pas de message d'erreur (une belle page blanche dans le navigateur par exemple). Dans ce cas vous pouvez activer les erreurs avec ces lignes magiques:

```php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

Ces configurations devraient être déjà placé de cette manière dans un environnement de développement. Par contre, dans un environnement de production il ne faut pas afficher d'erreurs et rediriger vers une page statique en cas d'erreur fatale.