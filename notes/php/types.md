# Types

Cette page s'inspire de [php.net](https://www.php.net/manual/en/language.types.php).

Une variable non déclaré est NULL (vérifiable avec is_null).

## Types scalaires

### Booléens

Standard: `true` ou `false`

```php
$foo = true;
```

### integer

Aussi standard, sauf pour la dernière notation qui fait la même chose que la première notation, mais permet plus de lisibilité.

```php
$a = 1234; // un nombre décimal
$a = 0123; // un nombre octal (équivalent à 83 en décimal)
$a = 0x1A; // un nombre héxadecimal (équivalent à 26 en décimal)
$a = 0b11111111; // un nombre binaire (équivalent à 255 en decimal)
$a = 1_234_567; // un nombre décimal (à partir de PHP 7.4.0)
```

La valeur maximale est environ 2 milliards sur les systèmes 32 bits et 9E18 sur les systèmes 64 bits. Vous pouvez avoir le maximum avec la constante globale PHP_INT_MAX. Si votre nombre dépasse ce maximum, il deviendra un float.

### float

Standard, les décimales avec le point et vous pouvez utiliser le '_' pour séparer les milliers. Le maximum est d'environ 1.8E308.

Attention, deux floats peuvent être différents même s'ils sont en théorie identiques (`8-6.4` et `1.6` vont être différents). Pour contrer ce problème il faut donc calculer la différence entre les deux nombres pour savoir s'ils sont égaux:

```php
$epsilon = 0.00001;
if(abs($a-$b) < $epsilon) {}
```

Un float peut aussi avoir la valeur `NAN`, on peut vérifier cette valeur avec `is_nan()`.

### string

Comme en JavaScript, PHP peut utiliser les strings avec les guillemets simples ou doubles. Par contre, il y a une différence en PHP. Une chaîne de caractères avec les guillemets doubles sera interprété par PHP contrairement aux guillemets simples. Exemple:

```php
$name = 'Toto';
$foo = 'Mon nom est $name'; // Donne Mon nom est $name
$fo2 = "Mon nom est $name"; // Donne Mon nom est Toto
```

Il faut donc prioriser les guillemets simples, surtout si on utilise des données non-fiables (qui proviennent d'un utilisateur). La barre oblique inverse est utilisé pour faire des guillemets du même type que celui utilisé pour la string. Pour les guillemets doubles il faut aussi utiliser la barre oblique inverse pour afficher le signe $.

Pour concaténer des chaînes de caractères, contrairement à tous les autres langages qui utilisent le '+', PHP utilise le '.' :

```php
$s = 'Bonjour ' . $name;
```

Comme on ne type pas les variables PHP, le langage ne peut pas se tromper entre une concaténation et une addition, problème possible en JavaScript.

## Types composés

### array

Pour déclarer un tableau, il faut utiliser array() ou []. Exemple:

```php
$tab1 = array(1, 2, 3);
$tab2 = [1, 2, 3];
```

Pour modifier un index, c'est standard:

```php
$tab2[0] = 0; // Donne [0, 2, 3]
$tab2[3] = 4; // Hors du tableau, mais donne [0, 2, 3, 4]
```

Vous pouvez même assigner la case à l'index 10 si vous voulez, sans problème.

Comme si ajouter des éléments dans un tableau juste en assignant n'était pas assez weird, vous pouvez aussi utiliser des indices string:

```php
$tab2['guide'] = 42; // Donne: [0 => 0, 1 => 2, 2 => 3, 3 => 4, 'guide' => 42]
```

Vous pouvez aussi déclarer votre tableau avec des indices randoms (integer et string seulement pour les indices):

```php
$tab3 = [
  89 => 'blarg',
  'titi' => [],
  '526' => 48,
  74 => new User()
];
```

Même si c'est possible, utiliser des types différents comme indice de tableau est une mauvaise pratique.

Plusieurs fonctions sont utiles pour les tableaux, voir la documentation du site [php.net](https://www.php.net/manual/fr/ref.array.php).

### object

On verra les classes dans un prochain chapitre, mais on ne peut pas passer à côté de la beauté des objets en PHP. Par exemple, le code suivant qui permet d'aller chercher la date de demain:

```php
$demain = new Date();
$demain->add(new DateInterval('P1D'));
```

C'est beau, n'est-ce pas? Au lieu d'un `.` on utilise `->`. Comme le `.` concatène, les inventeurs de PHP ont du trouver un autre symbole, ils ont choisi `->` qui existe en Pascal et en C.

Il existe d'autres types, mais je ne vois pas l'utilité d'en parler immédiatement...

## Typage strict

Vous pouvez activer le typage strict dans vos fichiers, par contre il faut le faire au début sinon ça peut tout briser. Il faut mettre cette ligne en haut du fichier, après le '<?php':

```php
declare(strict_types=1);
```

Sans cette ligne, 1 et '1' sont identiques. Si je donne un float à un paramètre int PHP va faire le transfère lui même. Avec le strict il va planter, mais ça évite des bugs...

Il faudra mettre cette ligne pour tous les fichiers de vos ateliers et TPs.

## Vérification

Pour vérifier le type d'une variable, vous avez plusieurs fonctions de disponible:

 * isset() : retourne vrai si la variable est déclaré et différente de null
 * empty() : retourne vrai si le contenu est considéré faux ("", 0, null, false, array(), etc.)
 * is_float()
 * is_bool()
 * is_int()
 * is_string()
 * is_array()
 * is_object()
 * is_null()

Pour détruire une variable, utilisez la fonction unset().

# Transtypage

 * \* → int: intval() // 0 en cas d'échec, permet aussi de définir la base pour convertir en binaire par exemple
 * \* → float: floatval() // Probablement 0 en cas d'échec (wait, what?), 1 sur des objets
 * \* → bool: boolval() // "", 0, [], null vaut faux
 * \* → string: strval() // int, float et bool c'est normal. Sur un array ça donne "Array". Sur un objet ça appel la fonction __ToString() ou erreur.
 * array → string: print_r($tab, true) // le 2e paramètre permet de dire à print_r de retourner la valeur au lieu de l'afficher