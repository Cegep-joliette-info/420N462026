# Boucles

Des boucles, vous en avez fait en plusieurs langage, rien de sorcier ici.

## for

Utile lorsque vous savez le nombre d'itérations à faire:

```php
for ($i = 0; i < 10; i++) {}
```

## while

Utile lorsque vous ne savez pas combien d'itérations faire:

```php
while ($i < 10) {}
```

Si vous voulez que le contenu de la boucle s'exécute au moins une fois, le do-while est intéressant:

```php
do {
  $i--;
} while ($i < 10);
```

## foreach

Utile lorsque vous voulez parcourir une collection:

```php
foreach ($tableau as $value) {}
```

Si vous avez besoin de l'indice dans le tableau, il faut ajouter la flèche:

```php
foreach ($tableau as $key => $value) {}
```

Si vous avez besoin de modifier les valeurs, vous pouvez accéder aux valeurs par références:

```php
foreach ($tableau as &$value) {
  $value--;
}
```