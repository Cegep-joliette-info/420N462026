# Certificat SSL

Tout site en production devrait utiliser un certificat SSL reconnu.
Un certificat auto-signé affichera un avertissement à l'utilisateur ce qui causera une méfiance ou un désagrément.
Il est conseillé de payer un certificat reconnu si votre site effectue des transactions bancaires, sinon utilisez un outil comme "Let's Encrypt" qui génère gratuitement des certificats SSL valides.

Un navigateur utilise le port 80 pour les requêtes HTTP et utilise le port 443 pour les requêtes HTTPS.

Sans certificat SSL votre site sera vulnérable aux attaques de type man-in-the-middle.
TLS chiffre toute la connexion: les en-têtes HTTP, le corps de la requête, et la réponse du serveur.
Autant le HTML envoyé à l'utilisateur que les données envoyées en POST sont donc protégés.

Il est important d'utiliser un certificat SSL sur tout site en production (que ce soit un script simple, du MVC ou une API).
Vous pouvez forcer une redirection de deux méthodes:

1\. Avec un htaccess:
```apacheconf
RewriteEngine On
RewriteCond %{SERVER_PORT} 80
RewriteRule ^(.*)$ https://420n46.jolinfo.cegep-lanaudiere.qc.ca/$1 [R,L]
```

2\. En PHP:
```php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $location);
    exit;
}
```
Source: [https://stackoverflow.com/a/5106355](https://stackoverflow.com/a/5106355)

L'avantage de le faire en PHP est qu'on peut ajouter une condition avec le `$_SERVER['HTTP_HOST]`.
Si le host est 'localhost', on force http, sinon on force le https.

3\. Avec l'en-tête HSTS (_HTTP Strict Transport Security_):

Dans le `.htaccess` (nécessite que le module `mod_headers` soit activé):
```apacheconf
Header always set Strict-Transport-Security "max-age=31536000"
```

HSTS indique au navigateur de toujours utiliser HTTPS pour ce domaine, même si l'utilisateur tape `http://`.
Ça protège contre les attaques _SSL stripping_ où un pirate intercepte la connexion avant la redirection et dégrade le HTTPS en HTTP.

Référence: https://www.hacksplaining.com/app/lessons/unencrypted-communication/prevention