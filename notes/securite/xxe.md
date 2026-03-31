# XXE

XXE est un acronyme pour _XML External Entity_.

XML est utilisé pour représenter des données structurées, souvent dans des fichiers de configuration ou des échanges entre systèmes. HTML est un type de XML, mais il existe aussi d'autres types de XML comme SVG, DOCX, etc.

Les parseurs XML supportent les entités externes: des références dans le document XML qui pointent vers une ressource externe (un fichier, une URL). Un pirate peut en abuser pour lire des fichiers sensibles sur le serveur, ou déclencher des requêtes internes (voir [SSRF](ssrf.md)).

Exemple de payload malicieux:

```xml
<?xml version="1.0"?>
<!DOCTYPE foo [
  <!ENTITY secret SYSTEM "file:///etc/passwd">
]>
<root>&secret;</root>
```

Le serveur remplace `&secret;` par le contenu de `/etc/passwd` avant de traiter le document.

## Impacts possibles

* Lecture de fichiers arbitraires sur le serveur (`/etc/passwd`, fichiers de configuration, clés privées).
* SSRF — le serveur fait des requêtes vers des services internes normalement inaccessibles.
* Déni de service (_Billion Laughs_: entités imbriquées qui explosent en mémoire).

## Pour vous protéger

* Désactiver le traitement des entités externes et des DTD (*Document Type Definition*) dans votre parseur XML.
  * **PHP 8.0+**: protégé par défaut.
  * **PHP < 8.0**: appeler `libxml_set_external_entity_loader(null)` avant de parser.
* Ne jamais parser du XML non validé provenant d'un utilisateur.
* Préférer des formats moins risqués (JSON) lorsque XML n'est pas requis.
* Attention aux surfaces d'attaque cachées: les fichiers SVG et DOCX contiennent du XML et sont aussi vulnérables si vous les parsez.

Référence: https://www.hacksplaining.com/app/lessons/xml-external-entities/prevention
