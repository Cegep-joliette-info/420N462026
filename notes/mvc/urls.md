# Beaux URLs

Avec un Dispatch, nos URLs vont ressembler à "index.php?controller=home&action=index", ce qui n'est pas très beau. On va transformer cet URL pour donner "/home/index". L'URL "index.php?controller=user?action=edit&id=1" va devenir "/user/edit/1".

## Solution Nginx

La solution Docker prépare déjà cette fonctionnalité, allez voir le fichier [nginx.conf](../../docker/fichiers/docker/nginx.conf) du git. Cette ligne fait la magie:

```nginx
location / {
    try_files $uri /index.php$is_args$args;
}
```

Si le fichier n'existe pas, nginx va envoyer la requête à votre index.php qui se trouve à la racine de votre projet. Vous devrez donc adapter votre docker-compose pour que la racine du site se trouve directement sur localhost. Dans les *containers* Web et php, modifiez la ligne suivante:

```
./:/var/www/html
```

Si votre projet se trouve dans le sous-dossier TP1, changez simplement pour:

```
./TP1/:/var/www/html
```

## Solution Apache

N'oubliez pas que le CPanel utilise Apache.

Pour commencer il faut activer le module apache "Rewrite". Ensuite nous allons créer un fichier ".htaccess" qui contient les données suivantes:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

Ces règles activent la redirection (ligne 1), si l'URL n'est pas un fichier existant (ligne 2) ni un dossier existant (ligne 3), redirigez vers le fichier index.php (ligne 4). Ça permet donc d'accéder aux fichiers CSS, JS et images en plus de faire fonctionner nos beaux URLS pour les contrôleurs. 

## Dans le PHP

Maintenant quoi faire dans le index.php? En haut du fichier nous allons accéder à l'URL actuel avec la commande:

```php
$_SERVER['REQUEST_URI']
```

Cette commande va me donner la string "/home/index" lorsque j'accède à "http://localhost/home/index". Pour séparer cette string pour accéder à la partie contrôleur et la partie action, on va utiliser quelques commandes:

```php
$uri = $_SERVER['REQUEST_URI'];
$uri = substr($uri, 1);
$parts = explode('/', $uri);
```

La première ligne va chercher "/home/index". La fonction substr permet de récupérer une partie de la string, la 2e ligne enlève le premier caractère de la string ce qui me donne "home/index". Finalement la fonction explode est l'équivalent de Split en Java, on sépare la string en plusieurs morceaux ce qui crée un tableau. La 3e nous donne donc ["home", "index"].

La première cellule de $parts nous donne le contrôleur à utiliser. La deuxième cellule nous donne l'action à appeler. On pourrait avoir une 3e cellule pour l'ID demandée. Tous les autres paramètres vont rester en GET et POST comme avant.

N'oubliez pas de mettre des valeurs par défaut à votre contrôleur et action, les URLs suivants doivent tous fonctionner (et pourraient faire la même chose):

 * localhost -> Default contrôleur home et default action index
 * localhost/home -> Contrôleur home et default action index
 * localhost/home/index -> Tout explicite