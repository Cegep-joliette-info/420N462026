# Affichage

## Afficher dans le script

Lorsque vous voulez afficher quelque chose à l'écran, la méthode principale est `echo`. echo permet d'afficher des chaînes de caractères dans le HTML. Cette fonction peut aussi ajouter du HTML dans la page.

```php
echo("Bonjour <b>$name</b>"); // Affiche le nom en gras
```

Autrefois echo ne prenait pas de parenthèse. La pratique est resté même si ce n'est pas bien. Pourquoi utiliser une fonction sans parenthèse alors que toutes les autres en demandent?

```php
echo "toto";
```

Je me sert parfois de echo pour déverminer le code. Si vous voulez voir le contenu d'un objet ou d'un tableau, il faut utiliser `print_r` ou `var_dump`. print_r donne peu d'information (clés et valeurs du tableau) tandis que var_dump va donner plus d'informations, tel que le type. Pour cette raison j'utilise plus souvent print_r car il y a juste l'information que j'ai besoin. Par contre, les deux fonctions n'affiche qu'une profondeur de 3 (3 niveaux d'objets ou tableaux imbriqués). Si vous avez xdebug d'installé dans vos modules PHP, il va afficher votre print_r ou var_dump de manière plus belle avec une profondeur de 10 par défaut. Vous pouvez augmenter cette profondeur mais faites attentions aux boucles infinis dans les objets!

## Script dans le HTML

Important: mettre votre logique avant le début du HTML afin de ne mettre que le code d'affichage dans le HTML. Plus tard dans la session nous allons séparer la logique de l'affichage dans des fichiers différents.

### echo

Pour faire un echo dans votre HTML, vous pouvez utiliser le echo normal:

```php
<h1>Bienvenue <?php echo($name); ?></h1>
```

Ou le echo raccourcis:

```php
<h1>Bienvenue <?= $name ?></h1>
```

### boucles et conditions

Si vous devez utiliser des boucles ou des conditions dans votre HTML (par exemple, afficher un bouton seulement si l'utilisateur est connecté ou afficher tous les messages reçus), vous pouvez le faire simplement comme ceci:

```php
<form>
  <?php if ($logguedIn) { ?>
    <button>Logout</button>
  <?php } ?>
</form>
```

Ou avec les ':' au lieu des accolades:

```php
<form>
  <?php if ($logguedIn): ?>
    <button>Logout</button>
  <?php endif ?>
</form>
```
Important: si vous utilisez les ':' au lieu des accolades dans votre HTML, soyez constant!