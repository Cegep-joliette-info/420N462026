# Vol de session

En anglais: _session hijacking_ ou _session fixation_.

Les pages _Session Fixation_ et _Weak Session IDs_ de HackSplaining sont regroupées dans ce chapitre, car les méthodes de protection sont similaires.

Une session est associée à un ID entreposé dans un cookie.
Si un pirate obtient cet ID, il met l'ID dans son cookie et il obtient la même session que le propriétaire original (il est donc connecté).

## Cookie de session

Par défaut le cookie de session n'est pas sécurisé, il est vulnérable aux attaques XSS.
Le pirate pourrait accéder au cookie avec une attaque XSS et s'envoyer l'ID de session par fetch.

On avait déjà parlé dans le chapitre [fuite d'information](leak.md), mais vous pouvez changer le nom du cookie pour se protéger d'une attaque à l'aveugle:

```php
session_name("MonCookieDeSession");
```

Vous pouvez sécuriser encore plus votre cookie avec la ligne suivante:

```php
session_set_cookie_params(0, '/', '', true, true);
```

Les 5 paramètres de la fonction `session_set_cookie_params`:

1. Durée de vie du cookie, 0 pour dire que le cookie se détruit à la fermeture du navigateur. On pourrait mettre 3600 pour 1h.
2. Chemin d'accès du cookie, le cookie est valide seulement pour ce chemin d'accès.
   `/` veut dire que le cookie est valide pour tout le site.
   On pourrait mettre `/1234567` pour dire que notre cookie de session est valide pour ce dossier seulement.
3. Domaine lié au cookie. Lorsque c'est une chaîne vide, PHP va mettre le domaine actuel.
4. Lorsque vrai, le cookie est seulement envoyé sur https.
   Doit être faux en localhost, vrai en production.
5. Lorsque vrai, le cookie n'est pas accessible par JavaScript.
   Doit toujours être vrai.
   Attention, le cookie est quand même visible dans la console, donc vulnérable au self-XSS.

Le paramètres 2 va changer si vous êtes en développement ou sur le FTP, il faut donc mettre cette valeur dans votre fichier de configuration et charger le bon fichier de configuration. Vous pouvez aussi consulter la superglobale $_REQUEST pour charger la bonne valeur selon le domaine utilisé.

Notez que les deux fonctions (`session_name` et `session_set_cookie_params`) doivent être appelés avant le `session_start`.

## ID de session

Ne pas interagir directement avec l'ID et le cookie de session.
Utilisez les fonctions PHP prévus.
N'affichez jamais l'ID de session et si possible, ne pas y accéder directement.

Une fonction utile pour protéger votre session est:

```php
session_regenerate_id(true)
```

Cette fonction va créer un nouveau ID de session et le paramètre dit de supprimer l'ancien.
L'information qui se trouve dans la session demeure valide.

Si vous allez voir sur le site [php.net](https://www.php.net/manual/en/function.session-regenerate-id.php), vous allez voir qu'il y a un avertissement.
Cette fonction pourrait être néfaste si le réseau est instable, ce qui arrive souvent en campagne.
Nous allons quand même utiliser cette fonction dans le TP2 afin d'ajouter de la sécurité sans trop se compliquer la vie.
En entreprise il faudrait faire la même logique, mais avec beaucoup plus de code...

Il faut appeler cette fonction à 2 moments:

1. À la connexion;
2. À toutes les X minutes (échéance stockée dans la sesison);

De cette manière, si un pirate a réussi à voler l'ID de session, la fonction va invalider l'ID volé une fois de temps en temps.

## Forgeage d'ID de session

Un pirate peut envoyer un lien à une victime qui contient un ID de session pré-déterminé, par exemple `http://localhost/?PHPSESSID=1234567`.
Si la victime clique sur ce lien, elle va se connecter à la session 1234567, et le pirate peut ensuite utiliser ce même ID pour se connecter à la session de la victime.

Pour se protéger contre ce type d'attaque, il faut ajouter la ligne suivante avant de faire le `session_start`:

```php
ini_set('session.use_strict_mode', 1);
```

Il faut aussi regénérer l'ID de session à la connexion, de cette manière le pirate ne peut pas prédire l'ID de session.

## Génération d'ID de session

PHP permet de personnaliser la génération de l'ID de session, mais il est préférable d'utiliser la méthode par défaut qui est sécuritaire.

Références:
 * https://www.hacksplaining.com/app/lessons/session-fixation/prevention
 * https://www.hacksplaining.com/app/lessons/weak-session/prevention