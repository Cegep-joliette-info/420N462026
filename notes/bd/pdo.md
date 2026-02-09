# PDO - intro

Il existe 2 extensions PHP pour MySQL: mysqli et pdo.

Mysqli est la nouvelle extension pour utiliser MySQL/MariaDB. Historiquement on utilisait l'extension Mysql mais elle n'existe plus depuis PHP 7.0. Le 'i' à la fin veut dire 'improved'. Il y a une version procédurale et une version orienté objet.

PDO est un acronyme pour "PHP Data Objects". C'est une interface pour 12 différents SGBD (MySQL, MS SQL, Oracle et PostgreSQL). Les fonctions PHP sont identiques peu importe le SGBD utilisé, mais le SQL peut varier pour chacun!

Nous allons utiliser PDO dans ce cours. Il est un peu moins performant et manque quelques fonctionnalités avancés de MySQL, mais ces différences ne sont pas importantes pour le cours. Le fait d'apprendre une extension pour tous les différents SGBD vaut la différence.

Les deux extensions sont très similaires, voir la section "Code Differences" de [cet article](https://websitebeaver.com/php-pdo-vs-mysqli).

## Connexion et déconnexion

Pour ouvrir une connexion à la BD, il faut utiliser le constructeur PDO:

```php
$bd = new PDO('mysql:dbname=testdb;host=127.0.0.1', 'username', 'mot_de_passe');
```

Le premier paramètre est le DSN (Data Source Name). Voir tous les paramètres sur le site [php.net](https://www.php.net/manual/en/ref.pdo-mysql.connection.php). Si vous utilisez docker, utilisez le host 'host.docker.internal' sur Windows et '172.17.0.1' sur Linux.

Pour voir si la connexion est bien établis il faut faire un try/catch.

```php
try {
    $bd = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
```

Et lorsque nous n'avons plus besoin de la BD:

```php
$bd = null;
```

Si vous oubliez de fermer la connexion à la bd ce n'est pas grave, elle sera fermé automatiquement à la fin de la vie de l'objet (la fin du script habituellement). Mais c'est une bonne pratique de fermer la connexion lorsqu'elle n'est plus nécessaire.

## Informations de connexion à la BD

Idéalement, nous allons mettre les informations de connexion à la BD dans un fichier de configuration. Ce fichier de configuration peut contenir un tableau, des constantes ou un objet. Nous allons ignorer ce fichier dans git et mettre un fichier d'exemple de configuration (par exemple, on ignore config.php et on ajoute config.sample.php à git). De cette manière, chaque personne qui travail sur le projet peut mettre sa configuration sans créer de conflit à GIT.

On peut créer aussi un fichier d'amorçage (bootstrap en anglais, processus de démarrage de l'application sans aide extérieur, mot dérivé de "booting"). Ce fichier d'amorçage peut: démarrer le chargement automatique de classes, configurer le niveau d'erreur et ouvrir la base de données. Cette méthode est utilisé dans les cadres d'applications (frameworks) directement dans le fichier index.php ou même en plusieurs fichiers. On peut faire beaucoup plus que ça dans ce fichier.

## select

Si vous n'avez aucune donnée non-fiable (qui peut avoir été manipulé par un utilisateur), vous pouvez utiliser la fonction query qui retourne un PDOStatement:

```php
$query = $bd->query("SELECT * FROM users");
```

Si vous avez des données non-fiables, il faut préparer la requête avec prepare. Chaque paramètre provenant d'une source non-fiable est identifié avec un point d'interrogation ou nommé:

```php
$query = $bd->prepare("SELECT * FROM users WHERE id = ?");
$query = $bd->prepare("SELECT * FROM users WHERE id = :userid");
```

Ensuite on a 3 possibilité: bindParam, bindValue et le donner à l'exécution.

Pour bindParam, PDO va lier la variable au paramètre (un pointeur). Donc si la variable change avant l'exécution le paramètre va changer. Utile lorsqu'on doit faire plusieurs requête similaires dans une boucle. On change la variable et ça va impacter la prochaine exécution. Exemple du bindParam:

```php
$userid = 1;
$query->bindParam(1, $userid, PDO::PARAM_INT); // avec le ?, le premier ? est 1...
$query->bindParam(':userid', $userid, PDO::PARAM_INT); // avec le paramètre nommé
$query->execute(); // exécuter la requête préparée
```

Le 3e paramètre de bindParam est une constante, voici la liste non-exhaustive: `PDO::PARAM_BOOL`, `PDO::PARAM_NULL`, `PDO::PARAM_INT` et `PDO::PARAM_STR`. Pour les dates utilisez `PDO::PARAM_STR`.

bindValue est très similaire à bindParam. Mais au lieu d'utiliser un pointeur vers une variable, il va utiliser la valeur de la variable. Utile lorsque la variable peut changer. Je ne met pas d'exemple, c'est exactement la même structure que bindParam.

Finalement, on peut donner les valeurs dans le execute directement, dans un array:

```php
$query->execute([$userid]); // avec le ?
$query->execute([':userid' => $userid]); // avec le paramètre nommé
```

Si vous avez un paramètre de requête string, vous n'avez pas besoin de mettre les guillemets autour de la string. Pour faire un like (username LIKE '%name%'), votre variable doit contenir les '%', il ne faut pas les mettre dans la requête.

Finalement, après la fonction query ou execute, on peut récupérer les données. La première solution permet de récupérer un enregistrement à la fois. La fonction fetch retourne FAUX s'il n'y a rien, ou le prochain enregistrement. C'est donc utile lorsqu'il y a juste 1 enregistrement entendu. La fonction demande 1 paramètre, le style de l'enregistrement:

 * `PDO::FETCH_BOTH` (valeur par défaut): Met le résultat dans un tableau. Chaque colonne accessible par son nom ou son index. Par exemple, pour l'ID je peux faire $result[0] ou $result['id'];
 * `PDO::FETCH_CLASS`: Crée un objet basé sur la classe. Il faut executer la ligne suivante avant de pouvoir faire le fetch: `$query->setFetchMode(PDO::FETCH_CLASS, 'nomDeLaClasse');`
 * `PDO::FETCH_OBJECT`: Met le résulat dans un objet anonyme.

Un exemple:

```php
$result = $query->fetch(PDO::FETCH_OBJECT);
```

C'est possible d'utiliser fetchObject au lieu de mettre les paramètres d'objet ou de classe.

Deuxième solution est d'utiliser fetchAll, fonctionne de la même manière que fetch mais retourne un tableau de tous les résultats.

Toisième et dernière solution est de passer l'objet Statement dans une boucle foreach:

```php
foreach ($query as $result) {}
```

## Insert, update, delete, create, etc

Si vous n'avez pas de données non-fiables, vous pouvez utiliser exec:

```php
$noResults = $bd->query("INSERT INTO users (username, password) VALUES ('toto', 'toto')");
```
Cette fonction retourne le nombre de lignes modifiés.

Si vous faites un INSERT, vous pouvez accéder à l'ID inséré (par exemple, pour aller consulter l'enregistrement) avec lastInsertId:

```php
$id = $bd->lastInsertId();
```

Si vous avez des données non-fiables, il faut passer par le prepare comme avant:

```php
$query = $bd->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$query->execute(['toto', 'motdepasse']);
```

Pour avoir le nombre de lignes affectés, vous pouvez utiliser:

```php
$query->rowCount();
```