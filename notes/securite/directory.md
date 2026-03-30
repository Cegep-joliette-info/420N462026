# Traversée de dossiers

En anglais: _directory traversal_.

Une traversée de dossiers est une attaque qui permet d'aller chercher n'importe quel fichier sur un système d'exploitation.
Voici un exemple courant, on liste tous les fichiers dans un dossier en les marquant comme "téléchargeables".

[Exemple non sécuritaire](../../exemples/directory/index.php)

Pour pirater cet exemple, visitez la page et cliquez sur un des 3 liens. Puis modifiez le paramètre GET pour `"../../directory/index.php"`, vous pouvez maintenant aller voir le code source de tous les fichiers PHP!

Si vous allez chercher un fichier selon un paramètre non-fiable, il est préférable d'utiliser une liste d'acceptation.
Par exemple on liste tous les fichiers du dossier téléchargeable et on valide que le nom de fichier reçu en GET correspond à un nom de fichier présent dans le dossier.
Vous pouvez aussi avoir la liste des fichiers permis dans la base de données, dans ce cas le visiteur envoie seulement un ID ce qui est plus discret.
Vous pouvez aussi entreposer les fichiers dans un serveur existant seulement pour les fichiers téléchargeables. 

Si la liste d'acceptation n'est pas possible, il faut nettoyer le nom du fichier.
Vous pouvez retirer les `. / \ ~` dans la chaîne (attention à ne pas enlever le point de l'extension).
Vous pouvez aussi utilisez la fonction basename qui semble sécuritaire (à tester).

Référence: https://www.hacksplaining.com/app/lessons/directory-traversal/prevention