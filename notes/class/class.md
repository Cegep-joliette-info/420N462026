# Classes

PHP inclus l'orienté objet depuis la version 5. Le langage est encore en migration, il y a encore du procédural mélangé avec l'orienté objet dans les fonctions de bases du langage. Par contre, comme développeur, vous pouvez faire du vrai orienté objet.

Contrairement à JavaScript, PHP fait du vrai orienté objet, le mot-clé private existe!

Rappel des normes de programmations:

Le nom des classes doivent être en notation Pascal (StudlyCaps). Une classe doit être la seule chose présente dans son fichier. Le nom du fichier doit être le nom de la classe.

Les espaces de noms (namespaces) doivent suivre la norme de chargement automatique PSR-4. Donc chaque espace de nom est un dossier et chaque barre oblique est un sous-dossier. Le nom de chaque espace de nom doit être en notation Pascal.
Donc si votre projet deviens plus gros, vos classes devraient être séparés en sous-dossiers avec les espaces de noms.

## Classe

Une classe est déclaré par le mot-clé 'class':

```php
<?php
class ExempleDeClasse {}
```

## Propriétés

Pour déclarer une propriété membre, il faut: sa visibilité, son type (optionnel), son nom (précédé d'un $) et de sa valeur par défaut (optionnelle). Donc par exemple:

```php
public $id; // Minimum pour déclarer une propriété
public ?int $id = null; // Déclaration complète
```

La visibilité peut être: public, private ou protected.

Le type peut peut être: bool, int, float, string, array, object, iterable, self, parent ou NomDeLaClasse. En ajoutant un '?' devant le type comme dans l'exemple précédant, la propriété devient nullable.

Pour accéder à une propriété, il faut utiliser la flèche '->'. Attention! Après la flèche il ne faut pas mettre le signe '$' pour faire référence à la propriété membre.

$this fait référence à l'objet actuel.

## Fonctions

Même chose qu'une fonction hors-classe. Seule différence: le mot-clé 'function' doit être précédé de sa visibilité (public, private ou protected).

## Constructeur et destructeur

Les constructeurs et destructeurs sont considérés comme des méthodes magiques (voir site [PHP.net](https://www.php.net/manual/fr/language.oop5.magic.php)).

Vous ne pouvez avoir qu'un seul constructeur par classe. Si vous voulez avoir plusieurs constructeurs, vous devez mettre des paramètres optionnels (nullables). Le constructeur pour toutes les classes a le nom '__construct' (il y a 2 '_' devant le nom). Exemple du site php.net:

```php
class User
{
    public int $id;
    public ?string $name;

    public function __construct(int $id, ?string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
```

Je peux utiliser cette classe de cette manière:

```php
$user1 = new User(1); // $name vaut null
$user2 = new User(2, 'toto'); // $name vaut toto
echo $user2->name; // Affiche toto
```

Vous avez aussi accès au destructeur avec la fonction '__destruct()' (n'oubliez pas qu'il y a 2 '_').

## Constantes

Depuis PHP 7.1, c'est possible de mettre une visibilité aux constantes. Si on ne met rien, elle est publique par défaut. Voici comment déclasser une constante:

```php
class User {
  public const PASSWORD = 'Bonjour1!'; // Ne pas utiliser cet exemple dans un vrai cas
}
```

Une constante est considéré comme statique. On ne peut pas y accéder avec '$this->' car elle ne fait pas partie de l'objet actuel. Il faut utiliser 'self::' dans l'objet ou 'User::' à l'extérieur. Voici quelques exemples un peu loufoques:

```php
// Dans la classe:
self::TOTO; // Version à privilégier
User::TOTO;
$this::TOTO;
// À l'extérieur de la classe
User::TOTO; // Version à privilégier
$user = new User();
$user::TOTO;
$classname = 'User';
$classname::TOTO;
```

## Statique

Pour mettre une fonction ou une propriété statique, il suffit d'ajouter le mot-clé 'static' après la visibilité:

```php
public static int $count = 0;
public static function add($n1, $n2) {}
```

Il faut utiliser les '::' pour accéder à tout ce qui est statique (voir section constante pour des exemples). Petite particularité, il faut le '$' pour les propriétés lorsqu'elles sont statiques!

```php
User::$count;
```