# Tests

Nous allons voir deux types de tests: les tests unitaires et les tests d'acceptation.

Le test unitaire sert à tester chaque classe, chaque fonction de manière isolée. Il faut donc contrôler toutes les dépendances de la classe et de la fonction testé. On ne test que le code, la base de données et l'interface ne font pas partis de ces tests.

Les tests d'acception servent à valider que les demandes des clients ont bien été répondus. On va tester de l'interface jusqu'à la base de donnée.

Il existe aussi les tests fonctionnels, qui sont un mélange des deux types de tests précédents. Ils testent une partie de l'application, par exemple le calcul de taxe, sans nécessairement tester l'interface. Une grande différence entre les tests fonctionnels et les tests d'acceptation est le point de vue: les tests fonctionnels sont générés pour le QA tandis que les tests d'acceptation sont générés pour les utilisateurs finaux. Nous n'allons pas utiliser les tests fonctionnels, nous allons seulement faire des tests unitaires et d'acceptation.

Pour plus d'information et des exemples dans un autre contexte, vous pouvez vous référer à mon rapport de maitrise: https://github.com/Padreik/rapport-maitrise/blob/master/chapitre_test.tex

Pour toutes les commandes de ce chapitre, si vous voulez les exécuter dans votre terminal sans les faire dans le container, vous devez les précéder de:

```
# Composer
docker compose exec php ...

# Codeception
docker compose exec php sh -c "cd /app && ..." 

# Exemples:
docker compose exec php composer require "codeception/codeception" --dev
docker compose exec php sh -c "cd /app && vendor/bin/codecept run" 
```