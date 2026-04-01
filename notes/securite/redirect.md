# Redirection ouverte

Essayez d'accéder au calendrier du cours qui est sur Moodle sans être connecté.
Moodle va dire que vous n'êtes pas connecté, une fois connecté il vous envoie vers la page désirée initialement.

Sur plusieurs sites on peut voir en GET un paramètre "returnURL" qui permet de faire une redirection après une connexion.

Le problème? Si vous faites le code suivant:

```php
header('location: ' . $_GET['returnurl']);
```

Le pirate pourrait envoyer un lien qui redirigerait vers un site frauduleux imitant votre page de connexion pour voler les identifiants de la victime (phishing), ou vers un site à caractère problématique.
Si vous implémentez cette fonctionnalité, gardez le `returnurl` dans la session ou assurez-vous qu'il s'agit bien d'une URL relative et non absolue.

**Méthode 1 — conserver le `returnurl` dans la session :**

```php
// À la page de connexion, on sauvegarde l'URL dans la session
$_SESSION['returnurl'] = $_GET['returnurl'] ?? '/';

// Après la connexion réussie, on redirige et on nettoie la session
$returnurl = $_SESSION['returnurl'] ?? '/';
unset($_SESSION['returnurl']);
header('Location: ' . $returnurl);
```

**Méthode 2 — valider que l'URL est relative :**

```php
$returnurl = $_GET['returnurl'] ?? '/';

// parse_url retourne un tableau vide ou sans 'host' si l'URL est relative
if (!empty(parse_url($returnurl, PHP_URL_HOST))) {
    $returnurl = '/'; // URL absolue détectée, on redirige vers l'accueil
}

header('Location: ' . $returnurl);
```

Référence: https://www.hacksplaining.com/app/lessons/open-redirects/prevention