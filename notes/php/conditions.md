# Conditions
Les conditions en PHP sont standards, sauf pour le else if.

```php
if ($a > $b) {
  // bla
} elseif ($a < $b) {
  // blabla
} else {
  // bleh
}
```

Regardez bien le `elseif`, il n'y a pas d'espace entre le else et le if. Ce manque d'espace est obligatoire!

Les 'et' et les 'ou' sont standards (&& et ||). Les comparaisons aussi (>, >=, <, <=, ==, !=). Par contre, comme le typage est faible en PHP, il y a, comme en JavaScript, les opérateurs '===' et '!==' qui vont aussi valider le type.

Si vous utilisez les opérateurs de comparaisons standards (== et !=), les comparaisons suivantes sont vrai:

 * `0 == '0'`
 * `0 == false`
 * `42 == true`
 * `'' == false`

Le switch est standard aussi:

```php
switch ($a) {
  case 0:
  case 1:
    // bla
    break;
  case 2:
    // blabla
    break;
  default:
    // bleh
}
```

PHP 8 introduit un petit frère au switch, le match:

```php
$n = match($condition) {
  0 => 'bla',
  default => 'blabla'
}
$prix = match(true) {
  $age >= 65 => 100,
  $age <= 18 => 10,
  default => 25
}
```

Vous avez aussi accès à l'opérateur ternaire de base:

```php
$b = $a > 0 ? $a : 0;
```

Vous avez aussi l'opérateur de fusion Null:

```php
$b = $a ?? 0; // Vaut 0 si $a est null, sinon on prend $a
$name = $user->login->name ?? 'anonymous'; // Si $user, $user->login ou $user->login->name est null, on utilise anonymous
```

C'est possible aussi de faire du chaînage avec des fonctions nullables:

```php
$date = $reservation->getDate()?->toString(); // Donne null si getDate retourne null
```

Finalement, l'opérateur vaisseau spacial, qui permet de faire une comparaison <, > et = en même temps:

```php
1 <=> 2; // Donne -1 car la gauche est plus petite
2 <=> 1; // Donne 1 car la gauche est plus grande
2 <=> 2; // Donne 0 car c'est égal
```