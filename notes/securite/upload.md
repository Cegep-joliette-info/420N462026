# Téléversement

En anglais: _File Upload Vulnerabilities_

Quoi de plus dangereux que de permettre à un utilisateur de mettre le fichier qu'il veut sur un serveur?

Avec l'exemple précédent on peut voir qu'un formulaire de téléversement non-sécurisé peut être très dangereux.
La méthode la plus simple (mais la moins bonne) pour se protéger est de vérifier le type de fichier reçu:

Je ne suis pas assez bon hacker pour exploiter cette faille, mais selon Internet c'est très simple.
Les protections possibles (combinez les pour plus d'efficacité):

## Extension de fichier

Utilisez une expression régulière sur `$_FILES['img']['name']` afin de permettre seulement certaines extensions (avec une liste d'autorisation).
Attention à ne pas permettre les doubles extensions comme `.jpg.php` (faille possible si vous utilisez la fonction `str_contains`).

## Type mime

Le type mime est décrit dans le fichier, contrairement à l'extension qui est dans le nom.
Vous pouvez le trouver dans la variable `$_FILES` mais comme ça provient de l'utilisateur, il s'agit d'une donnée non fiable.
On va donc utiliser la fonction PHP `mime_content_type`, si on lui donne un fichier .jpg la fonction va nous retourner `image/jpeg`.

Je vous conseille d'utiliser l'extension de fichier et le type mime afin de valider une ressource téléversée, vous pouvez même vérifier que les deux informations concordent.

## Entête Content-Type

Lorsqu'un fichier est téléversé depuis un navigateur, une entête HTTP `Content-Type` accompagne la requête.
Vous pouvez valider que cette valeur fait partie d'une liste d'autorisation (ex: `image/jpeg`, `image/png`).
Attention: cette entête peut facilement être falsifiée par un script ou un proxy, donc cette vérification seule est insuffisante — combinez-la avec la validation du type MIME réel du fichier.

## Taille de fichier

Pour éviter de remplir le disque dur, validez la taille des fichiers téléversés.
Apache fait déjà cette vérification, vous pouvez voir la limite actuelle avec la commande PHP `echo ini_get('upload_max_filesize');`.

## Fichier compressé

Évitez les téléversements de fichiers compressés d'utilisateurs moins fiables.
Une attaque du type _zip bomb_ existe, il s'agit, par exemple, d'un fichier `42.zip` qui fait 42 Ko compressé, mais une fois décompressé pèse 4.5 Po.
Décompresser cette bombe demande beaucoup de ressources, ce qui ralentit ou même fait planter votre système.

## Permissions d'exécution

Si les fichiers téléversés sont écrits sur disque, assurez-vous que le serveur Web n'a pas la permission de les exécuter.
Sur un système Unix, le dossier de téléversement ne devrait avoir que les permissions de lecture et d'écriture, jamais d'exécution (`chmod` sans le flag `x`).
Ainsi, même si un pirate réussit à téléverser un fichier `.php`, le serveur refusera de l'exécuter.

## Nom du fichier

Renommez les fichiers, ne laissez pas le nom de fichier donné par l'utilisateur.
Personne n'aime avoir un caractère invisible qui empêche la suppression d'un fichier...

Souvent j'utilise l'ID de l'élément comme nom.
Donc l'image de profil de l'utilisateur id=42 va être 42.jpg.
Bien entendu, ça prend une colonne supplémentaire pour garder l'extension.
Vous pouvez aussi générer un nom aléatoire, mais assurez vous que le nom de fichier est unique!

## Serveur de téléversement

Une technique pour protéger votre site serait d'utiliser un autre système pour entreposer les fichiers téléversés (comme Amazon S3).
De cette manière un fichier dangereux ne pourrait pas détruire votre serveur PHP.

## Optimisation: image dans la bd

Vous pouvez mettre des images dans la BD en utilisant un BLOB.
Par exemple dans ma table _user_ je pourrais avoir les champs: _id_, _username_, _password_ et _image_.
Le problème? Une table qui ferait quelques octets de taille fait maintenant plusieurs Mo.
MariaDb (et la plupart des SGBD) charge tout l'enregistrement en mémoire avant de le retourner, même si vous avez juste besoin de l'ID.

Donc pour accélérer vos requêtes SQL, créez une table _images_ et ajoutez une clé étrangère dans votre table _user_ qui pointe vers la table _image_.
De cette manière, si vous avez besoin de faire un SELECT sans avoir besoin de l'image, votre requête sera beaucoup plus rapide.

## Protection contre les images infectées

Les bons pirates peuvent cacher du code malicieux directement dans le fichier de l'image.
Aux yeux du système l'image est valide et va bien s'afficher, mais en parallèle du code supplémentaire va s'exécuter...

La manière de se protéger ce type d'attaque est de réencoder l'image avec GD ou ImageMagick, nous allons voir comment faire dans le chapitre 5 du cours si nous avons le temps.

Référence: https://www.hacksplaining.com/app/lessons/file-upload/prevention