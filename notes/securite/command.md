# Injection de commande

En anglais: _Command Execution_.

L'injection de commande est une attaque très dangereuse, mais très facile à éviter.

Évitez d'utiliser les commandes PHP suivantes: `shell_exec`, `exec`, `passthru`, `system` et `popen`.
Ces quatre fonctions servent à exécuter d'autres programmes que PHP.
Par exemple on pourrait afficher le résultat d'un `ping` ou encore faire un explorateur de fichier en utilisant des `ls`.

```php
// Vulnérable — l'entrée utilisateur est directement dans la commande
$ip = $_GET['ip'];
echo shell_exec("ping -c 1 " . $ip);
// Un pirate envoie : 8.8.8.8; rm -rf /var/www
```

Si vous n'avez pas le choix d'utiliser ces commandes, priorisez les solutions suivantes:
1. N'utilisez pas de données non-fiables
2. Utilisez une liste d'acceptation définie en PHP de valeurs possibles

```php
// Liste d'acceptation : seules les valeurs connues sont acceptées
$serveursPermis = ['serveur1', 'serveur2', 'serveur3'];
$serveur = $_GET['serveur'] ?? '';

if (!in_array($serveur, $serveursPermis)) {
    exit('Serveur non autorisé.');
}

echo shell_exec("ping -c 1 " . $serveur);
```

3. DANGER: Si la liste d'acceptation n'est pas possible, utilisez `escapeshellarg()` pour assainir les arguments ou `escapeshellcmd()` pour assainir la commande entière. Une erreur pourrait avoir des résultats catastrophiques!

```php
// escapeshellarg() encadre la valeur entre guillemets et échappe les caractères spéciaux
$ip = escapeshellarg($_GET['ip']);
echo shell_exec("ping -c 1 " . $ip);
```

Habituellement le serveur web roule avec un utilisateur créé pour apache ou nginx, les dommages possibles sont limités si vous gardez ce fonctionnement.
Par contre cet utilisateur a accès aux fichiers journaux, qui peuvent contenir des informations sensibles.
Le pirate pourrait trouver les informations de connexion à la base de données, si la connexion est possible à distance, il a accès à tout!

Références:
  * https://www.hacksplaining.com/app/lessons/command-execution/prevention
  * https://portswigger.net/web-security/os-command-injection