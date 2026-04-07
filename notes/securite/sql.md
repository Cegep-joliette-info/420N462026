# Injection SQL

En anglais: _SQL Injection_.

L'injection SQL est une attaque dangereuse, mais c'est facile de s'y protéger.

Si vous voulez exécuter les tests, modifiez le fichier /config/db.php si vous avez modifié le docker-compose.
Exécutez ensuite le script /SQL/init.php qui va créer la table et un enregistrement.

Un exemple d'injection SQL qui permet d'ignorer la majorité des WHERE:
 - Pour une chaîne: `' OR 1=1 --`
 - Pour un nombre: `0 OR 1=1 --`

Le but des 2 injections précédentes est de fermer la condition actuelle et rendre le WHERE vrai avec une condition simple, puis commenter le reste de la requête. Imaginez cette injection dans une des requêtes suivantes:

```php
"DELETE FROM posts WHERE id = " . $_POST['id']
"DELETE FROM posts WHERE id = " . $_POST['id'] . " AND user_id = " . $_SESSION['user_id']
```

Pour se protéger, historiquement on utilisait la fonction PHP `mysql_real_escape_string` sur toutes les chaînes de caractères à mettre dans une requête.
Cette fonction ajoutait des \ devant les caractères spéciaux, comme les ' et les ".
Pour les entiers on utilisait intval.

Maintenant c'est plus simple, il faut simplement utiliser les requêtes préparées de PDO ou mysqli.
Voir les notes de cours sur l'utilisation de PDO en PHP.

```php
// Vulnérable - l'entrée utilisateur est directement dans la requête
$pdo->query("SELECT * FROM users WHERE email = '" . $_POST['email'] . "'");

// Sécuritaire - requête préparée avec paramètre lié
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $_POST['email']]);
$user = $stmt->fetch();
```

## Principe du moindre privilège

Si vous configurez une base de données sur un serveur, créez un utilisateur qui aura accès seulement à votre base de données.
Donnez aussi le moins de droits possible, avec MariaDB les droits sont très modulaires, vous pouvez donc donner accès aux requêtes SELECT, INSERT, DELETE et UPDATE seulement.
De cette manière, un utilisateur qui réussit à passer outre votre protection contre l'injection SQL ne pourra pas détruire toute votre base de données.

Références:
  * https://www.hacksplaining.com/app/lessons/sql-injection/prevention
  * https://portswigger.net/web-security/sql-injection