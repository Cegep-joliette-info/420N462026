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
## Révision complète

Chapitres 1 à 11

---

## Plan de révision

| # | Sujet |
|---|-------|
| 1 | Manquement dans l'ACL |
| 2 | Mauvaise configuration |
| 3 | Chaîne d'approvisionnement |
| 4 | Défaillances cryptographiques |
| 5 | Injection |
| 6 | Conception non sécurisée |
| 7 | Échecs d'authentification |
| 8 | Défaillances d'intégrité |
| 9 | Journalisation |
| 10 | Mauvaise gestion des exceptions |

---

<!-- _class: chapter -->

# Chapitre 1
## Manquement dans l'ACL
*Broken Access Control*

---

## ACL — Les trois niveaux

Une ACL défaillante vérifie seulement la connexion, pas la propriété.

```php
// ACL défaillante : vérifie seulement si connecté
public function isConnected(): bool {
    return isset($_SESSION['user']);
}

// ACL correcte : vérifie aussi que la ressource appartient à l'utilisateur
public function isMyPost(int $postId): bool {
    $post = $this->postModel->find($postId);
    return $post && $post->userId === $_SESSION['user']['id'];
}
```

| Niveau | Question |
|--------|----------|
| **Authentification** | Est-il connecté ? |
| **Autorisation** | A-t-il le rôle requis ? |
| **Vérification** | Cette ressource lui appartient-elle ? |

> **Refus par défaut** — si aucune règle ne correspond, l'accès est refusé.

---

## Traversée de dossiers
*Directory Traversal*

Un paramètre GET peut contenir `../../etc/passwd` pour lire n'importe quel fichier.

```php
// Liste d'acceptation : seuls les fichiers du dossier sont permis
$dossier = realpath('/var/www/telechargements');
$cheminResolu = realpath($dossier . '/' . $_GET['fichier']);

if (!str_starts_with($cheminResolu, $dossier . '/')) {
    http_response_code(403); exit;
}
readfile($cheminResolu);
```

- `realpath()` résout les `../` et retourne le chemin absolu réel
- `basename()` retire le chemin mais ne vérifie pas le dossier (moins fiable)
- Alternative : stocker les fichiers permis en BD, l'utilisateur envoie un **ID**

---

## CSRF — Cross-Site Request Forgery

Un pirate envoie un lien ou formulaire qui exécute une action au nom de l'utilisateur connecté.

**Protection : jeton CSRF dans chaque formulaire POST**

1. Générer un jeton → sauvegarder en session → mettre dans un champ `hidden`
2. À la réception : comparer le jeton reçu avec celui en session

```php
// Génération (méthode 3 — par formulaire, multi-onglets OK)
$jeton = hash_hmac('sha256', $formulaireId, $_SESSION['secret']);
```

**Protections complémentaires :**
- Cookie `SameSite=Strict` — bloque les requêtes cross-site
- Vérifier l'en-tête `Origin` ou `Referer`

> Tous les formulaires POST et le bouton de déconnexion doivent être protégés.

---

## SSRF — Server Side Request Forgery

Le serveur fait une requête HTTP vers une URL fournie par l'utilisateur.

**Risques :** accès aux services internes, métadonnées cloud AWS (`169.254.169.254`), fichiers via `file://`, utilisation du serveur pour un DDoS.

```php
function estUrlSure(string $url): bool {
    $infos = parse_url($url);
    if (!in_array($infos['scheme'] ?? '', ['http', 'https'])) return false;
    $ip = gethostbyname($infos['host'] ?? '');
    return filter_var($ip, FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
}
```

- Liste d'autorisation de domaines
- Ne jamais faire d'appel HTTP pour les utilisateurs anonymes

---

## Redirection ouverte

```php
// Dangereux — redirige vers n'importe quel site
header('location: ' . $_GET['returnurl']);
```

Un pirate envoie un lien vers votre site qui redirige vers un site de phishing.

```php
// Option 1 — garder le returnurl dans la session
$_SESSION['returnurl'] = $_GET['returnurl'] ?? '/';

// Option 2 — valider que l'URL est relative
if (!empty(parse_url($returnurl, PHP_URL_HOST))) {
    $returnurl = '/'; // URL absolue → refus
}
```

---

<!-- _class: chapter -->

# Chapitre 2
## Mauvaise configuration
*Security Misconfiguration*

---

## Paramètres de sécurité laxistes

<div class="columns">

**Vecteurs courants**
- Comptes par défaut (`admin`/`admin`)
- Interfaces d'admin exposées
- Clés API commitées dans Git
- Environnements de staging non sécurisés

**Protections**
- Automatiser le déploiement
- Stocker les secrets hors du dépôt (`.env`)
- Identifiants différents par environnement
- Changer les mots de passe par défaut
- 2FA sur les comptes d'admin
- Supprimer les fonctionnalités inutiles

</div>

---

## XXE — XML External Entity

Un parseur XML peut résoudre des entités externes → lecture de fichiers, SSRF.

```xml
<?xml version="1.0"?>
<!DOCTYPE foo [
  <!ENTITY secret SYSTEM "file:///etc/passwd">
]>
<root>&secret;</root>
```

Le serveur remplace `&secret;` par le contenu de `/etc/passwd`.

**Protection :**
- **PHP 8.0+** : protégé par défaut
- **PHP < 8.0** : `libxml_set_external_entity_loader(null)`
- Préférer **JSON** quand XML n'est pas requis
- Attention : les fichiers **SVG** et **DOCX** contiennent du XML

---

## Bombe XML — *Billion Laughs*

Un fichier de 1 Ko peut générer **3 Go en mémoire** via des entités imbriquées.

```xml
<!DOCTYPE lolz [
  <!ENTITY lol "lol">
  <!ENTITY lol2 "&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;&lol;">
  <!ENTITY lol9 "&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;&lol8;">
]>
<lolz>&lol9;</lolz>
```

9 niveaux × 10 références = **10⁹ expansions**

**Même protection que XXE** — désactiver les DTD dans le parseur XML.

| Attaque | Mécanisme | But |
|---------|-----------|-----|
| XXE | Entité externe | Vol de données |
| Bombe XML | Entités imbriquées | Déni de service |

---

<!-- _class: chapter -->

# Chapitre 3
## Chaîne d'approvisionnement
*Software Supply Chain*

---

## Dépendances toxiques

Une librairie peut introduire une faille dans votre application (Log4J, Heartbleed).

**Attaque par confusion de dépendances :** un pirate publie un paquet public avec le même nom qu'une dépendance privée de votre organisation.

<div class="columns">

**Risques**
- Faille dans une librairie tierce
- Paquet malicieux sur npm/Packagist
- Mises à jour automatiques surprises

**Protections**
- `npm audit` régulièrement
- Épingler les versions exactes
- Prioriser les registres privés
- Scanner le CI/CD automatiquement
- Surveiller les bulletins de sécurité

</div>

---

<!-- _class: chapter -->

# Chapitre 4
## Défaillances cryptographiques
*Cryptographic Failures*

---

## SSL/HTTPS et SSL Stripping

Sans HTTPS, le trafic circule en clair → attaque **man-in-the-middle**.

```apacheconf
# Forcer HTTPS en .htaccess
RewriteEngine On
RewriteCond %{SERVER_PORT} 80
RewriteRule ^(.*)$ https://monsite.com/$1 [R,L]

# HSTS — le navigateur n'essaie même plus HTTP
Header always set Strict-Transport-Security "max-age=31536000"
```

**SSL Stripping :** le pirate intercepte la requête HTTP initiale *avant* la redirection → l'utilisateur reste en HTTP sans s'en rendre compte.

**HSTS** protège contre ça : le navigateur utilise toujours HTTPS, même si l'utilisateur tape `http://`.

---

## Attaque par déclassement
*Downgrade Attack*

Un pirate force une connexion à utiliser une vieille version vulnérable de TLS.

| Attaque | Protocole forcé | Faille |
|---------|----------------|--------|
| **POODLE** | SSL 3.0 | Décryptage du trafic |
| **BEAST** | TLS 1.0 | Exploite les failles de chiffrement |

**Protection :**
- N'autoriser que **TLS 1.2 et 1.3** (désactiver SSLv3, TLS 1.0, 1.1)
- Activer `TLS_FALLBACK_SCSV`

> En pratique, **cPanel gère déjà cette configuration** sur nos serveurs.

---

<!-- _class: chapter -->

# Chapitre 5
## Injection

*SQL · Commande · Regex · XSS*

---

## Injection SQL

```php
// Vulnérable
$pdo->query("SELECT * FROM users WHERE email = '" . $_POST['email'] . "'");
// Payload : ' OR 1=1 --

// Sécuritaire — requête préparée PDO
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $_POST['email']]);
```

**Principe du moindre privilège :** créer un utilisateur BD avec seulement les droits SELECT, INSERT, UPDATE, DELETE — jamais DROP.

> Si la protection échoue, les dégâts sont limités.

---

## Injection de commande

Éviter `shell_exec`, `exec`, `passthru`, `system`, `popen`.

```php
// Vulnérable
echo shell_exec("ping -c 1 " . $_GET['ip']);
// Payload : 8.8.8.8; rm -rf /var/www

// Protection 1 — liste d'acceptation
$permis = ['serveur1', 'serveur2'];
if (!in_array($_GET['serveur'], $permis)) exit;

// Protection 2 — si liste impossible
$ip = escapeshellarg($_GET['ip']);
echo shell_exec("ping -c 1 " . $ip);
```

> L'utilisateur Apache a accès aux fichiers journaux → les credentials de BD peuvent être exposés.

---

## Injection de Regex

```php
// Vulnérable — l'utilisateur contrôle la regex
preg_grep('/' . $_GET['recherche'] . '/', $listeFichiers);

// Sécuritaire — regex définie dans le code
preg_match('/^[a-zA-Z0-9 ]+$/', $_GET['recherche']);
```

**Backtracking catastrophique :** une entrée malformée sur une regex mal conçue peut paralyser le serveur (ex: `(a+)+b` sur `"aaaaaac"`).

**Patrons dangereux à éviter :**
- Quantificateurs imbriqués : `(a+)+`
- Disjonctions qui se chevauchent : `(a|a)+`
- Adjacences : `\d+\d+`

---

## XSS — Cross-Site Scripting

Injection de HTML/JS dans la page → vol de cookies, actions au nom de l'utilisateur.

<div class="columns">

**Types**
- **Réfléchi** (GET) — lien corrompu
- **Permanent** (BD) — visible par tous
- **DOM** — via `#` ou `?` en JS
- **Self-XSS** — social engineering

**Protection (les deux)**
- `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`
- Header CSP : `default-src 'self'`

</div>

```php
header("Content-Security-Policy: default-src 'self';");
```

> En JS, préférer `innerText` à `innerHTML` pour les données non fiables.

---

<!-- _class: chapter -->

# Chapitre 6
## Conception non sécurisée
*Insecure Design*

---

## Classification des données

| Niveau | Accès | Exemple |
|--------|-------|---------|
| **Publiques** | Visiteurs | Produits d'une boutique |
| **Privées** | Connectés | Profil utilisateur |
| **Restreintes** | Admins | Rapport des ventes |
| **Haut risque** | Personne | Hash de mot de passe, ID de session |

**Principes clés :** moindre privilège · valider toutes les entrées GET/POST · séparer dev et production · échec sécuritaire · SSL · observabilité (logs)

> Garder la sécurité simple : un protocole trop contraignant sera ignoré.

---

## Fuite d'information

```php
header_remove('x-powered-by');          // Masquer la version PHP
session_name("MonCookieDeSession");      // Changer le nom du cookie de session

ini_set('display_errors', 0);           // Désactiver les erreurs en production
```

```apacheconf
deny from all           # Dans /app/.htaccess — cache l'architecture
ErrorDocument 404 /404.html
```

**Ne jamais envoyer au client :**
- L'ID de session
- Un mot de passe (même haché)
- Des commentaires révélant l'architecture dans le HTML/JS

---

## Téléversement de fichiers

Combiner plusieurs validations :

```php
// 1. Extension (liste d'autorisation, pas de doubles extensions)
preg_match('/\.(jpg|png|gif)$/i', $_FILES['img']['name']);

// 2. Type MIME réel (lu dans le fichier — fiable)
$type = mime_content_type($_FILES['img']['tmp_name']);

// 3. Content-Type de la requête (non fiable seul — complémentaire)
$_FILES['img']['type'] === 'image/jpeg';
```

- **Permissions Unix :** `chmod 644` sur le dossier uploads (jamais `x`)
- **Renommer** le fichier (ex: utiliser l'ID de l'enregistrement)
- Réencoder les images avec **GD/ImageMagick** (contre les images infectées)
- Stocker sur un **serveur externe** (Amazon S3) pour isoler le risque

---

## Clickjacking

Un `<iframe>` invisible par-dessus une page trompeuse → clic redirigé à l'insu.

```php
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

# Chapitre 7
## Échecs d'authentification
*Authentication Failures*

---

## Gestion du mot de passe

```php
// Entreposage sécuritaire — password_hash gère sel + hachage
$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
password_verify($_POST['password'], $hash);
```

**Validation du courriel :** jeton + expiration → lien d'activation par courriel.

**Oubli de mot de passe :** même logique — jeton + expiration + nouveau mot de passe.

**Déconnexion sécuritaire :**
```php
$_SESSION = [];
session_destroy();
```

> La **longueur** du mot de passe est plus importante que sa complexité.

---

## Escalade de privilèges

Un pirate modifie son rôle dans un **cookie** → devient admin.

```php
// Mauvais — rôle dans le cookie, modifiable par le client
setcookie('role', 'admin');

// Correct — rôle dans la session, inaccessible au client
$_SESSION['role'] = 'admin';
```

Si un cookie est inévitable → **JWT maison** : payload + signature HMAC pour garantir l'intégrité.

> Stocker le minimum possible chez le client. La session est la source de vérité.

---

## Recensement des utilisateurs
*User Enumeration*

Éviter de confirmer qu'un compte existe ou non.

| Formulaire | Message à éviter | Message recommandé |
|------------|------------------|--------------------|
| Connexion | "Mot de passe incorrect" | "Nom d'utilisateur ou mot de passe invalide" |
| Inscription | "Courriel déjà utilisé" | "Un courriel d'activation a été envoyé" |
| Oubli MDP | "Courriel introuvable" | "Si le compte existe, un courriel a été envoyé" |

> Pour l'oubli de mot de passe : ajouter un **délai aléatoire** si le compte n'existe pas (contre le timing attack).

---

## Vol de session
*Session Hijacking / Fixation*

```php
// Avant session_start()
session_name("MonCookieDeSession");
ini_set('session.use_strict_mode', 1);  // Empêche les IDs forgés
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,   // HTTPS seulement
    'httponly' => true,   // Inaccessible en JavaScript
    'samesite' => 'Lax',
]);
session_start();

// À la connexion et toutes les X minutes
session_regenerate_id(true);  // Invalide l'ancien ID
```

> Si un pirate vole l'ID de session, `session_regenerate_id` l'invalide périodiquement.

---

<!-- _class: chapter -->

# Chapitre 8
## Défaillances d'intégrité
*Software and Data Integrity Failures*

---

## Pollution de prototype

En JavaScript, modifier `__proto__` contamine **tous** les objets créés ensuite.

```javascript
// Payload malicieux
let payload = JSON.parse('{ "__proto__.injected" : "code malicieux" }');
processNested(payload);
console.log(injected);        // "code malicieux"
console.log(Object().injected); // "code malicieux"
```

```javascript
Object.freeze(obj);             // Rendre un objet immuable
const safe = Object.create(null); // Objet sans prototype
const map = new Map();          // Map — pas vulnérable
map.set('role', 'user');
```

- Ne jamais écraser des propriétés commençant par `_`
- `npm audit` pour détecter les dépendances vulnérables

---

## Assignation massive
*Mass Assignment*

Des frameworks assignent automatiquement les paramètres HTTP vers un objet.

```php
// Dangereux : un pirate ajoute role=admin dans la requête
$user->fill($_POST);

// Sécuritaire : énumérer explicitement les champs autorisés
$user->nom     = $_POST['nom'];
$user->courriel = $_POST['courriel'];
// Le champ "role" est ignoré même s'il est présent
```

> Toujours définir explicitement les champs autorisés côté serveur — ne jamais passer `$_POST` directement à un objet ou une requête.

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
- Clés API / chiffrement
- ID de session / cookies
- Informations personnelles
- Numéros de carte

</div>

---

## Journalisation — Surveillance

```php
file_put_contents('logs/app.log',
    date("Y-m-d H:i:s") . " [ERROR] " . $message . PHP_EOL,
    FILE_APPEND
);
```

**Outils d'entreprise :** LogStash · Graylog · Splunk

**Uptime :** [Uptime Robot](https://uptimerobot.com/) — vérifie toutes les 5 min, alerte par courriel.

> Les journaux doivent être **inaccessibles** depuis le navigateur.

---

<!-- _class: chapter -->

# Chapitre 10
## Mauvaise gestion des exceptions
*Exception Handling*

---

## Ne pas révéler les erreurs

```php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
```

```apacheconf
ErrorDocument 404 /404.html
ErrorDocument 500 /500.html
```

| Action | En développement | En production |
|--------|-----------------|---------------|
| Afficher l'erreur | Oui | **Non** |
| Journaliser l'erreur | Oui | **Oui** |
| Page d'erreur custom | Non nécessaire | **Oui** |
| Détails techniques | Oui | **Non** |

> Chaque message d'erreur affiché est une **fuite d'information** potentielle.

---

<!-- _class: chapter -->

# Chapitre 11
## Autres vulnérabilités

---

## Autres vulnérabilités — Survol

| Attaque | Mécanisme | Protection |
|---------|-----------|------------|
| **DoS/DDoS** | Saturer les ressources | CDN, cache, scalabilité |
| **Usurpation courriel** | Fausse adresse expéditeur | SPF + DKIM + DMARC |
| **Publicité malveillante** | Code malicieux via réseaux pub | CSP, réseaux réputés |
| **Débordement de tampon** | Écriture hors des limites mémoire | Garder PHP à jour |
| **Host Header Poisoning** | Fausse en-tête Host → lien malicieux | Domaine en config, jamais depuis `$_SERVER['HOST']` |
| **RCE** | Exécuter du code via `eval`/`exec` | Liste d'autorisation stricte |
| **XSSI** | Importer votre JS depuis un autre site | Jamais de données sensibles dans les fichiers JS |
| **Empoisonnement DNS** | Fausses adresses IP | HTTPS + DNSSEC |
| **Squattage de sous-domaine** | CNAME vers ressource supprimée | Supprimer l'entrée DNS avant de déprovisionner |

---

## Utiliser l'IA de façon sécuritaire

<div class="columns">

**Ne jamais soumettre à une IA**
- Mots de passe / clés API
- Chaînes de connexion BD
- Informations personnelles de clients
- Code sous NDA / propriété intellectuelle

**Risques à connaître**
- Code fonctionnel mais non sécuritaire (SQL, XSS…)
- Hallucinations : fonctions inexistantes, URLs fausses
- Extensions IDE : accès à tout le projet, y compris `.env`
- Droits d'auteur flous en contexte professionnel

</div>

> Traiter le code généré par une IA comme du code écrit par un stagiaire : **il doit être revu avant d'être intégré**.

---

<!-- _class: title -->

# Résumé général

| # | Sujet | Protection clé |
|---|-------|----------------|
| 1 | ACL | 3 niveaux · refus par défaut · `realpath()` · CSRF token · URL relative |
| 2 | Config | Secrets hors dépôt · désactiver DTD XML · PHP 8+ |
| 3 | Dépendances | `npm audit` · épingler les versions |
| 4 | Crypto | HTTPS + HSTS · TLS 1.2/1.3 uniquement |
| 5 | Injection | PDO préparé · liste d'acceptation · `htmlspecialchars` + CSP |
| 6 | Conception | Classifier les données · valider MIME + ext · `X-Frame-Options` + CSP |
| 7 | Auth | `password_hash` · session (pas cookie) · message générique · `session_regenerate_id` |
| 8 | Intégrité | `Object.freeze` · `Map` · champs explicites |
| 9 | Logs | Logger les événements, jamais les secrets · fichier hors accès web |
| 10 | Exceptions | Journaliser, ne jamais afficher en production |
