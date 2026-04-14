# Fuite d'information

En anglais: _Information Leakage_

Sur un serveur Web plusieurs choses peuvent donner des indices sur la structure de votre serveur.
En soi ce n'est pas dangereux, mais si un pirate trouve une faille et peut facilement accéder à votre structure de fichier (par exemple), le risque est beaucoup plus grand.

## Information dans l'entête

Allez sur le site du cégep, ouvrez dans la console et allez dans l'onglet de réseau.
Rechargez la page, quelle est la version Apache utilisée?

Cette information peut être très utile pour un pirate, si la version "1.2.3" présente une faille qui est disparue dans la version "1.2.4".
Une mauvaise configuration peut même montrer la version de PHP, notre configuration Docker nous donne cette entête.
En développement (localhost) ce n'est pas grave, mais sur un serveur de production ça peut donner plus d'informations au pirate.
Pour désactiver ces entêtes:

```php
header_remove('x-powered-by');
```

Notez que pour enlever la version d'apache il faut modifier la configuration de ce dernier, ce qui n'est pas possible pour vous sur le serveur de production (donc pas votre problème).

## Utiliser des beaux URLs

Sans beaux URLs vos URLs doivent pointer directement vers des fichiers PHP.
Si les fichiers PHP sont dans l'URL, le pirate a un indice sur votre architecture, pouvant même aller jusqu'au framework et sa version.

Vous pouvez même rediriger tous les liens qui ne sont pas vers index.php (votre routeur) vers votre racine.
Pour faire ça, regroupez tous vos fichiers PHP (sauf le routeur) dans un sous-dossier (nommé par exemple "app").
À la racine de ce sous-dossier ajoutez un fichier `.htaccess` qui contient:

```apacheconf
deny from all
```

De cette manière votre architecture est cachée.
Une vraie configuration placera le dossier app dans le dossier `/var/www` ce qui reviens au même (apache permet juste l'accès à `/var/www/html`).

## Modifier les cookies génériques

Tous les cookies de session PHP ont le même nom, ce qui simplifie le travail d'un pirate.
Pour le changer, avant d'appeler `session_start`, appelez la fonction suivante:

```php
session_name("MonCookieDeSession");
```

Mettez un nom générique, différent du nom par défaut (PHPSESSID), pour ne pas révéler la technologie utilisée. Pour le TP2 gardez un nom significatif pour faciliter le développement.

## Désactiver les erreurs

Sur le serveur de production, c'est déjà géré.
Aucune information sur le code PHP est affichée en cas d'avertissement ou erreur PHP.

Si vous voulez quand même désactiver les erreurs, ajoutez ces lignes à votre routeur:

```php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
```

Vous pouvez aussi mettre des pages spéciales pour les erreurs HTTP, par exemple pour une page 404:

```apacheconf
ErrorDocument 404 /404.html
```

Vous pouvez même pointer vers une action de votre MVC (contrôleur Error ou Home).
Vous devez mettre une ligne par code d'erreur HTTP possible.
On voit surtout les codes 404 (page non trouvée) et 500 (PHP a planté).

## Données envoyées au client

Envoyez seulement les informations nécessaires au client:

* JAMAIS afficher l'ID de session
* Ne jamais "echo" un mot de passe (haché ou non, valide ou non)
* Afficher le moins possible les ID (mais souvent nécessaire dans les formulaires ou les liens)

Demandez-vous si afficher l'information présente un risque si elle est trouvée.
Chaque fois qu'une donnée passe du client au serveur (ou l'inverse) un risque d'interception est présent (surtout dans l'URL ou l'entête).
Donc si l'ID de session est affiché dans la page en plus de passer par le cookie, il y a 2 fois plus de risque (même si c'est minime).

## Brouillage du JavaScript

Sur un serveur de production, il est conseillé de compresser et minimiser les fichiers css et JavaScript.
Dans les deux cas, il y aura une diminution du téléchargement (temps et données) pour les clients.

Pour le JavaScript le code sera aussi dissimulé, ce qui ajoute un autre bâton dans les roues du pirate.
Par contre, le code devient impossible à déboguer, à utiliser seulement sur la production.
Attention à ne pas déployer la version non-compressée sur le serveur.

## Code mort dans l'interface frontale

Attention à ne pas afficher d'information sur votre architecture dans les commentaires HTML ou JavaScript.
Utilisez des commentaires PHP dans ces cas.

## Fichiers de configurations

Comme dit plus haut, votre code ne devrait pas être accessible directement pour les utilisateurs (il doit passer par le routeur).
Le danger est similaire sinon pire pour les fichiers de configurations, assurez-vous qu'ils ne sont pas accessibles par l'utilisateur.

Référence: https://www.hacksplaining.com/app/lessons/information-leakage/prevention