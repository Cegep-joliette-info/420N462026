# Téléversement

Voici un exemple simple de formulaire HTML:

```html
<form method="post" enctype="multipart/form-data">
    <input type="file" name="fichier">
    <button>Téléverser</button>
</form>
```

Les attributs du form sont importants, ça prend les 2 valeurs pour que le fichier se rende à PHP.

En PHP le fichier va se trouver dans un fichier temporaire, toutes les informations sur le fichier sont présentes dans la variable superglobale `$_FILES`. Les autres champs du formulaire sont encore disponibles dans `$_POST`.

Une fois les vérifications terminées, déplacez le fichier de son emplacement temporaire vers la bonne place (le dossier d'image par exemple). Utilisez la fonction [`move_uploaded_file`](https://www.php.net/manual/fr/function.move-uploaded-file.php).