# Variables

Contrairement à la plupart des langages existants, vous n'avez pas besoin de déclarer vos variables. Ce n'est même pas possible d'utiliser le "use strict" de JavaScript pour forcer cette fonctionnalité. Donc les variables "foo" et "Foo" sont différentes, ce qui peut causer d'énormes mots de têtes.

Toutes les variables commencent par le signe '$', suivit d'une lettre ou d'un trait de soulignement. Par la suite vous pouvez ajoutez des chiffres et même des accents (c'est mal les accents dans des noms de variables...).

Pourquoi le signe `$`? Historiquement PHP se voulait un remplacement plus simple à Perl, qui met un `$` devant les variables et `@` devant les tableaux. PHP a seulement gardé le `$`.

Les constantes doivent utiliser le mot-clé `const` et n'ont pas besoin du `$`:

```php
$var = 'Une variable';
$Var = 'Une 2e variable';
const VIVE_PHP = 'C\'est la vie!';
```