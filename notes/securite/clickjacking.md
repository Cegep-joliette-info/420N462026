# Détournement de clic

En anglais: *clickjacking*

Technique de piratage très simple.
Je mets votre site dans un `iframe` et je mets le `iframe` "fullpage" dans mon site.
Je peux mettre le `iframe` dans une balise `a`, quand on clique n'importe où je peux ouvrir les pages que je veux.
Pire, je peux même détecter ce qui se passe dans le `iframe`.

## Risques

Le clickjacking ne compromet pas directement votre serveur, mais il peut nuire à vos utilisateurs:

* Vol de credentials: afficher un faux formulaire de connexion par-dessus le vrai.
* Activation de la webcam ou du microphone à l'insu de l'utilisateur (exploitait autrefois la page de paramètres Adobe Flash).
* Propagation de malware en redirigeant les clics vers des liens de téléchargement malicieux.
* Promotion d'arnaques en ligne en faisant cliquer les utilisateurs sur des éléments invisibles.
* Propagation de vers sur les réseaux sociaux (Twitter, MySpace, etc.).

## Protection

Pour se protéger c'est simple, dans votre routeur ajoutez les lignes suivantes:

```php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);
```

La première ligne est un entête HTTP conçu pour contrer le clickjacking.
Il y a 3 valeurs possibles:
* DENY: Ne peut pas être affiché dans un `iframe`.
* SAMEORIGIN: Peut être dans un `iframe` si le domaine du parent est le même que celui du `iframe`.
* ALLOW-FROM uri: Peut être dans un `iframe` si le parent est du domaine spécifié

Le "X-Frame-Options" a été remplacé par le CSP, on le garde seulement pour les vieux navigateurs.

Pour le CSP, il y a 3 valeurs possibles au "frame-ancestors":

* none: Comme le DENY
* self: Comme le SAMEORIGIN
* url: Comme le ALLOW-FROM

Si vous avez plusieurs CSP (par exemple pour le [xss](xss.md)), séparez chaque instruction par un point-virgule.

## Frame-killing (technique obsolète)

Avant que `X-Frame-Options` et le CSP soient largement supportés, la seule protection disponible était le _frame-killing_: un bout de JavaScript qui détecte si la page est chargée dans un `iframe` et force la sortie.

```html
<style>
  html { display: none; }
</style>
<script>
  if (self === top) {
    document.documentElement.style.display = 'block';
  } else {
    top.location = self.location;
  }
</script>
```

Cette technique était nécessaire pour les navigateurs très anciens qui n'acceptaient ni `X-Frame-Options` ni le CSP, comme Internet Explorer 6 et 7, les vieilles versions de Firefox (avant Firefox 3.6.9) et Safari (avant Safari 4).
Aujourd'hui tous les navigateurs modernes supportent au moins `X-Frame-Options`, cette technique n'est donc plus nécessaire.

Référence: https://www.hacksplaining.com/app/lessons/click-jacking/prevention