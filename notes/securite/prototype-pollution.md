# Pollution de prototype

En anglais: _Prototype Pollution_

JavaScript est unique parmi les langages de programmation courants en ce qu'il utilise un héritage basé sur les prototypes.
Plutôt que d'être instanciés à partir de classes, la plupart des objets sont des tableaux associatifs qui héritent des propriétés d'un objet existant : le prototype.
Chaque objet possède une référence vers son prototype via la propriété `__proto__`.

Si un attaquant parvient à modifier l'objet prototype, il peut potentiellement injecter du code dans tous les objets créés de la même façon en mémoire.

## Risques

Si l'attaquant remplace une fonction couramment appelée sur les objets, il peut exécuter le code de son choix dans cet environnement.
Cela permet des attaques XSS dans le navigateur, ou de l'exécution de code à distance dans les applications Node.js.

## Comment ça fonctionne

Voici un exemple tiré d'une ancienne version du module `express-fileupload` pour Node.js :

```javascript
function processNested(data) {
  if (!data || data.length < 1) return {};

  let d = {},
    keys = Object.keys(data);

  for (let i = 0; i < keys.length; i++) {
    let key = keys[i],
      value = data[key],
      current = d,
      keyParts = key.replace(new RegExp(/\[/g), '.').replace(new RegExp(/\]/g), '').split('.');

    for (let index = 0; index < keyParts.length; index++) {
      let k = keyParts[index];
      if (index >= keyParts.length - 1) {
        current[k] = value;
      } else {
        if (!current[k]) current[k] = !isNaN(keyParts[index + 1]) ? [] : {};
        current = current[k];
      }
    }
  }

  return d;
}
```

Cette fonction est conçue pour "déplier" un objet plat comme :

```json
{ "a.b.c": "valeur" }
```

...en un objet imbriqué :

```json
{ "a": { "b": { "c": "valeur" } } }
```

Le problème : le code est beaucoup trop permissif — il permet de modifier l'objet `__proto__` directement :

```javascript
let payload = JSON.parse('{ "__proto__.injected" : "Cette variable existe sur tous les objets" }');

processNested(payload);

// Affiche "Cette variable existe sur tous les objets",
// car on a injecté une propriété dans l'espace global.
console.log(injected);

// Affiche aussi "Cette variable existe sur tous les objets",
// car tous les nouveaux objets héritent de cette propriété.
console.log(Object().injected);
```

## Protections

### Geler vos objets

La méthode `freeze()` rend un objet immuable, ce qui empêche aussi la modification de son prototype :

```javascript
const obj = { prop: 42 };

Object.freeze(obj);

// Lance une erreur, l'objet est gelé.
obj.prop = 33;
```

### Créer des objets sans prototype

Les objets créés avec `Object.create(null)` n'ont pas de `__proto__` ni de `constructor`.
Leur prototype ne peut donc jamais être pollué.

### Utiliser des Map plutôt que des objets

La structure `Map` (introduite en ES6) stocke des paires clé/valeur et n'est pas vulnérable à la pollution de prototype :

```javascript
const map = new Map();
map.set('a', 1);
map.set('b', 2);

console.log(map.get('a')); // 1
```

### Valider explicitement les propriétés

Lors du traitement de données provenant d'un utilisateur, énumérez explicitement les propriétés que vous autorisez.
Ne jamais écraser des propriétés internes qui commencent par `_`.

## Dépendances tierces

Les vulnérabilités de pollution de prototype se trouvent souvent dans des bibliothèques tierces.
Si vous développez en Node.js, consultez régulièrement les avis de sécurité et exécutez la commande suivante :

```bash
npm audit
```

> Ce document a été rédigé avec l'aide de l'intelligence artificielle.

Référence: https://www.hacksplaining.com/app/lessons/prototype-pollution/prevention
