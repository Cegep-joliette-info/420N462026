# Atelier 4 - Base de données

Dans votre base de données (via un script PHP ou PhpMyAdmin), créez les tables suivantes:

 * categories: id, nom
 * jeux: id, nom, categorie_id (fk)

Ajoutez manuellement (via un script PHP ou PhpMyAdmin) quelques catégories dans votre base de données.

En PHP, créez les quatres actions CRUD (Create, Read, Update, Delete) pour les jeux dans 3 ou 4 fichiers différents, particularités:

 * Create: On doit avoir un 'select' pour choisir une catégorie existante
 * Read: On doit voir le nom de la catégorie dans la liste, pas l'ID
 * Update: Comme Create, la bonne catégorie doit être sélectionné à l'ouverture
 * Delete: Cette action peut se trouver dans le même fichier qu'une autre action

Copies d'écrans (vous n'êtes pas obligés de faire la même chose), action Read:

![Copie d'écran Read](../imgs/atelier03/read.png)

Actions Edit et create sont très similaires:

![Copie d'écran Create et Update](../imgs/atelier03/create.png)

L'action Delete n'a pas d'écran dans mon exemple.