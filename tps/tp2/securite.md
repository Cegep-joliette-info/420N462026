# Sécurité — TP2

Liste des éléments de sécurité à implémenter dans votre projet final.

## 1. [Manquement dans l'ACL](../../notes/securite/acl.md)

- Implémenter un ACL à trois niveaux : authentification (est-il connecté ?), autorisation (a-t-il le rôle requis ?) et vérification des permissions (peut-il agir sur *cette* ressource précise ?)
- Appliquer le principe de refus par défaut : si aucune règle ne correspond, l'accès est refusé

### [Traversée de dossiers](../../notes/securite/directory.md)

- Si vous servez des fichiers à partir d'un paramètre GET, utiliser une liste d'acceptation ou valider le chemin avec `realpath()`

### [CSRF](../../notes/securite/csrf.md)

- Protéger **tous** les formulaires POST (y compris le bouton de déconnexion) avec un jeton CSRF
- Utiliser la **méthode 3** : hacher la concaténation d'une chaîne aléatoire générée à la connexion avec un identifiant unique de formulaire

### [Redirection ouverte](../../notes/securite/redirect.md)

- Si vous utilisez un paramètre `returnurl`, conserver sa valeur en session ou valider qu'il s'agit d'une URL relative (jamais absolue)

---

## 4. Défaillances cryptographiques — [Certificat SSL](../../notes/securite/ssl.md)

- Forcer le site en **HTTPS** sur le CPanel
- Configurer le cookie de session pour n'être envoyé qu'en HTTPS (voir chapitre 7)

---

## 5. Injection

### [Injection SQL](../../notes/securite/sql.md)

- Utiliser les **requêtes préparées PDO** pour toutes les requêtes SQL (aucune concaténation de variables dans les requêtes)
- Appliquer le principe du moindre privilège sur l'utilisateur de la base de données (accès SELECT, INSERT, UPDATE, DELETE seulement, sur votre BD uniquement)

### [Injection de commande](../../notes/securite/command.md)

- Ne jamais passer de données non fiables aux fonctions `exec()`, `system()`, `shell_exec()`, etc.

### [XSS](../../notes/securite/xss.md)

- Utiliser `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`, `strip_tags()` ou `intval()` sur **toutes** les données non fiables avant de les afficher
- Ajouter l'en-tête **Content Security Policy** dans le routeur :
  ```php
  header("Content-Security-Policy: default-src 'self';");
  ```
- En JavaScript, utiliser `innerText` (jamais `innerHTML`) pour insérer des données provenant de l'URL ou de l'utilisateur
- Ajouter un avertissement dans la console du navigateur pour décourager le Self-XSS

---

## 6. Conception non sécurisée

### [Conception fragile](../../notes/securite/design.md)

- Valider **toutes** les données GET et POST côté PHP (même si une validation HTML ou JavaScript est déjà présente)

### [Fuite d'information](../../notes/securite/leak.md)

- Retirer l'en-tête `x-powered-by` dans le routeur :
  ```php
  header_remove('x-powered-by');
  ```
- Utiliser de beaux URLs pour ne pas exposer l'architecture de fichiers, le seul fichier PHP accessible doit être le routeur (index.php)
- Changer le nom du cookie de session (appel à `session_name()` avant `session_start()`)
- Ne jamais afficher l'ID de session ni un mot de passe (haché ou non)

### [Téléversement de fichiers](../../notes/securite/upload.md)

- Valider l'**extension** du fichier (liste d'autorisation, attention aux doubles extensions)
- Valider le **type MIME réel** avec `mime_content_type()` et s'assurer qu'il concorde avec l'extension
- Valider la **taille** du fichier
- **Renommer** le fichier (ne pas conserver le nom d'origine fourni par l'utilisateur)

### [Clickjacking](../../notes/securite/clickjacking.md)

- Ajouter les en-têtes suivants dans le routeur :
  ```php
  header("X-Frame-Options: DENY");
  header("Content-Security-Policy: frame-ancestors 'none'", false);
  ```

---

## 7. Échecs d'authentification

### [Gestion du mot de passe](../../notes/securite/password.md)

- Hacher les mots de passe avec `password_hash()` et les vérifier avec `password_verify()`
- Demander une confirmation de mot de passe à la création de compte et lors d'un changement de mot de passe
- Imposer une complexité minimale (au moins une longueur minimale)
- Envoyer un courriel de validation avec un jeton aléatoire et une expiration (ex. 1h) lors de la création de compte
- Implémenter la fonctionnalité « Mot de passe oublié » avec un jeton, une expiration et un courriel
- Implémenter un « Se souvenir de moi » avec un cookie de longue durée contenant un jeton aléatoire lié à l'utilisateur (pas de stockage de mot de passe ou d'ID dans le cookie)
- Déconnecter de façon sécuritaire :
  ```php
  $_SESSION = [];
  session_destroy();
  ```

### [Escalade de privilèges](../../notes/securite/escalation.md)

- Stocker le **rôle ou l'id de l'utilisateur dans la session** uniquement (jamais dans un cookie non signé)

### [Recensement des utilisateurs](../../notes/securite/enumeration.md)

- Formulaire de connexion : message générique « Nom d'utilisateur ou mot de passe invalide »
- Création de compte : ne pas indiquer si l'adresse courriel existe déjà ; envoyer un courriel dans les deux cas
- Mot de passe oublié : message générique « Si le compte existe, un courriel vous a été envoyé »

### [Vol de session](../../notes/securite/session_hijack.md)

- Sécuriser le cookie de session avant `session_start()` :
  ```php
  ini_set('session.use_strict_mode', 1);
  session_name("NomGenerique");
  session_set_cookie_params(0, '/', '', true, true);
  ```
- Regénérer l'ID de session à la connexion avec `session_regenerate_id(true)`
- Regénérer l'ID de session périodiquement (échéance stockée en session)

---

## 8. Défaillances d'intégrité logicielle et des données

### [Assignation massive](../../notes/securite/mass-assignement.md)

- Ne jamais passer `$_POST` directement à un objet ou une requête SQL ; énumérer explicitement les champs autorisés

### [Pollution de prototype](../../notes/securite/prototype-pollution.md)

- En JavaScript, ne jamais écraser les propriétés commençant par `__` ; valider explicitement les propriétés reçues de l'utilisateur

---

## 10. Mauvaise gestion des exceptions — [Fuite d'information](../../notes/securite/leak.md)

- Ne pas afficher les erreurs PHP en production ; utiliser des pages d'erreur personnalisées (ex. 404, 500) :
  ```apacheconf
  ErrorDocument 404 /404.html
  ErrorDocument 500 /500.html
  ```
