# Tests d'acceptation

Pour générer un test d'acceptation:

```
php vendor/bin/codecept g:cest Acceptance NomDuTest
```

## Test d'acceptation

Exemple du contenu d'un test (pris sur le site https://codeception.com/docs/03-AcceptanceTests):

```php
$I->amOnPage('/login');
$I->fillField('username', 'davert');
$I->fillField('password', 'qwerty');
$I->click('LOGIN');
$I->see('Welcome, Davert!');
```

Roulez la commande suivante pour exécuter les tests:

```
php vendor/bin/codecept run
```

## Gherkin

Un vrai bon test d'acceptation doit être lisible par un client non-programmeur. La solution précédente peut être difficile à lire pour un néophyte, on utilise donc Gherkin dans ces cas. Il faudrait écrire au moins un test Gherkin par demande client, le client peut même les valider. Pour configurer Gherkin, ajoutez la section "Gherkin" dans votre `Acceptance.suite.yml`:

```yml
gherkin:
    contexts:
        default:
            - Tests\Support\AcceptanceTester
```

Gherkin est un format d'écrite de test en langage "commun", souvent en anglais mais pourrait être traduit en français. Pour les besoins du cours, vous pouvez les écrire en anglais. Pour générer un fichier Gherkin, exécutez la ligne suivante dans la console Docker:

```
php vendor/bin/codecept g:feature Acceptance nomdutest
```

Cette ligne génère un fichier "nomdutest.feature". Chaque fichier Gherkin test une fonctionnalité et débute par une ligne "Feature". Ensuite il y a 3 lignes de commentaires:

 * "Afin de" / "In order to": Qu'est-ce qu'on veut faire
 * "En tant que" / "As a": Quel rôle d'utilisateur à tester (utilisateur connecté, admin, visiteur, etc.)
 * "Je dois" / "I need to": Description du test

Exemple du site officiel:

```gherkin
Feature: checkout
  In order to buy product
  As a customer
  I need to be able to checkout the selected products
```

Ensuite nous pouvons écrire des scénarios. Par exemple dans le fichier "login.feature" nous pourrions avoir un scénario de test échoué et un scénario de test réussis.

Chaque scénario est séparé en 3 sections: "Étant donné que", "Quand" et "Alors" ou en anglais: "Given", "When" et "Then".

 * Given: Précondition au test (on est sur une page, on est connecté, la BD est remplis, etc.)
 * When: Étapes réalisés par l'utilisateur
 * Then: Tests à effectuer

Chaque section peut avoir plus qu'une ligne en utilisant "Et" ou "And". Exemple du site officiel:

```gherkin
Scenario --
 Given i have product with $600 price in my cart
 And i have product with $1000 price in my cart
 When i go to checkout process
 Then i should see that total number of products is 2
 And my order amount is $1600
```

Une fois votre Gherkin écrit, il faut écrire les fonctions, vous pouvez les générer automatiquement avec la commande (dans Docker):

```
php vendor/bin/codecept gherkin:snippets Acceptance
```

Copiez les fonctions dans votre "AcceptanceTester" (dans le dossier `tests/support`) et complétez les. Idéalement, renommez les paramètres de la chaîne gherkin. Exemple:

```php
// Code généré par le gherkin:snippets
#[\Codeception\Attribute\Given('I am on :arg1')]
public function iAmOn($arg1)
{
    throw new \PHPUnit\Framework\IncompleteTestError('Step `I am on :arg1` is not defined');
}

// Code complété
#[\Codeception\Attribute\Given('I am on :page')]
public function iAmOn($page) {
    $this->amOnPage($page);
}
```