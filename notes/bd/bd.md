# Base de données

Dans le cours nous allons utiliser MySQL/MariaDB comme SGBD. Avec docker vous avez accès à PHPMyAdmin (localhost:8080) qui permet de consulter le contenu de votre base de données. MariaDB est une fourche (fork) de MySQL, cette fourche a été produite au moment où Oracle a acheté Sun, propriétaire de MySQL. MariaDB est sous licence GPL (open-source), la communauté implémente les nouvelles fonctionnalités de MySQL afin de les garder compatibles. La communauté a aussi fait plusieurs amélioration de performance sur MariaDB qui rend le système plus rapide que MySQL.

MySQL est la propriété d'Oracle, Oracle c'est comme l'empire dans Star Wars, utilisez MariaDB.

## Optimisation de chaînes de caractères

Utiliser `CHAR` seulement si la taille de la chaîne est toujours la même. Par exemple, pour un code postal qui est toujours de 6 caractères (sans l'espace). Sinon, utilisez `VARCHAR`. Si la taille d'un `CHAR` est plus petite que la taille définie, MySQL va ajouter des espaces pour compléter la taille, ce qui gaspille de l'espace et peut ralentir certaines opérations.

Un `VARCHAR` a une limite de 65535 octets (taille de la chaîne sur 2 octets). Si votre colonne doit être dans un index la limite varie de 191 à 3072 octets selon le type de caractères (utf8mb4 ou utf8) et le moteur de stockage (InnoDB ou MyISAM).

Même s'il n'y a pas d'index, la taille doit être limitée. Pour certaines requêtes complexes, MySQL doit créer des tables temporaires sur le disque. Si votre colonne est trop grande, MySQL ne peut pas utiliser la mémoire pour la table temporaire, ce qui ralentit les performances.

Longueurs à faire attention:

 * `VARCHAR(255)`: Max pour une taille encodée sur 1 octet (à garder en tête pour une petite optimisation).
 * `VARCHAR(320)`: Taille pour une adresse e-mail, certains sites parles de 254 caractères, mais pour être sûr on peut mettre 320.
 * `VARCHAR(100)`: Taille pour un nom, 50 devrait être assez, mais on se garde une marge.
 * `VARCHAR(255)`: Taille pour un mot de passe haché (bcrypt par exemple), 60 est suffisant, mais PHP recommande 255.

## Ensemble de caractères (character set)

Lorsqu'on crée une BD il faut choisir un ensemble de caractères (character set) et un interclassement (collation). L'ensemble de caractères définit les caractères qui peuvent être utilisés dans la BD. L'interclassement définit comment les caractères sont comparés et triés. Par exemple, si on utilise utf8mb4 comme ensemble de caractères, on peut utiliser tous les caractères Unicode, y compris les emojis. Si on utilise utf8mb4_general_ci comme interclassement, les comparaisons de chaînes seront insensibles à la casse et aux accents.

Nous allons utiliser utf8mb4_uca1400_ai_ci dans l'interface de phpMyAdmin, ce que chaque section signifie:

 * utf8mb4: ensemble de caractères qui supporte tous les caractères Unicode, y compris les emojis.
 * uca1400: interclassement qui utilise l'algorithme de comparaison Unicode Collation Algorithm version 14.0.0.
 * ai: accent insensitive, les comparaisons de chaînes seront insensibles aux accents.
 * ci: case insensitive, les comparaisons de chaînes seront insensibles à la casse.

Ce qui veut dire que si on compare "école" et "ECOLE", ils seront considérés comme égaux. Vous pouvez changer ai et ci pour as (accent sensitive) et cs (case sensitive) si vous voulez que les comparaisons soient sensibles aux accents et à la casse.