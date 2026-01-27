# CSS et SCSS

SCSS (Syntactically Awesome Style Sheets) est une préprocesseur de CSS. Il faut compiler les fichiers SCSS en fichiers CSS pour que le navigateur puisse les utiliser. SCSS est une couche supplémentaire à CSS, voici donc un petit résumé de CSS.

## CSS

Liens utiles:

 - [Sélecteurs CSS](https://developer.mozilla.org/fr/docs/Web/CSS/CSS_Selectors)
 - [Pseudo-classes CSS](https://developer.mozilla.org/fr/docs/Web/CSS/Pseudo-classes#liste_des_pseudo-classes_standards)
 - [Unités CSS](https://developer.mozilla.org/fr/docs/Web/CSS/CSS_Values_and_Units#dimensions) (em, rem, px, etc.)
 - [Propriétés CSS](https://www.w3schools.com/cssref/default.asp)

On applique le CSS sur des balises HTML. Il existe 4 moyens de base pour identifier la balise à modifier:

 - Le nom de la balise (div, body, input, etc.)
 - L'attribut ID de la balise, cet attribut doit être unique dans une page et peut être ajouté à toutes les balises HTML. On met un '#' devant l'ID en CSS
 - L'attribut class de la balise, cet attribut peut contenir plusieurs classes différentes (séparés par des espaces) qui peuvent être utilisés à plusieurs endroits. On met un '.' devant la classe en CSS.
 - N'importe quelle attribut du tag, par exemple l'attribut name. On va utiliser les sélecteurs CSS:
   - [name] - Pour sélectionner toutes les balises qui ont l'attribut name
   - [name="toto"] - (bis) avec la valeur toto
   - [name^="toto"] - (bis) avec la valeur qui débute par toto (sélectionne le name totoaaaa)
   - [name$="toto"] - (bis) avec la valeur qui termine par toto (sélectionne le name aaaatoto)
   - [name*="toto"] - (bis) avec la valeur qui contient toto (sélectionne le name aaaatotoaaaa)
   - [class~="toto"] - Sélectionner toutes les balises qui ont l'attribut class qui contient le mot toto

Exemple:

```
HTML:
<body>
  <div id="header">...</div>
  <div class="content autreclass">...</div>

CSS:
body {...}
#header {...}
.content {...}
```

On utilise parfois aussi le sélecteur '*' qui sélectionne toutes les balises. Vous pouvez utiliser le même CSS pour plusieurs sélecteurs en séparant chaque sélecteur par une virgule. On peut aussi créer des sélecteurs plus complexe en combinant les sélecteurs:

 - div.content = (Deux sélecteur collés), les deux sélecteurs doivent s'appliquer à la même balise
 - div .content = (Un espace entre 2 sélecteurs), la classe content qui a un parent div
 - div > .content = La classe content qui a un parent immédiat div
 - div ~ .content = La classe content qui a un frère précédant div
 - div + .content = La classe content qui a un frère précédant immédiat div

Vous avez aussi des pseudos-classes qui peuvent être utiles, par exemple sélectionner le premier enfant d'une balise, ou le dernier li d'une liste. Voici la liste complète: https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes

Il y a trop de styles pour tous les nommer ici, je vous conseil de consulter la liste suivante: https://developer.mozilla.org/en-US/docs/Web/CSS/Reference

Ce n'est pas très utile vu qu'on utilise Bootstrap, mais sans ça c'est conseillé d'utiliser un "reset". C'est un fichier CSS qui remet tout à zéro, donc il ne devrait plus y avoir de différence entre les navigateurs. Voici un [exemple](https://meyerweb.com/eric/tools/css/reset/reset.css).

## SCSS

SCSS permet de faire de la programmation et de simplifier notre CSS. Il ne faut pas mélanger Sass et SCSS, Sass est la 'vielle' version de SCSS.

Pour utiliser SCSS, il faut installer sass avec la commande:

```
npm install -g sass
```

Ensuite, dans PHPStorm, créez un fichier de type "stylesheet" avec l'extension scss. PHPStorm va vous demandez de configurer un "File Watcher". Habituellement la config initiale est bonne, mais une erreur dans le PATH au cégep nous oblige a donner le chemin absolu vers l'exécutable de sass. Donc dans la case programme, inscrivez: "C:\Users\1234567\AppData\Roaming\npm\sass" (remplacez les chiffres par votre DA). Une fois le tout configuré, à la sauvegarde d'un fichier scss PHPStorm va automatiquement compiler vers une fichier css.

Ça ressemble à quoi SCSS? Par exemple, au lieu du CSS suivant:

```css
div.menu-bar li > ul {
  display: none;
}
div.menu-bar li:hover > ul {
  display: block;
}
```
On peu écrire le SCSS:

```scss
div.menu-bar li {
  & > ul {
    display: none;
  }
  &:hover > ul {
    display: block;
  }
}
```

Une fois compilé, le SCSS va donner la même chose que le CSS. Dans cet exemple SCSS nous sert principalement à éviter de se répéter. Le '&' permet de 'copier' le sélecteur parent. On s'en sert avec les sélecteur plus complexes seulement. Pour un exemple plus simple:

```css
CSS:
div .content {
  display: block;
}

SCSS:
div {
  .content {
    display: block;
  }
}
```

Le CSS et le SCSS précédents font exactement la même chose, pas besoin du '&' lorsqu'il y a seulement un espace qui sépare les sélecteurs.

SCSS et CSS permettent d'utiliser des variables et des opérateurs mathématiques (nouveau pour CSS). SCSS permet d'utiliser des fonctions et l'héritage. Voir la référence SCSS: https://sass-lang.com/guide