# Héritage et plus

## Héritage

Pour hériter d'une autre classe, il faut utiliser le mot-clé 'extends' :

```php
class User {}
class Student extends User {}
```

Une classe ne peut hériter que d'une autre classe.

Si vous voulez appeler la fonction de la classe parente, il faut utiliser 'parent::'. Si on déclare une fonction avec le même nom que le parent, ça overwrite par défaut, il faut donc parfois appeler la fonction parente. Exemple:

```php
class User {
  public string $username;
  public function __construct($username) {
    $this->username = $username;
  }
}
class Student extends User {
  protected string DA $da;
  public function __construct($username, $da) {
    parent::__construct($username);
    $this->da = $da;
  }
}
```

## Classe abstraite

Pour définir une classe abstraite, il faut utiliser le mot-clé 'abstract'. Dans cette classe on peut définir des méthodes communes pour toutes les classes qui vont en hériter et des méthodes à définir (en utilisant encore le mot-clé 'abstract'). Exemple de php.net :

```php
abstract class AbstractClass 
{
    // Force les classes filles à définir cette méthode
    abstract protected function getValue();
    abstract protected function prefixValue($prefix);

    // méthode commune
    public function printOut() {
        print $this->getValue() . "\n";
   }
}
```

Les classes qui vont hériter de AbstractClass doivent redéfinir les fonctions getValue et prefixValue avec exactement la même signature (sans le 'abstract' bien entendu). La visibilité peut être changé pour une visibilité moins restrictive (private > protected > public). Donc on peut mettre ces fonctions protected ou public.

## Classe finale

Le mot-clé final est l'inverse de 'abstract'. Une classe ou une fonction 'final' ne peut pas être surchargé ou étendu. Ce mot clé vient avant la visibilité de la fonction et avant le mot-clé class. Exemple:

```php
final class User {} // On ne peut pas extend cette classe
class User2 {
  final public verifyPassword($password): bool {} // On ne peut pas redéfinir cette fonction si on hérite de cette classe
}
```

## Interfaces

Une interface permet de forcer une classe à implémenter des fonctions. Une classe peut implémenter plusieurs interfaces. On nomme les interfaces comme des classes. Par contre, l'interface peut suivre plusieurs normes. 1. Précédé d'un 'i' (iCar, iUser, iDatabase), c'est la vielle manière de fonctionner. 2. Utiliser des verbes (Flying, Swimming, Rolling OU CanFly, CanSwim, CanRoll). 3. Utiliser des mots plus générique pour les interfaces que pour les classes (interface Vehicule et classe Car par exemple). Encore là, prenez une des méthodes mais restez constants! Exemple d'interface et de classe:

```php
interface Person {
  public function getFullName(): string;
}
class Player extends User implements Person { ... }
```

Comme vous pouvez voir dans l'exemple précédant, le extends vient avant le implements.

Vous pouvez aussi faire de l'héritage entre les interfaces avec le mot-clé extends:

```
interface a { public function foo(); }
interface b { public function bar(); }
interface c extends a, b { public function toto(); }
```

Si une classe implémente c, elle devra définir les fonctions foo, bar et toto.

## Traits

Un trait est très similaire à une classe abstraite, on ne peut pas l'instancier et peut être utilisé dans les classes. Un trait peut avoir des propriétés membres et des fonctions, comme une classe. La magie d'un trait est qu'une classe peut en utiliser plusieurs (c'est presque de l'héritage multiple). Un trait peut utiliser d'autres traits et peut définir des méthodes abstraites. Pour utiliser un trait, il faut utiliser le mot-clé 'use'. Exemple simple:

```php
trait Account {
  public string $username;
  public string $password;
  public function login() {...}
}
trait Student {
  public string $da;
}
class StudentAccount {
  use Account, Student;
}
```

Si la classe, sa classe parent et un trait définissent la même fonction, l'ordre de priorité est: la classe, le trait et le parent.

Si deux trais définissent la même fonction, vous devez utiliser 'insteadof' pour choisir quelle fonction utiliser:

```php
trait T1 {
  public function test() : int {
    return 1;
  }
}
trait T2 {
  public function test() : int {
    return 2;
  }
}
class TVrai {
  use T1, T2 {
    T2::test insteadof T1;
  }
}
```

En utilisant le mot-clé 'as', vous pouvez aussi redéfinir des éléments des traits, par exemple:

```php
class TFaux {
  use T1 {
    T1::test as private testT1;
  }
}
```

Dans l'exemple précédent, la fonction test qui provient de T1 est maintenant privé et s'appelle testT1. Vous pouvez modifier seulement la visibilité, seulement le nom ou les deux comme dans l'exemple précédent.

Attention, si vous voulez redéfinir une propriété membre dans une classe qui utilise un trait, la visibilité, le nom et la valeur initiale de l'attribut doivent être identiques dans le trait et la classe.