# Inclure d'autres fichiers

## Manuellement

Il existe 2 'familles' d'instructions (pas des fonctions, même si c'est pratiquement pareil) pour inclure d'autres fichiers: include et require. Ce sont des fonctions sans parenthèse, on leur donne le chemin relatif vers le fichier à inclure avec son nom complet. Exemple:

```php
include '../class/user.php';
```

La différence entre include et require: Si le fichier donné ne peut pas être chargé (il n'existe pas ou autre), include emet une erreur E_WARNING, tandis que require va emettre une erreur E_COMPILE_ERROR. Donc require va planter et include va afficher un avertissement (ou ne rien afficher, selon la configuration de PHP).

C'est possible d'écrire les fonctions include et require avec des parenthèses (c'est ce que je fais habituellement). Mais la communauté PHP préfère la syntaxe sans parenthèses. L'important est de rester constant.

Parfois, sur Windows vous pouvez avoir des erreurs de chemin d'accès, c'est parce que Windows dans toute sa splendeur, utilise des \ au lieu de / pour ses chemins d'accès. Pour palier au problème, la constante DIRECTORY_SEPARATOR existe dans PHP qui va donner / ou \ selon le système d'exploitation. Mais PHP est supposé convertir les '/' en '\' automatiquement lorsque nécessaire... À tester!

include et require peuvent charger des fichiers plusieurs fois. Si le fichier A include les fichiers B et C, et que le fichier B include le fichier C, le fichier C sera chargé 2 fois en mémoire. Pour éviter ça vous pouvez ajouter '_once' à l'instruction (include_once 'c.php'; et require_once 'c.php'). De cette manière chaque fichier ne sera chargé qu'une seule fois même si plusieurs en on besoin, utile pour charger des classes!

Si le chemin d'accès donnée est relatif (débute par ./ ou ../), PHP va toujours utiliser le chemin d'accès du script principal. Donc si j'ai:

```php
a.php -> echo('patate');
d1/b.php -> include '../a.php';
d1/d2/c.php -> include '../b.php';
```

Si on ouvre c.php dans le navigateur, le include de a.php ne fonctionnera pas, car le include reste relatif au fichier original, soit c.php.
Si le chemin d'accès débute par le nom d'un dossier ou de fichier (sans '.' ou '/' au début), PHP va utiliser le chemin d'accès du script principal et le 'include_path'. Le 'include_path' par défaut est la racine de votre serveur PHP et le dossier actuel (sur mon mac c'est: ".:/user/local/Cellar/php/7.4.11/share/php/pear"). On peut ajouter le chemin absolu vers notre logiciel avec l'instruction suivante:

```php
set_include_path(get_include_path() . PATH_SEPARATOR . '/absolute/path');
```

On ajoute donc le dossier '/absolute/path' au 'include_path' de base. Le PATH_SEPARATOR est ':' sur unix et ';' sur Windows. Donc si je veux charger le fichier '/absolute/path/c.php' je peux juste faire:

```php
include 'c.php';
```

Une autre solution pour couvrir le problème de chemin d'accès est d'utiliser la constante magique `__DIR__` (attention, il y a deux '_' au début et à la fin). `__DIR__` représente le chemin d'accès du dossier du fichier courant. Par exemple sur mon projet de test ça donne '/Users/phil/Projets/test'. Si je reprend mon exemple précédent je vais modifier le fichier d1/b.php pour qu'il contienne:

include `__DIR__` . '/../a.php';
Dans les vieux scripts, au lieu de `__DIR__` vous allez voir: dirname(`__FILE__`), qui reviens au même.

## Automatiquement

La fonction magique est:

```php
spl_autoload_register();
```

On peut modifier les comportements de cette fonction en lui donnant des fonctions (callbacks) en paramètre. Mais c'est préférable d'utiliser les valeurs par défauts car la fonction de base est écrite en C, qui sera plus rapide que n'importe quelle fonction écrite en PHP.

Une fois cette fonction appelé, si vous utilisé une classe non chargé, PHP va aller 'include' le fichier de la classe.

Par défaut il va chercher dans le include_path (dossier courant et racine du serveur) pour trouver votre fichier qui termine par '.php'. Donc si j'ai la classe 'User', elle doit se trouver dans le dossier 'User.php'. Si ma classe a un espace de nom (namespace), chaque espace de nom est un dossier. Donc si la classe User a le namespace 'Main/Model', lorsque je vais faire 'new Main/Model/User()', PHP va aller le fichier 'Main/Model/User.php'.

Une vielle pratique est de mettre comme extension '.class,php' aux fichier de classes, si vous faites ça utilisez la fonction suivante avant le register:

```php
spl_autoload_extensions('.class.php');
```

Et finalement, si vos classes sont dans un dossier 'class', changez le include_path:

```php
set_include_path(get_include_path() . PATH_SEPARATOR . 'class/');
```

Ou:

```php
spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class/' . $className . '.php';
});
```

Ou, c'est possible de le faire avec composer. Dans votre composer.json ajoutez la configuration suivante:

```php
{
    "autoload": {
        "psr-4": {
            "": "class"
        }
    }
}
```

Ensuite, il suffit d'ajouter le fichier d'autoload de composer:

```php
require __DIR__ . '/vendor/autoload.php';
```

Finalement, pour mettre à jour le fichier d'autoload vous pouvez exécuter les commandes "composer update" ou "composer autoload-dump".