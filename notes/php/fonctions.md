# Fonctions

De base, une fonction se déclare comme ceci:

```php
function test() {}
```

Une fonction PHP peut retourner n'importe quoi (int, float, string, bool, rien, objet etc.). Les paramètres peuvent être de n'importe quel type:

```php
function test($a) {
  return $a + 1;
}
```

Mais, PHP 7.1 arriva sur Internet comme un messie avec sa bonne nouvelle qui va rendre PHP un peu moins laid. On peut maintenant mettre des types aux paramètres et à la valeur de retour. Si ce n'était pas de cette fonctionnalité, je vous demanderais de commenter chaque fonction...

Voici un exemple:

```php
function add(int $a, int $b): int {
  return $a + $b;
}
```

Toutes les fonctions peuvent être typés de cette manière. Si votre fonction ne retourne rien il faut mettre 'void' comme valeur de retour.

Notez que PHP passe les paramètres par valeur, vous pouvez utiliser le `&` pour signifier que vous passer le paramètre par référence:

```php
function inc(int &$a): void {
  $a++;
}
```

Tous les paramètres peuvent être nullables si vous ajoutez un '?' devant le type. Vous pouvez aussi mettre une valeur par défaut à un paramètre. Exemple:

```php
function test(?int $a = null) {}
function test(int|null $a = null) {}
```

Les deux lignes précédentes font la même chose. Dans cet exemple $a peut être un int ou null, si vous ne spécifiez pas $a à l'appel de la fonction le paramètre vaut null. Notez que si je donne un float à la fonction il va transformer le float en int lui-même de manière douteuse. Si vous avez activé le 'strict type' ça va planter si vous donnez autre chose qu'un int ou null. Notez que les paramètres optionnels (avec une valeur par défaut) doivent être à la fin de la liste de paramètres.

Vous pouvez aussi avoir un nombre de paramètres variables. Exemple:

```php
function add(int ...$numbers): int {}
```

Dans la fonction $numbers est un tableau de nombres entiers. Lorsque je l'appel, je peux utiliser un tableau ou des paramètres:

```php
add(1, 2, 3); // Appel standard

$tab = [1, 2, 3];
add(...$tab); // Appel avec un tableau
```

## Portée des variables

Dans l'exemple suivant:

```php
$a = 1;
function test() {
  echo $a;
}
```

Le script n'affiche rien, car la fonction utilise la variable $a locale. Pour que ma fonction utilise le $a global, il faut le spécifier à la fonction:

```php
$a = 1;
function test() {
  global $a;
  echo $a;
}
```

En utilisant une architecture orienté objet, on se sauve de ce problème.