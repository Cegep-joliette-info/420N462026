# API

Les API en PHP reçoivent et envoie du JSON. Une bonne API est RESTFULL.

Au lieu de soumettre l'information avec un formulaire HTML, votre page Web va envoyer l'information en JavaScript avec Fetch. On a maintenant le choix de soumettre un formulaire normalement et "refresh" la page, ou rester au même endroit et envoyer l'information en JS.

## Fetch

Fetch fait des appels asynchrones. Sur Internet plusieurs personnes semblent préférer une autre solution (avec des await), mais nous allons utiliser la version plus complexe afin de bien comprendre ce qui ce passe.

Voici un appel fetch pour aller chercher du JSON sur le serveur:

```js
let list = [];
fetch('http://localhost/index.php?controller=api&action=list')
  .then(response => {
    return response.json();
  })
  .then(data => {
    list = data;
  })
  .catch(error => {
    console.log(error);
  });
console.log(list);
```

L'appel fetch est asynchrone, le code va continuer sans attendre que le fetch termine, notre dernier console.log va toujours afficher '[]' dans ce cas.

Fetch retourne une promesse (un jour je vais avoir une réponse), la fonction anonyme dans le premier .then se fait appeler lorsqu'on reçoit la réponse du serveur. Il y a 3 fonctions utiles:

 - .json() transforme le JSON reçu en objet JS
 - .text() retourne du texte brut
 - .blob() retourne le binaire reçu

Comme le traitement peut être long, ces 3 fonctions retournent aussi des promesses, il faut donc un 2e .then qui pourra manipuler l'information transformé par le premier .then.

Le catch va être appelé si un des deux .then déclanche une erreur, il faudra déterminer le type d'erreur et afficher un message d'erreur clair.

## API PHP

Pour retourner du JSON, utilisez le code suivant:

```php
header('Content-Type: application/json');
echo json_encode($data);
```

### Solution FormData

En JavaScript, dans le body envoyez un FormData (plusieurs champs, pas du JSON). En PHP vous pouvez simplement recevoir les données avec $_POST.

Le fetch (sans les .then) ressemblera à:

```js
fetch('/api', {
    method: 'POST',
    body: formData
})
```

Le formData peut se construire à partir d'un formulaire HTML ou manuellement en JS:

```js
// Solution form
let form = document.querySelector('form');
let formData = new FormData(form);

// Solution JS
let formData = new FormData();
formData.append('username', 'toto');
formData.append('password', 'bonjour123');
```

### Solution JSON

En JavaScript, utilisez `JSON.stringify` pour donner du JSON dans le body du fetch. Par contre, en PHP, c'est un peu plus complexe. La ligne suivante vous donne le JSON reçu via POST en PHP.

```php
$json = file_get_contents('php://input');
```

Note que $json contient une string.

### Solution Mixte

Une 3e solution qui est d'envoyer du JSON dans le FormData. Le FormData contiendra donc un seul champ, qui contiendra du JSON.

## API RESTful

Une API doit respecter 6 contraintes architecturales pour être RESTful:

 - Architecture client-serveur: Le frontend doit être séparé du backend. Donc votre frontend ne doit pas contenir de PHP et votre backend ne doit pas contenir de HTML/JavaScript/CSS.
 - Sans état: Il ne faut pas garder de contexte entre chaque appel serveur, vous ne pouvez pas utiliser les sessions! De cette manière c'est facile à faire du load-balancing si votre API devient plus grosse.
 - Permettre la cache: Par défaut, il doit être possible de garder en cache les requêtes GET mais pas les requêtes POST, c'est possible de changer ça avec les headers PHP.
 - Système en couches: Permettre de multiplier les serveurs (load balancing) de manière invisible. Pour ce faire, on peut diviser le système en plusieurs couches ce qui le rend plus maléable. Ce point n'est pas nécessaire dans votre TP.
 - Code sur demande: Point optionnel pour REST. Le serveur peut envoyer du code JavaScript qui sera exécuter sur le client afin d'augmenter les fonctionnalités.
 - Interface uniforme: Votre API doit être uniforme. Si vos contrôleurs sont en anglais au pluriel, ils doivent tous l'être. Si votre action "list" affiche tous les objets, tous les contrôleurs qui ont cette fonctionnalité utilisent le même terme.

Habituellement une API REST est séparé par ressources. Par exemple, pour la ressource "users", voici les URL possibles:

| Verbe HTTP | URL             | Explication                                                      |
|------------|-----------------|------------------------------------------------------------------|
| GET        | /users          | Retourne tous les users en JSON                                  |
| GET        | /users/1        | Retourne le user avec l'ID 1 en JSON                             |
| POST       | /users          | Crée un user, les informations sont envoyés en JSON              |
| POST       | /users/1        | Modifie le user avec l'ID 1, les informations en envoyés en JSON |
| POST       | /users/delete/1 | Supprimer le user avec l'ID 1                                    |

Vous pouvez aussi utiliser les paramètres GET, comme avec le dispatch MVC:
/index.php?controler=users_api&action=list

Certains systèmes, comme Laravel, vont aussi utiliser les verbes HTTP PUT et DELETE. Par contre PHP ne les supportes pas. Laravel passe le verbe HTTP en string dans le POST. Voir le tableau de contrôleur de Laravel [ici](https://laravel.com/docs/10.x/controllers#actions-handled-by-resource-controller). Notez que Laravel utilise une action pour afficher la page de création (create) et une action pour faire le insert (store).

## Codes HTTP

Par défaut PHP va envoyer un code 200 (OK, requête réussie). Si votre réponse d'API n'est pas un succès, il faut spécifier le bon code. Le plus connu était 404 (page non trouvée), voir la liste ici: https://developer.mozilla.org/fr/docs/Web/HTTP/Status. Pour spécifier le code http, utilisez la fonction PHP `http_response_code($codeHttpIci)`. Ensuite, dans votre première Promesse suite à un fetch il faut vérifier si le code est OK:

```js
fetch('/api').then(response => {
  if (response.ok) {
    return response.json();
  }
  else {
    // Gérer l'erreur
  }
});
```

Tout code entre 200 et 299 inclusivement est considéré comme OK.

## Cache

Pour dire explicitement si le client doit utiliser la cache, il faut utiliser la fonction header de PHP:

```php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
```

Exemple précédent provient de StackOverflow: https://stackoverflow.com/a/4485194.

Notez que la fonction header de PHP doit être appelé avant que la page affiche quelque chose (echo, var_dump, erreur, etc.) sinon ça crée des problèmes...