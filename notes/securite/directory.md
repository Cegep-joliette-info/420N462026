# Traversée de dossiers

En anglais: _directory traversal_.

Une traversée de dossiers est une attaque qui permet d'aller chercher n'importe quel fichier sur un système d'exploitation.
Voici un exemple courant, on liste tous les fichiers dans un dossier en les marquant comme "téléchargeables".

[Exemple non sécuritaire](../../exemples/directory/index.php)

Pour pirater cet exemple, visitez la page et cliquez sur un des 3 liens. Puis modifiez le paramètre GET pour `"../../directory/index.php"`, vous pouvez maintenant aller voir le code source de tous les fichiers PHP!

Si vous allez chercher un fichier selon un paramètre non-fiable, il est préférable d'utiliser une liste d'acceptation.
Par exemple on liste tous les fichiers du dossier téléchargeable et on valide que le nom de fichier reçu en GET correspond à un nom de fichier présent dans le dossier.
Vous pouvez aussi avoir la liste des fichiers permis dans la base de données, dans ce cas le visiteur envoie seulement un ID ce qui est plus discret.
Vous pouvez aussi entreposer les fichiers dans un serveur dédié seulement pour les fichiers téléchargeables.

```php
// Liste d'acceptation : on récupère les fichiers du dossier autorisé
$dossier = '/var/www/telechargements/';
$fichiersPermis = scandir($dossier);
$fichier = $_GET['fichier'] ?? '';

if (!in_array($fichier, $fichiersPermis)) {
    http_response_code(403);
    exit('Fichier non autorisé.');
}

readfile($dossier . $fichier);
```

Si la liste d'acceptation n'est pas possible, il faut nettoyer le nom du fichier.
La méthode la plus fiable est `realpath()` : elle résout le chemin absolu réel (en suivant les `../`) et on vérifie ensuite que le fichier se trouve bien dans le dossier autorisé.

```php
$dossier = realpath('/var/www/telechargements');
$fichier = $_GET['fichier'] ?? '';

$cheminResolu = realpath($dossier . '/' . $fichier);

// Vérifie que le chemin résolu commence bien par le dossier autorisé
if ($cheminResolu === false || !str_starts_with($cheminResolu, $dossier . '/')) {
    http_response_code(403);
    exit('Accès refusé.');
}

readfile($cheminResolu);
```

`basename()` peut aussi être utilisée pour extraire uniquement le nom de fichier sans son chemin, ce qui empêche les traversées simples. Elle est cependant moins robuste que `realpath()` car elle ne vérifie pas que le fichier existe dans le bon dossier.

```php
$fichier = basename($_GET['fichier'] ?? '');
// "../../etc/passwd" devient "passwd" — le chemin est retiré
readfile('/var/www/telechargements/' . $fichier);
```

Référence: https://www.hacksplaining.com/app/lessons/directory-traversal/prevention