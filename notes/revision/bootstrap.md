# Bootstrap

> ⚠️ AVERTISSEMENT<br>
> Bootstrap ne sera pas couvert en classe, mais vous êtes libres de l'utiliser dans vos projets. Ce cadre d'application CSS reste très populaire dans l'industrie.

Bootstrap est un cadre d'application CSS, ça vous permet de faire un beau site sans trop d'effort. Nous allons utiliser la dernière version de Bootstrap.

Bootstrap est un des cadres d'applications CSS les plus populaires. Il est très complet et permet de faire un site complet sans écrire une ligne de CSS contrairement à ses concurents.

## Utilisation

3 solutions:

 - Utiliser le CDN, inclure les lignes suivantes dans votre <head>: https://getbootstrap.com/docs/5.3/getting-started/download/#cdn-via-jsdelivr
 - Télécharger (sur le site ou avec NPM) les fichiers et les inclures dans votre fichier.
 - Utiliser NPM et inclure le SCSS de Bootstrap dans votre SCSS (vous devrez probablement modifier le chemin d'accès):

```scss
@import "../node_modules/bootstrap/scss/bootstrap";
```

La dernière solution a quelques avantages, comme une intégration plus simple de l'auto-complétion. Ça facilite aussi la modification de Bootstrap, par exemple si vous voulez changer la couleur primaire:

```scss
$primary: #FF0000;
@import "node_modules/bootstrap/scss/bootstrap";
```

La liste complète des variables est disponible dans votre projet: `node_modules/bootstrap/scss/_variables.scss`.

## Fonctionnement

Bootstrap fonctionne avec un système de grille (grid). Nous allons voir la structure d'une page. Il offre plusieurs autres outils que vous pourrez voir sur le site.

Votre balise racine doit avoir la classe 'container'. Votre container peut être avec une structure fluide (pleine largeur) ou avec des points d'interruptions (breakpoints). Pour le conteneur fluide vous devez utiliser 'container-fluid' au lieu du 'container' de base. Pour le système avec breakpoints, votre site fera 1320px maximum de largeur. Les breakpoints sont:

 - (base) - Moins de 576px de large
 - sm - 576px et plus (small)
 - md - 768px et plus (medium)
 - lg - 992px et plus (large)
 - xl - 1200px et plus (x-large)
 - xxl - 1400px et plus (xx-large)

Ensuite il faut définir la grille, on commence par définir les lignes avec un div qui a la classe "row". Ensuite il faut définir les colonnes. Bootstrap utilise un système à 12 colonnes. Si vous voulez pleine largeur il faudrait utiliser la classe 'col-12'. Pour 2 colonnes il faut deux div qui utilisent 'col-6' et ainsi de suite. Le système à 12 colonnes permet de facilement séparer votre site en 1, 2, 3, 4, 6 ou 12 colonnes. Par exemple, pour avoir une ligne pleine largeur suivit de 3 colonnes:

```html
<div class="content">
  <div class="row">
    <div class="col-12"></div>
  </div>
  <div class="row">
    <div class="col-4"></div>
    <div class="col-4"></div>
    <div class="col-4"></div>
  </div>
</div>
```

Vous pouvez utiliser les colonnes numérotés ou utiliser simplement 'col' qui va faire une division en colonne intelligente. Si votre 'row' a deux div 'col', vous aurez 2 colonnes de tailles identiques. Si votre 'row' a un div 'col-6' et deux div 'col', vous aurez 3 colonnes: une qui fait la moitié de la page (col-6) et deux qui font le quart (col). On peut aussi utiliser les breakpoints, par exemple:

```html
<div class="col-12 col-sm-6 col-md-4 col-lg-3"></div>
```

Ce div fera la pleine largeur sur un petit écran, la moitié sur un écran small, le tier sur un écran medium et le quart sur un écran large.

Vous pouvez facilement mettre des grilles dans des grilles (le content sera présent une seule fois).

Source de Bootstrap: https://getbootstrap.com/docs/5.0/getting-started/introduction/