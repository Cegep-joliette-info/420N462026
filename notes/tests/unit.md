# Tests unitaires

## Installation

Pour l'installation et la configuration initiale de Codeception, référez-vous à [codecept.md](codecept.md).

Pour l'autoload de vos classes, ajoutez le autoload dans votre `composer.json`:

```json
{
    "require-dev": {...},
    "autoload": {
        "psr-0": {
            "": "src/"
        }
    }
}
```

Ensuite exécutez `composer update` pour appliquer l'autoload.

## Générer un test

Pour générer un fichier de test unitaire:

```
php vendor/bin/codecept g:test Unit NomDuTest
```

## Structure des tests

Nos dossiers (et namespaces) et fichiers (les classes) auront la même structure dans le dossier `tests/Unit` que dans le `src`. Par contre, chaque classe de ce dossier vont être suffixé de 'Test'. Exemple:

```
Fichier : src/Controller/UserController.php
Namespace: Controller
Classe: UserController

Fichier : tests/Unit/Controller/UserControllerTest.php
Namespace: Controller
Classe: UserControllerTest
```

Notre classe de test va hériter de la classe `\Codeception\Test\Unit`.

## Structure d'un test unitaire

En PHP, la norme est d'appeler le test avec le nom de la fonction à tester précédé de 'test' (pour tester Moyenne utilisez testMoyenne). Il existe plusieurs autres normes, on va garder ça simple ici.

Un test unitaire est séparé en 3 sections, en anglais on les appels les AAA: Apprêter (Arrange), Agir (Act) et Affirmer (Assert).

  * La partie Apprêter sert à initialiser notre test: créer les variables, créer les simulacres, etc.
  * La partie Agir sert à exécuter la fonction à tester
  * La partie Affirmer sert à vérifier que la fonction nous a donné le bon résultat

Par exemple, on veut tester la fonction suivante:

```php
function Somme(int $a, int $b) : int {
    return $a + $b;
}
```

Notre test ressemblera à:

```php
function testSomme() : void {
    // Apprêter
    $nombre1 = 1;
    $nombre2 = 2;
    $resultatAttendu = 3;

    // Agir
    $resultat = Somme($nombre1, $nombre2);

    // Affirmer
    $this->assertEquals($resultatAttendu, $resultat);
}
```

Il existe plusieurs 'assert' pour la partie Affirmer. Utilisez l'autocomplétion de votre IDE pour les trouver. J'utilise souvent les 'asserts' suivant:

 * assertEquals
 * assertNotEquals
 * assertTrue
 * expectException

## Fournisseur de données

Si vous voulez tester votre fonction avec plusieurs valeurs différentes, vous pouvez utiliser un fournisseur de données (Data Provider). Par exemple, si on veut un fournisseur de données pour notre fonction somme, on crée la fonction suivante dans la même classe que la fonction somme:

```php
public function sommeProvider(): array {
    return [
        [0, 0, 0],
        [1, 1, 2]
    ];
}
```

Chaque élément dans le tableau va être un test. Chaque sous-tableau fournis les données pour un test, ils seront données en paramètre au test. Le fournisseur peut remplacer la partie 'Apprêter' du test. Notre test maintenant va devoir recevoir 3 paramètres et identifier le fournisseur de données à l'aide d'un commentaire:

```php
/**
 * @dataProvider sommeProvider
 */
public function testSomme(int $a, int $b, int $resultatAttendu): void {
    $resultatObtenu = somme($a, $b);
    $this->assertEquals($resultatAttendu, $resutltatObtenu);
}
```

## Préparation et démontage

Souvent vous allez effectuer des actions avant chaque test, par exemple créer la classe à tester. Nous allons donc utiliser la fonction `_before` (Préparation) pour créer ces variables. La fonction `_before` sera appelée avant chaque test, ce qui vous permet d'exécuter chaque test de manière indépendante.

Pour exécuter du code après chaque test (pour faire du nettoyage par exemple), il faut utiliser la fonction de démontage: `_after`.

```php
protected function _before()
{
    $this->maClasse = new MaClasse();
}

protected function _after()
{
    // nettoyage
}
```

## Couverture de code

La couverture de code (code coverage) est une métrique qui indique combien de lignes de codes sont testés et combien ne le sont pas. Pour sortir cette métrique, il faut ajouter à votre `codeception.yml`:

```yml
coverage:
    enabled: true
    include:
        - src/*
```

Ensuite il faut exécuter Codeception avec les paramètres suivants:

```
php vendor/bin/codecept run Unit --coverage --coverage-html html
```

Codeception va sortir les métriques dans un nouveau dossier appelé html. Ouvrez le fichier index.html pour voir la couverture pour chaque fichier.

## Déverminage

Les tests unitaires ne fonctionnent pas? Voici quelques trucs.

Si Codeception dit qu'il n'y a aucun test, c'est probablement que le test a planté. Regardez bien la console, remontez les premières lignes affichées.

En tout temps, un petit `composer update` ne fait pas de tord, parfois ça règle des problèmes par magie.

Vérifier le nom des fichiers, namespaces, classes et fonctions. Codeception est très chialeux sur la nomenclature. Le fichier de test doit se terminer par `Test.php`.
