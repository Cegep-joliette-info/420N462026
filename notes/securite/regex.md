# Injection de regex

En anglais: _Regex Injection_.

Les expressions régulières (regex) permettent de décrire un patron de caractères pour valider une entrée ou faire des recherches dans des chaînes de caractères.
Si la regex est construite à partir d'une entrée non-fiable, un pirate peut injecter une expression malicieuse qui sera évaluée directement par le serveur.

## Risques

L'injection de regex est souvent utilisée pour faire des attaques par déni de service (_DoS_) sur un serveur web.

## Injection de regex

```php
// Vulnérable — l'utilisateur contrôle la regex
$patron = $_GET['recherche'];
$resultats = preg_grep('/' . $patron . '/', $listeFichiers);
```

Un pirate peut envoyer un patron comme `(a+)+b` qui provoque un _backtracking catastrophique_ (voir section suivante), ou injecter des délimiteurs pour sortir de la regex prévue.

Pour se protéger, ne jamais construire une regex à partir d'une entrée non-fiable — définissez vos expressions directement dans le code :

```php
// Sécuritaire — la regex est définie dans le code
$patron = '/^[a-zA-Z0-9 ]+$/';
preg_match($patron, $_GET['recherche']);
```

## Danger de preg_replace

La fonction `preg_replace` présente un danger supplémentaire : le modificateur `/e` (disponible avant PHP 7) permettait d'évaluer le texte de remplacement comme du code PHP.
Même si ce modificateur est retiré en PHP 7+, si le patron ou le remplacement provient d'une entrée non-fiable, un pirate peut manipuler le résultat.

```php
// Dangereux avec PHP 5 — le modificateur /e exécutait le remplacement comme du code PHP
preg_replace('/' . $_GET['patron'] . '/e', $_GET['remplacement'], $texte);
// Un pirate envoie : patron=.* et remplacement=system('rm -rf /var/www')
// Le serveur exécute la commande shell!
```

En PHP 7+, le modificateur `/e` a été supprimé. Son remplacement officiel est `preg_replace_callback`, qui accepte une fonction de rappel au lieu d'évaluer du code :

```php
// Sécuritaire — la fonction de rappel est définie dans le code, pas par l'utilisateur
$resultat = preg_replace_callback('/\d+/', function ($correspondances) {
    return $correspondances[0] * 2;
}, $texte);
```

## Backtracking catastrophique

Même sans contrôle de la regex, un pirate peut envoyer une entrée spécialement conçue pour faire entrer une regex mal conçue dans un état de _backtracking catastrophique_ : le moteur regex doit alors évaluer un nombre exponentiel de combinaisons, paralysant le serveur.

Par exemple, la regex `(a+)+b` appliquée à la chaîne `"aaaaaaaaaaaaaaac"` (qui ne correspond pas) force le moteur à essayer toutes les façons de découper les `a` entre les groupes imbriqués avant de conclure qu'il n'y a pas de correspondance. Ajouter un seul `a` double le temps de traitement.

Quelques patrons à éviter :
- Quantificateurs imbriqués : `(a+)+`
- Disjonctions qui se chevauchent : `(a|a)+`
- Adjacences qui se chevauchent : `\d+\d+`

Pour les recherches complexes sur de grands ensembles de données, préférez un moteur de recherche comme Elasticsearch ou Lucene plutôt que des regex.

Référence: https://www.hacksplaining.com/app/lessons/regex-injection/prevention
