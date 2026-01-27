# Tailwind CSS

Tailwind CSS est un cadre d'application CSS qui utilise une approche différente de Bootstrap. Plutôt que de fournir des composants préconçus, Tailwind offre une collection de classes utilitaires qui permettent de construire des designs personnalisés directement dans le HTML.

Pour tester les exemples, vous pouvez utiliser le site suivant: https://play.tailwindcss.com/

Par exemple, pour créer un bouton avec Tailwind, vous pouvez utiliser les classes utilitaires suivantes:

```html
<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
  Bouton Tailwind
</button>
```

Ce que ces classes font:

 - `bg-blue-500`: Définit la couleur de fond du bouton, bleu moyen.
 - `hover:bg-blue-700`: Change la couleur de fond lorsque l'utilisateur survole le bouton, bleu foncé.
 - `text-white`: Définit la couleur du texte en blanc.
 - `font-bold`: Rend le texte en gras.
 - `py-2`: Ajoute un padding vertical de 0.5rem.
 - `px-4`: Ajoute un padding horizontal de 1rem.
 - `rounded`: Ajoute des coins arrondis au bouton.

La liste complète des classes est disponible dans la documentation officielle: https://tailwindcss.com/docs

## Installation

Tailwind CSS est un cadre d'application CSS différent des autres, au lieu d'inclure tout le CSS, un processus de construction est utilisé pour générer uniquement le CSS nécessaire à votre projet. Cependant, pour des tests rapides ou des petits projets, vous pouvez utiliser Tailwind directement via un CDN.

### CDN

Pour utiliser le CDN, ajoutez la ligne suivante dans la balise `<head>` de votre document HTML:

```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
```

### Compilation avec Node.js

Pour compiler le CSS, ouvrez la ligne de commande du container Docker PHP (il inclus Node.js). Déplacez-vous dans le répertoire de votre projet (où se trouve votre fichier HTML).

Sur votre ordinateur personnel vous pouvez installer Node.js directement pour une utilisation plus simple. Sur Windows, évitez la version Microsoft Store. Visitez cette page pour télécharger Node.js: https://nodejs.org/en/download/, il y a les instructions en ligne de commande et le binaire d'installation.

Ensuite, suivez les étapes décrites dans la documentation officielle: https://tailwindcss.com/docs/installation/tailwind-cli

### Compilation avec le Standalone CLI

Téléchargez le binaire standalone pour votre système d'exploitation depuis la page des versions: https://github.com/tailwindlabs/tailwindcss/releases/latest

Sur Linux il faut le `chmod +x tailwindcss-linux-x64` pour le rendre exécutable.

Ensuite dans le répertoire de votre projet, exécutez la commande suivante pour générer le fichier CSS:

```bash
./tailwindcss-linux-x64 -i index.css -o output.css --watch
```

Ici, `index.css` est le fichier d'entrée où vous pouvez inclure les directives Tailwind, `output.css` est le fichier CSS généré et le `--watch` permet de surveiller les changements pour recompiler automatiquement.

Dans votre fichier html, vous devez inclure le fichier CSS généré:

```html
<link href="output.css" rel="stylesheet">
```

Et dans le `index.css`, vous devez inclure les directives suivantes:

```css
@import "tailwindcss";
```

## A11Y

Pour l'accessibilité web, tailwind fournit des classes utilitaires pour gérer les états de focus, comme `focus:outline-none` pour supprimer le contour par défaut et `focus:ring-2 focus:ring-blue-500` pour ajouter un anneau de focus personnalisé.

Il founit aussi des classes pour gérer la visibilité, comme `sr-only` pour cacher un élément visuellement mais le laisser accessible aux lecteurs d'écran.

## Largeur

Rappel: 1 rem est égal à la taille de la police de la balise racine (balise html, habituellement 16px).

```html
!!! Liste non exaustive !!!
<div class="w-24">Largeur fixe de 6rem (96px), le chiffre dans la classe en un 1/4 de rem</div>
<div class="w-1/2">Largeur de 50% du parent</div>
<div class="w-full">Largeur de 100% du parent</div>
<div class="w-3xs">Taille 3x-small, 16rem (256px)</div>
<div class="w-xs">Taille x-small, 20rem (320px)</div>
<div class="w-xl">Taille x-large, 36rem (576px)</div>
<div class="w-3xl">Taille 3x-large, 48rem (768px)</div>
```

Vous pouvez ajouter un préfixe `max-` ou `min-` pour définir une largeur maximale ou minimale. Par exemple `max-w-lg` définit une largeur maximale de 32rem (512px).

Pour de la réactivité, vous pouvez utiliser les préfixes de points d'interruption (breakpoints) comme `sm:`, `md:`, `lg:`, `xl:` et `2xl:`. Par exemple, `md:w-1/2` applique une largeur de 50% à partir du point d'interruption medium (768px) et plus.
Notez que Tailwind prévoit du "mobile first", donc sans préfixe c'est pour mobile (petit écran).

## Grid

Tailwind CSS offre des classes utilitaires pour créer des grilles flexibles et réactives. Voici quelques exemples de base:

```html
<div class="grid grid-cols-3 gap-4">
    <div class="bg-blue-500 p-4">Colonne 1</div>
    <div class="bg-blue-500 p-4">Colonne 2</div>
    <div class="bg-blue-500 p-4">Colonne 3</div>
</div>
```

Le code précédent crée une grille avec 3 colonnes et un espace (gap) de 1rem entre les éléments. Vous pouvez ajuster le nombre de colonnes en modifiant la classe `grid-cols-{n}` où `{n}` est le nombre de colonnes souhaité.

## Autres combinaisons

Voici quelques exemples:

```html
<!-- Centrer un élément horizontalement et verticalement -->
<div class="flex items-center justify-center w-lg h-64 border border-gray-300">
    <div class="bg-blue-500 p-4">Centré</div>
</div>
```

```html
<!-- Créer une carte avec ombre et arrondis -->
<div class="max-w-sm rounded overflow-hidden shadow-lg">
    <img class="w-full" src="https://picsum.photos/400/200" alt="Image de la carte">
    <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">Titre de la carte</div>
        <p class="text-gray-700 text-base">
            Ceci est une description de la carte.
        </p>
    </div>
</div>
``` 

```html
<!-- Formulaire avec accessibilité web -->
<div class="bg-gray-200">
  <div class="min-h-screen flex items-center justify-center px-4">
    <form class="max-w-sm w-full bg-white rounded-lg shadow p-6 space-y-4">
      <h2 class="text-2xl font-bold mb-4">Connexion</h2>
      <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
      <input type="text" id="username" class="mt-1 block w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">

      <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
      <input type="password" id="password" class="mt-1 block w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">

      <button type="button" class="w-full bg-teal-500 text-white font-semibold py-2 rounded-md shadow hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">Connexion</button>
    </form>
  </div>
</div>
```

## Thème sombre

Tailwind CSS facilite la mise en place d'un thème sombre grâce au préfixe `dark:`. Vous pouvez définir des styles spécifiques pour le mode sombre en utilisant ce préfixe. Par exemple:

```html
<div class="bg-white dark:bg-gray-800 text-black dark:text-white p-4">
  Ceci est un exemple de thème sombre avec Tailwind CSS.
</div>
```

Tailwind utilise automatiquement la préférence de l'utilisateur pour le thème sombre en utilisant la requête média `prefers-color-scheme`. Vous pouvez également forcer le mode sombre en ajoutant la classe `dark` à un élément parent, comme la balise `<html>` ou `<body>`.

## Simplification

Si vous voulez que tous vos boutons aient le même style, vous pouvez créer une classe personnalisée dans votre fichier CSS (donné dans le input au compilateur) et utiliser la directive `@apply` de Tailwind pour appliquer les classes utilitaires. Par exemple:

```css
@import "tailwindcss";

button {
    @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded
}
```

Si vous voulez écraser une propriété, vous pouvez ajouter un `!` à la fin de la classe. Par exemple, pour forcer une couleur de fond rouge:

```html
<button class="bg-red-400!">Bouton Rouge</button>
```