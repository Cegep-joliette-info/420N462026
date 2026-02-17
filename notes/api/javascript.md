# JavaScript

JavaScript est un langage faiblement typé, c'est le seul langage qui permet de faire de la programmation web côté client. Comme nous allons nous concentrer sur le PHP cette session, on va utiliser JavaScript vanille avec quelques librairies. Vous pouvez utiliser d'autres librairies JavaScript telle que jQuery.

Pour commencez, consultez [cette cheatsheet JavaScript](https://www.codecademy.com/learn/introduction-to-javascript/modules/learn-javascript-introduction/cheatsheet) (tous les 'Topics' dans le menu de gauche). Vous avez vu la plupart des 'Topics' dans le précédant cours de Web, sauf 'Iterators'. C'est tout de même des sujets intéressant à connaître. Rappel de quelques particularités de JavaScript:

 - On ne type rien, tout est typé dynamiquement pour le meilleur et pour le pire
 - Pour déclarer une variable, utilisez 'let', pas 'var'. Ce dernier était utilisé dans le vieux JavaScript (norme es5 et moins, 2015 et avant)
 - Tout est public dans une classe, mais on va mettre un '_' devant le nom de la propriété/méthode qui doit être utilisé comme si elle était privé

Pour modifier le HTML, il faut travailler avec DOM (Document Object Model). Voici quelques méthodes d'accès et de modification du DOM:

```js
document; // Constante, élément racine de la page, contient tout votre HTML
document.getElementById('idDeLaBalise'); // Retourne l'élément avec l'ID donné
document.getElementsByTagName('div'); // Retourne tous les div dans un tableau
document.getElementsByClassName('row'); // Retourne toutes les balises contenant la classe row
document.querySelector('.row'); // Prend un sélecteur CSS en paramètre et retourne la première balise trouvé
document.querySelectorAll('.row'); // Idem, mais retourne un tableau d'éléments
element.setAttribute('value', 'toto'); // Met la valeur toto dans l'attribut value de la balise sélectionné
element.getAttribute('value'); // Obtient la valeur de l'attribut value de la balise sélectionné
element.innerText; // Get ou Set le texte à l'intérieur de la balise
element.innerHtml; // Get ou Set le HTML à l'intérieur de la balise
let div = document.createElement('div'); // Crée une balise div
element.appendChild(div); // Ajoute div en tant qu'enfant à element
element.insertAdjacentElement('beforebegin', div); // Ajoute div juste avant element
element.addEventListener('click', evt => {}); // Ajoute un événement onClick sur element
```

## Day.js

Day.js permet de rendre les dates agréables à manipuler. Avant on utilisait Moment.js (encore le plus populaire), mais la librairie n'est plus maintenu. On passe donc à Day.js, plus récent et plus léger. Pour l'ajouter à votre application, vous pouvez passer par CDN:

 - https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.19/dayjs.min.js
 - https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.19/locale/fr-ca.min.js

Ou via NPM:

```
npm install dayjs
```

Ensuite vous devez inclure les deux fichiers (le dayjs et le fichier de traduction) en bas du body, avant votre code JavaScript.

Voici quelques fonctions utiles:

```js
dayjs.locale('fr-ca'); // Charger la traduction français
let now = dayjs(); // Moment actuel
let feteDeLaCovid = dayjs({year: 2019, month: 11, day: 31}); // 31 décembre 2019, mois 0 = janvier
let dansUneSemaine = dayjs().add(7, 'day');
let afficherLaDate = dayjs('2021-01-31').format('LL'); // string '31 janvier 2021'
```

Afin de bien fonctionner, la dernière ligne a besoin du plugin suivant: https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.19/plugin/localizedFormat.min.js et il faut inclure le plugin avec la commande: `dayjs.extend(dayjs_plugin_localizedFormat);`.