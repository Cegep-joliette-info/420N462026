---
marp: true
theme: default
paginate: true
style: |
  section {
    font-size: 1.4rem;
    background: #1a1a2e;
    color: #e0e0e0;
    position: relative;
  }
  section h1 {
    color: #e74c3c;
  }
  section h2 {
    color: #7fb3d3;
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 0.2em;
  }
  .columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  code {
    font-size: 0.85rem;
    background: #0d0d1a;
    color: #a8d8a8;
  }
  pre {
    background: #0d0d1a;
    border-left: 3px solid #e74c3c;
  }
  pre code {
    background: transparent;
  }
  table {
    width: 100%;
  }
  thead tr {
    background: #16213e;
    color: #7fb3d3;
  }
  tbody tr:nth-child(odd) {
    background: #16213e;
  }
  tbody tr:nth-child(even) {
    background: #1a1a2e;
  }
  blockquote {
    border-left: 4px solid #e74c3c;
    background: #16213e;
    padding: 0.5em 1em;
    color: #bdc3c7;
  }
  strong {
    color: #f39c12;
  }
  section::after {
    color: #555;
  }
  section.title {
    background-color: #0d0d1a;
    background-image: url('../images/autres/ia-gia.png');
    background-repeat: no-repeat;
    background-position: bottom 30px right 30px;
    background-size: 120px;
    color: white;
  }
  section.title h1 {
    color: #e74c3c;
    font-size: 2.2rem;
  }
  section.title h2 {
    color: #7fb3d3;
    border-bottom: 2px solid #e74c3c;
  }
  section.chapter {
    background: #0d0d1a;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }
  section.chapter h1 {
    color: #e74c3c !important;
    font-size: 2rem;
  }
  section.chapter h2 {
    color: #7fb3d3 !important;
    border-bottom: 2px solid #e74c3c;
  }
  section.chapter p {
    color: #bdc3c7;
    font-size: 1.1rem;
  }
  ul li {
    margin-bottom: 0.3em;
  }
---

<!-- _class: title -->

# Sécurité Web
## Cours du 15 avril

Chapitres 6, 8, 9 et 10


---

## Plan du cours

| # | Sujet |
|---|-------|
| 6 | Conception non sécurisée |
| 8 | Défaillances d'intégrité logicielle et des données |
| 9 | Défaillances de journalisation et d'alerte |
| 10 | Mauvaise gestion des exceptions |

---

<!-- _class: chapter -->

# Chapitre 6
## Conception non sécurisée
*Insecure Design*

---

## Conception fragile — Classification des données

Classer les données selon leur sensibilité guide les priorités de sécurité.

| Niveau | Accès | Exemple |
|--------|-------|---------|
| **Publiques** | Visiteurs | Produits d'une boutique |
| **Privées** | Utilisateurs connectés | Profil utilisateur |
| **Restreintes** | Admins seulement | Rapport des ventes |
| **Haut risque** | Personne | Hash de mot de passe, ID de session |

---

## Conception fragile — Principes de sécurité

<div class="columns">

**Dans le cycle de vie**
- Contrôle de version (Git)
- Intégration continue (CI/CD)
- Révision de code
- Déploiement automatisé

**Principes clés**
- Moindre privilège
- Valider toutes les entrées (GET/POST)
- Séparer dev et production
- Échec sécuritaire (catch toutes les erreurs)
- Encryption SSL
- Observabilité (logs)

</div>

> Garder la sécurité simple : un protocole trop contraignant sera ignoré.

---

## Fuite d'information — Masquer l'architecture

Ne pas révéler la technologie utilisée ni la structure du serveur.

```php
// Supprimer l'entête PHP
header_remove('x-powered-by');

// Changer le nom du cookie de session (avant session_start)
session_name("MonCookieDeSession");
```

- **Beaux URLs** → cacher les fichiers PHP et le framework
- Ajouter `.htaccess` avec `deny from all` dans le dossier `/app`
- Désactiver l'affichage des erreurs en production

---

## Fuite d'information — Protéger les données et le code

**Ne jamais envoyer au client :**
- L'ID de session
- Un mot de passe (même haché)
- Des commentaires révélant l'architecture dans le HTML/JS

**Bonnes pratiques :**
- Compresser et minifier CSS/JS en production (dissimule le code)
- Utiliser des commentaires PHP (pas HTML) pour les notes sensibles
- S'assurer que les fichiers de config ne sont **pas accessibles** via HTTP

---

## Téléversement de fichiers — Validations

Combiner plusieurs validations pour plus d'efficacité.

```php
// 1. Extension (liste d'autorisation, éviter les doubles extensions)
preg_match('/\.(jpg|png|gif)$/i', $_FILES['img']['name']);

// 2. Type MIME réel (fiable, car lu dans le fichier)
$type = mime_content_type($_FILES['img']['tmp_name']);

// 3. Content-Type de la requête (non fiable seul, complémentaire)
$_FILES['img']['type'] === 'image/jpeg';
```

- Vérifier la **taille** (éviter de remplir le disque)
- Éviter les archives compressées (risque de *zip bomb*)

---

## Téléversement de fichiers — Sécurité avancée

**Permissions Unix**
```bash
chmod 644 uploads/   # lecture + écriture, jamais exécution
```

**Renommer les fichiers**
```php
// Utiliser l'ID de l'enregistrement comme nom
$filename = $userId . '.' . $extension;
```

**Protections supplémentaires**
- Stocker les fichiers sur un serveur externe (ex: Amazon S3)
- Réencoder les images avec GD ou ImageMagick (contre les images infectées)
- Séparer les images dans une table distincte en BD (performance)

---

## Clickjacking — Concept et risques

Un attaquant insère votre site dans un `<iframe>` invisible par-dessus une page trompeuse.

**Risques :**
- Vol de credentials (faux formulaire de connexion)
- Activation de webcam / microphone à l'insu de l'utilisateur
- Propagation de malware via des clics redirigés
- Arnaques en ligne, vers sur les réseaux sociaux

---

## Clickjacking — Protection

```php
// Dans votre routeur
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);
```

| Valeur | X-Frame-Options | CSP frame-ancestors |
|--------|----------------|---------------------|
| Refus total | `DENY` | `'none'` |
| Même domaine | `SAMEORIGIN` | `'self'` |
| Domaine précis | `ALLOW-FROM uri` | `url` |

> `X-Frame-Options` est conservé pour la compatibilité avec les vieux navigateurs. Le CSP est le standard moderne.

---

<!-- _class: chapter -->

# Chapitre 8
## Défaillances d'intégrité logicielle
*Software and Data Integrity Failures*

---

## Pollution de prototype — Concept

En JavaScript, chaque objet hérite d'un **prototype** via `__proto__`.  
Si un attaquant modifie ce prototype, il contamine **tous** les objets créés ensuite.

```javascript
// Payload malicieux
let payload = JSON.parse('{ "__proto__.injected" : "code malicieux" }');
processNested(payload);

console.log(injected);        // "code malicieux"
console.log(Object().injected); // "code malicieux"
```

**Risques :** XSS dans le navigateur, exécution de code à distance dans Node.js.

---

## Pollution de prototype — Protections

```javascript
// 1. Geler les objets sensibles
Object.freeze(obj);

// 2. Créer des objets sans prototype
const safe = Object.create(null);

// 3. Utiliser Map au lieu d'objets
const map = new Map();
map.set('role', 'user');
```

- Valider explicitement les propriétés reçues
- Ne jamais écraser des propriétés commençant par `_`
- Auditer les dépendances Node.js régulièrement : `npm audit`

---

## Assignation massive — Concept et protection

Des frameworks assignent automatiquement les paramètres HTTP vers un objet.  
Sans restriction, un attaquant peut modifier des champs non prévus.

```php
// DANGEREUX : toutes les colonnes peuvent être modifiées
$user->fill($_POST);
// Un attaquant ajoute role=admin dans la requête → escalade de privilèges
```

```php
// SECURITAIRE : énumérer explicitement les champs autorisés
$user->nom     = $_POST['nom'];
$user->courriel = $_POST['courriel'];
// Le champ "role" est ignoré même s'il est présent dans la requête
```

---

<!-- _class: chapter -->

# Chapitre 9
## Défaillances de journalisation
*Logging and Monitoring Failures*

---

## Journalisation — Quoi journaliser

Écrire dans un fichier **inaccessible au public** avec horodatage, message, fichier et numéro de ligne.

<div class="columns">

**À journaliser**
- Succès/échecs d'authentification
- Accès refusés (autorisation)
- Erreurs applicatives
- Actions utilisateur (inscription, suppression)
- Actions administratives

**À ne JAMAIS journaliser**
- Mots de passe
- Clés API / clés de chiffrement
- ID de session / cookies
- Informations personnelles
- Numéros de carte de paiement

</div>

---

## Journalisation — Format et surveillance

**Contenu d'une entrée de journal :**
- Horodatage
- Message
- Fichier et numéro de ligne
- (optionnel) URL, IP, nom d'utilisateur, trace de la pile

**Surveillance et alertes :**
- Configurer des alertes par courriel/messagerie sur les erreurs critiques
- Outils d'agrégation en entreprise : LogStash, Graylog, Splunk
- Vérification de disponibilité : [Uptime Robot](https://uptimerobot.com/) (toutes les 5 min)

> Les journaux doivent être **inaccessibles** depuis le navigateur.

---

<!-- _class: chapter -->

# Chapitre 10
## Mauvaise gestion des exceptions
*Security Logging and Monitoring Failures*

---

## Mauvaise gestion des exceptions — Ne pas révéler les erreurs

Afficher une erreur PHP ou SQL donne des indices précieux à un attaquant.

```php
// Désactiver l'affichage des erreurs en production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
```

```apacheconf
# Pages d'erreur personnalisées (.htaccess)
ErrorDocument 404 /404.html
ErrorDocument 500 /500.html
```

- Rediriger vers une action MVC (contrôleur `Error` ou `Home`)
- Journaliser l'erreur côté serveur, mais **ne jamais l'afficher** à l'utilisateur

---

## Mauvaise gestion des exceptions — Synthèse

L'exception bien gérée respecte le principe **d'échec sécuritaire** :

| Action | En développement | En production |
|--------|-----------------|---------------|
| Afficher l'erreur | Oui | **Non** |
| Journaliser l'erreur | Oui | **Oui** |
| Page d'erreur custom | Non nécessaire | **Oui** |
| Détails techniques | Oui | **Non** |

> Chaque message d'erreur affiché à l'utilisateur est une **fuite d'information** potentielle.

---

<!-- _class: title -->

# Résumé

| # | Sujet | Mécanisme clé |
|---|-------|---------------|
| 6 | Conception non sécurisée | Classifier les données, valider les entrées |
| 6 | Fuite d'information | Masquer entêtes, erreurs, architecture |
| 6 | Téléversement | Valider extension + MIME + taille + permissions |
| 6 | Clickjacking | `X-Frame-Options` + CSP `frame-ancestors` |
| 8 | Pollution de prototype | `freeze()`, `Object.create(null)`, `Map` |
| 8 | Assignation massive | Énumérer explicitement les champs autorisés |
| 9 | Journalisation | Logger les événements, jamais les secrets |
| 10 | Exceptions | Journaliser, ne jamais afficher en production |
