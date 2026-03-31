# XML Bombs

Aussi appelé attaque _Billion Laughs_, c'est une attaque par déni de service exploitant l'expansion récursive des entités XML.

Un document XML minuscule peut se transformer en plusieurs gigaoctets en mémoire au moment du parsing, rendant le serveur inutilisable.

## Comment ça fonctionne

Les DTD permettent de définir des entités qui référencent d'autres entités. En les imbriquant, chaque niveau multiplie l'expansion par 10:

```xml
<?xml version="1.0"?>
<!DOCTYPE lolz [
  <!ENTITY lol "lol">
  <!ENTITY lol2 "&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;">
  <!ENTITY lol3 "&lol2;&lol2;&lol2;&lol2;&lol2;&lol2;&lol2;&lol2;&lol2;&lol2;">
  ...
  <!ENTITY lol9 "&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;">
]>
<lolz>&lol9;</lolz>
```

9 niveaux × 10 références = 10⁹ expansions → ~3 Go en mémoire à partir d'un fichier de moins de 1 Ko.

C'est lié au [XXE](xxe.md): les deux exploitent le même mécanisme de DTD, mais XXE vole des données tandis que les XML bombs saturent la mémoire.

## Pour vous protéger

* Désactiver le traitement des DTD dans votre parseur XML — c'est la protection la plus efficace contre les deux attaques (XXE et XML bombs).
  * **PHP 8.0+**: protégé par défaut.
  * **PHP < 8.0**: appeler `libxml_set_external_entity_loader(null)` et éviter le flag `LIBXML_NOENT`.
* Si les DTD sont nécessaires, limiter la taille maximale d'expansion des entités.
* Imposer une taille maximale sur les documents XML reçus avant même de les parser.
* Préférer JSON lorsque XML n'est pas requis.

Référence: https://www.hacksplaining.com/app/lessons/xml-bombs/prevention
