# Select dans un objet

On peut faire un select dans des objets basé sur classes. Par exemple, j'ai la table suivante:

```php
CREATE TABLE `user` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(512) NOT NULL,
 `password` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4
```

Avec la classe suivante:

```php
namespace Model;

class User {
    public int $id;
    public string $username;
    public string $password;
}
```

Remarquez bien que les propriétés de mon objets ont le même nom que les colonnes de ma table.

Maintenant, lorsque je fais le select je peux lui dire de mettre automatiquement les données dans l'objet:

```php
$result = $bd->query('SELECT * FROM user', PDO::FETCH_CLASS, 'Model\\User');
```

Je peux faire un fetchAll() par la suite pour avoir le résultat dans un tableau d'objets ou faire un ForEach sur le $result.

Avec le prepare, la structure est un peu plus complexe:

```php
$request = $bd->prepare('SELECT * FROM user');
// Mettre ici les binds de paramètres
$request->execute();
$users = $request->fetchAll(PDO::FETCH_CLASS, 'Model\\User');
```

Si je veux faire un foreach au lieu d'avoir un tableau, la structure est un peu différente:

```php
$request = $bd->prepare('SELECT * FROM user');
$request->setFetchMode(PDO::FETCH_CLASS, 'Model\\User');
$request->execute();
foreach ($request as $user) {
    var_dump($user);
}
```

La méthode foreach consomme moins de mémoire vive sur le serveur (garder des données "raw" SQL VS des objets complets en PHP), mais la différence n'est pas énorme.

**Attention!**
Avec le FETCH_CLASS, PHP va modifier les propriétés membres avant d'appeler le constructeur. Il va aussi passer aucun paramètre au constructeur.