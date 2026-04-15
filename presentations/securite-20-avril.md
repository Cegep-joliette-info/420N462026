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
## Cours du 20 avril

Autres menaces et sécurité avec l'IA

---

## Plan du cours

| Sujet | Thème |
|-------|-------|
| Déni de service | DoS / DDoS |
| Usurpation de courriel | SPF, DKIM, DMARC |
| Publicité malveillante | Malvertising |
| Débordement de tampon | Buffer Overflow |
| Empoisonnement de l'entête Host | Host Header Poisoning |
| Exécution de code à distance | RCE |
| Inclusion de script intersites | XSSI |
| Empoisonnement DNS | DNS Poisoning |
| Squattage de sous-domaine | Subdomain Squatting |
| Utiliser l'IA de façon sécuritaire | |

---

<!-- _class: chapter -->

# Autres menaces
## Non classées par l'OWASP

---

## Déni de service
### *Denial of Service — DoS / DDoS*

Un attaquant génère suffisamment de trafic pour **épuiser les ressources du serveur**.
Les attaques distribuées (DDoS) utilisent plusieurs adresses IP simultanément, rendant le filtrage très difficile.

**Protections :**
- Servir les images et ressources statiques via un **CDN**
- Mettre en **cache** les ressources fréquemment accédées
- Configurer `Cache-Control` sur les ressources peu changeantes
- Exécuter les tâches longues dans une **file asynchrone**
- Automatiser le déploiement pour pouvoir **scaler rapidement**
- Implémenter des outils d'**analytique** pour détecter les pics de trafic

---

## Usurpation de courriel
### *Email Spoofing*

Envoyer des messages avec une **fausse adresse d'expéditeur** pour usurper une source fiable.
\> 95 % des courriels sur Internet sont des pourriels utilisant des adresses falsifiées.

**Risques :** hameçonnage (phishing), arnaques, propagation de maliciels.

<div class="columns">

**SPF** — *Sender Policy Framework*
Enregistrement DNS indiquant quels serveurs sont autorisés à envoyer des courriels depuis votre domaine.

**DKIM** — *DomainKeys Identified Mail*
Signature numérique des courriels sortants pour prouver leur authenticité.

</div>

> **DMARC** chapeaute SPF et DKIM et ajoute un mécanisme de rapport.

---

## Publicité malveillante
### *Malvertising*

Diffuser des maliciels ou des annonces trompeuses via des **réseaux publicitaires**.
En hébergeant des pubs, vous invitez un tiers à écrire du contenu sur vos pages.

**Types d'attaques :**
- Téléchargements de rançongiciels — parfois sans que l'utilisateur clique
- Redirections vers des sites d'hameçonnage
- *Scareware* — fausses alertes de sécurité
- *Browser locker* — navigateur verrouillé

**Protections :** réseaux publicitaires certifiés, CSP pour contrôler les domaines autorisés, journaliser les URLs de sortie des publicités.

---

## Débordement de tampon & RCE

<div class="columns">

**Buffer Overflow**
Écrire plus de données que ce qu'un bloc mémoire peut contenir.

En PHP, **pas possible dans votre code** — PHP gère la mémoire automatiquement.

Risque dans le runtime PHP (écrit en C) et le serveur web.

**Protection :** garder PHP et Apache à jour.

**Exécution de code à distance** *(RCE)*
Permet à un attaquant d'exécuter du code sur votre serveur.

Ne jamais passer des données non fiables à `eval()` ou `exec()`.

</div>

```php
$allowed = ['action1', 'action2'];

if (!in_array($_POST['action'], $allowed)) {
    throw new Exception('Action non permise');
}

call_user_func($_POST['action']); // Seulement ici
```

---

## Empoisonnement de l'entête Host
### *Host Header Poisoning*

L'entête `Host` est envoyée par le navigateur pour indiquer le domaine visé.
Si l'application génère des URLs à partir de cette valeur, un attaquant peut la **falsifier**.

**Risque principal :** courriels de réinitialisation de mot de passe avec un lien redirigé vers un faux site.

**Protections :**
- Utiliser des **URLs relatives** partout où c'est possible
- Pour les URLs absolues (courriels), toujours lire le domaine depuis un **fichier de configuration**, jamais depuis `$_SERVER['HTTP_HOST']`

---

## Inclusion de script intersites & XSSI
### *Cross-Site Script Inclusion*

Un site malveillant importe un fichier JavaScript d'un autre domaine pour en extraire des données sensibles.

Contrairement au JSON et au HTML, les fichiers JavaScript **ne sont pas soumis à la politique de même origine** — n'importe quel site peut inclure vos fichiers JS avec `<script>`.

**Protection :**
- Ne **jamais** inclure de données sensibles (identifiants, jetons) dans des fichiers JavaScript
- Charger les données via des **URLs JSON dynamiques** — soumises à la politique de même origine

---

## Empoisonnement DNS & Squattage de sous-domaine

<div class="columns">

**DNS Poisoning**
Retourner de fausses adresses IP pour intercepter le trafic.

Usages : hameçonnage, interception (MitM), DDoS, distribution de maliciels.

**Protection :** HTTPS limite l'impact. **DNSSEC** signe cryptographiquement les réponses DNS.

**Subdomain Squatting**
Prendre le contrôle d'un sous-domaine via une entrée DNS pointant vers une ressource supprimée.

Risques : lecture des cookies, XSS, contournement du CSP, hameçonnage.

**Protection :** supprimer l'entrée DNS *avant* de déprovisionner la ressource.

</div>

---

<!-- _class: chapter -->

# Sécurité et IA
## Utiliser les outils IA de façon sécuritaire

---

## Ne pas partager d'informations sensibles

Les conversations envoyées à un service IA externe peuvent être utilisées pour entraîner de futurs modèles ou être accessibles aux employés du fournisseur.

**Ne jamais soumettre à une IA :**
- Mots de passe ou clés API
- Chaînes de connexion à une base de données
- Informations personnelles de clients
- Code contenant de la propriété intellectuelle confidentielle
- Données couvertes par un NDA

> En entreprise, vérifiez la politique de votre organisation avant de soumettre du code à un outil IA.

---

## Vérifier le code généré

Les modèles d'IA peuvent suggérer du code **fonctionnel mais non sécuritaire** :
- Fonctions dépréciées ou retirées du langage
- Bibliothèques avec des vulnérabilités connues
- Méthodes d'authentification faibles
- Code vulnérable aux injections SQL, XSS, etc.

> Traitez le code généré par une IA comme du code écrit par un stagiaire : **il doit être revu avant d'être intégré**.

**Méfiance envers les hallucinations :**
- Fonctions ou méthodes qui n'existent pas
- Références bibliographiques ou URLs inexistantes
- Explications plausibles mais fausses

---

## Extensions IDE, droits d'auteur et coût

<div class="columns">

**Extensions IA (Copilot, Cursor…)**
Ont souvent accès à l'ensemble du projet.

- Mettre les fichiers `.env` dans `.gitignore` **et** les exclure de l'indexation
- Comprendre quelles données sont envoyées au service distant

**Contexte et coût**
La fenêtre de contexte est limitée — le modèle oublie les éléments anciens sur de longues sessions.

- Démarrer une nouvelle conversation plutôt que prolonger une très longue session
- Fournir seulement le code pertinent
- Pour les tâches répétitives, privilégier des **modèles locaux**

</div>

> Le statut légal du code généré par IA est encore flou — vérifiez la politique de votre employeur.

---

<!-- _class: title -->

# Résumé

| Menace | Protection clé |
|--------|---------------|
| DoS / DDoS | CDN, cache, scalabilité |
| Email Spoofing | SPF + DKIM + DMARC |
| Malvertising | CSP, réseaux certifiés |
| Buffer Overflow / RCE | Mises à jour, liste d'autorisation |
| Host Header Poisoning | Domaine en config, jamais `$_SERVER` |
| XSSI | Pas de données sensibles en JS |
| DNS Poisoning | HTTPS + DNSSEC |
| Subdomain Squatting | Supprimer le DNS avant de déprovisionner |
| IA | Ne pas partager de secrets, vérifier le code |
