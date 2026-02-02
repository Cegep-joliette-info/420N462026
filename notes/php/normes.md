# Normes de programmations

Les normes écrites ici sont basés sur [PSR-1](https://www.php-fig.org/psr/psr-1/). Certaines normes ne seront pas utiles avant les chapitres plus avancés.

## Fichiers

Les fichiers doivent utiliser les balises `<?php ?>` ou `<?= ?>`, aucune autre balise PHP ne sera accepté.

Les fichiers doivent être encodés en UTF-8 sans BOM (vous pouvez le voir facilement avec Notepad++).

Chaque fichier doit déclarer des symboles (classes, fonctions, constantes, etc.) OU avoir de la logique avec des effets secondaires (affichage), pas les deux dans un seul fichier.

## Nommage

Le nom des classes doivent être en notation Pascal (StudlyCaps). Une classe doit être la seule chose présente dans son fichier.

Les espaces de noms (namespaces) doivent suivre la norme de chargement automatique [PSR-4](https://www.php-fig.org/psr/psr-4/). Donc chaque espace de nom est un dossier et chaque barre oblique est un sous-dossier. Le nom de chaque espace de nom doit être en notation Pascal.

Les constantes doivent être en majuscules, chaque mot séparé par un trait de soulignement.

Les méthodes doivent être en notation chameau (camelCase).

Les propriétés et variables ne sont pas définis dans le PSR-1. Par contre, pour le cours, je vous demande d'utiliser la notation chameau.