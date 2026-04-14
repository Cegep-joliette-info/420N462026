# Autre

Plusieurs points de sécurités ne sont pas classés par l'OWASP, mais sont tout de même importants à connaître.

## Table des matières

* [Déni de service](#déni-de-service)
* [Usurpation de courriel](#usurpation-de-courriel)
* [Publicité malveillante](#publicité-malveillante)
* [Débordement de tampon](#débordement-de-tampon)
* [Empoisonnement de l'entête Host](#empoisonnement-de-lentête-host)
* [Exécution de code à distance](#exécution-de-code-à-distance)
* [Inclusion de script intersites](#inclusion-de-script-intersites)
* [Empoisonnement DNS](#empoisonnement-dns)
* [Squattage de sous-domaine](#squattage-de-sous-domaine)

## Déni de service

En anglais: _Denial of Service (DoS)_

Un attaquant génère suffisamment de trafic pour épuiser les ressources du serveur et le rendre indisponible aux utilisateurs légitimes.
Les attaques distribuées (_DDoS_) utilisent plusieurs adresses IP simultanément, ce qui rend le filtrage très difficile.

Les motivations peuvent être politiques, financières (extorsion) ou simplement malveillantes.

### Protections

Vérifiez avec votre hébergeur les options disponibles - la plupart des plateformes infonuagiques offrent une protection de base gratuite.

Un site conçu pour être évolutif résistera mieux à un trafic anormal :

* Servir les images et ressources statiques via un CDN;
* Mettre en cache les ressources fréquemment accédées pour réduire les appels à la base de données;
* Configurer l'entête `Cache-Control` sur les ressources peu changeantes;
* Exécuter les tâches longues (envoi de courriels, appels API) dans une file de traitement asynchrone;
* Automatiser le déploiement des serveurs web pour pouvoir augmenter le nombre d'instances rapidement;
* Implémenter des outils d'analytique pour détecter les pics de trafic et réagir en conséquence.

Référence: https://www.hacksplaining.com/app/lessons/denial-of-service/prevention

## Usurpation de courriel

En anglais: _Email Spoofing_

L'usurpation de courriel consiste à envoyer des messages avec une fausse adresse d'expéditeur.
C'est une technique courante des fraudeurs pour gagner la confiance de leurs victimes en se faisant passer pour une source fiable.

Plus de 95 % des courriels sur Internet sont des pourriels, et la plupart utilisent des adresses falsifiées.
Si votre domaine est utilisé dans des campagnes de pourriel, des attaquants peuvent :

* Voler les identifiants de vos utilisateurs avec des courriels d'hameçonnage (_phishing_);
* Les piéger avec des arnaques en abusant de leur confiance envers votre site;
* Propager des logiciels malveillants via des pièces jointes.

### Protections

Deux mécanismes DNS permettent d'authentifier les courriels envoyés depuis votre domaine :

* **SPF** (_Sender Policy Framework_) : publie un enregistrement DNS indiquant quels serveurs sont autorisés à envoyer des courriels depuis votre domaine.
* **DKIM** (_DomainKeys Identified Mail_) : ajoute une signature numérique aux courriels sortants pour prouver leur authenticité et détecter toute modification en transit.

Il existe aussi la norme **DMARC** (_Domain-based Message Authentication, Reporting & Conformance_) qui chapeaute les deux précédentes et ajoute un mécanisme de rapport.

En adoptant ces technologies, vos courriels légitimes seront également moins susceptibles d'être marqués comme pourriels.

Vous pouvez vérifier la configuration SPF et DKIM d'un domaine avec [MXToolbox](https://mxtoolbox.com/emailhealth/cegep-lanaudiere.qc.ca/).

Référence: https://www.hacksplaining.com/app/lessons/email-spoofing/prevention

## Publicité malveillante

En anglais: _Malvertising_

La publicité malveillante consiste à diffuser des logiciels malveillants ou des annonces trompeuses via des réseaux publicitaires.
En hébergeant des publicités, vous invitez un tiers à écrire du contenu sur vos pages — ce qui limite votre contrôle sur ce que vos utilisateurs reçoivent.

Les types d'attaques possibles incluent :

* Téléchargements malveillants (rançongiciels) — parfois sans même que l'utilisateur clique, juste en visitant la page;
* Redirections vers des sites d'hameçonnage;
* _Scareware_ — fausses alertes de sécurité qui incitent à télécharger un logiciel dangereux;
* Blocage du navigateur (_browser locker_) — logiciel qui verrouille le navigateur en se faisant passer pour une alerte de sécurité.

### Protections

* Travailler avec des réseaux publicitaires réputés et certifiés (ex: Google); éviter ceux qui utilisent des fenêtres contextuelles (_pop-ups_);
* Restreindre la publicité à des segments de marché pertinents et, si possible, approuver les annonceurs individuellement;
* Mettre en place une politique de sécurité du contenu (CSP) pour contrôler quels domaines peuvent héberger du contenu sur vos pages;
* Utiliser des outils de rapport d'erreurs côté client (Sentry, Rollbar) pour détecter un comportement anormal;
* Journaliser les URLs de sortie des publicités pour faciliter l'analyse forensique en cas d'incident.

Référence: https://www.hacksplaining.com/app/lessons/malvertising/prevention

## Débordement de tampon

En anglais: _Buffer Overflow_

Un débordement de tampon survient quand un programme tente d'écrire plus de données que ce qu'un bloc mémoire peut contenir.
En PHP, ce type de faille **n'est pas possible dans le code que vous écrivez** — PHP gère la mémoire automatiquement et ne donne pas accès aux pointeurs bas niveau.

Par contre, PHP étant écrit en C, des vulnérabilités de débordement ont déjà été découvertes dans :

* Le runtime PHP lui-même (certaines fonctions internes);
* Les extensions PHP écrites en C;
* Le serveur web (Apache, Nginx).

La protection se résume à **garder PHP et le serveur web à jour** avec les derniers correctifs de sécurité dès leur publication.

Référence: https://www.hacksplaining.com/app/lessons/buffer-overflows/prevention

## Empoisonnement de l'entête Host

En anglais: _Host Header Poisoning_

L'entête `Host` d'une requête HTTP est envoyée par le navigateur pour indiquer le domaine visé.
Si l'application génère des URLs absolues en se basant sur cette valeur, un attaquant peut la falsifier pour produire des liens malveillants.

Le risque principal concerne les **courriels transactionnels** (ex: réinitialisation de mot de passe) : si le lien de réinitialisation est construit à partir de l'entête `Host`, un attaquant peut rediriger la victime vers un faux site sous son contrôle pour voler ses identifiants.

### Protections

* Utiliser des **URLs relatives** partout où c'est possible — elles n'ont pas besoin du domaine;
* Lorsqu'une URL absolue est nécessaire (comme dans un courriel), toujours prendre le nom de domaine depuis un **fichier de configuration côté serveur**, jamais depuis l'entête `Host`.

Je vous ai dit d'utiliser `$_SERVEUR['HOST']` dans le routeur de votre MVC, vous pouvez garder le routeur tel quel (je n'enlèverai pas de points pour cette faille dans le TP2).

Référence: https://www.hacksplaining.com/app/lessons/host-header-poisoning/prevention

## Exécution de code à distance

En anglais: _Remote Code Execution (RCE)_

Une faille RCE permet à un attaquant d'exécuter du code arbitraire sur votre serveur en envoyant du code malveillant dans une requête HTTP.
Les conséquences sont dévastatrices : suppression de fichiers, vol de données sensibles, installation de logiciels malveillants.

La plupart des langages de programmation permettent d'exécuter une chaîne de caractères comme du code.
En PHP, c'est le cas des fonctions `eval()` et `exec()`, ainsi que des injections de commandes vues précédemment.

### Protection

Ne jamais passer des données non fiables à une fonction d'exécution de code.
Si vous devez absolument exécuter du code dynamique, validez la valeur contre une **liste d'autorisation** avant de l'exécuter :

```php
$allowed = ['action1', 'action2'];

if (!in_array($_POST['action'], $allowed)) {
    throw new Exception('Action non permise');
}

// Seulement ici on peut utiliser la valeur
call_user_func($_POST['action']);
```

Référence: https://www.hacksplaining.com/app/lessons/remote-code-execution/prevention

## Inclusion de script intersites

En anglais: _Cross-Site Script Inclusion (XSSI)_

Une attaque XSSI se produit lorsqu'un site malveillant importe un fichier JavaScript d'un autre domaine pour en extraire des données sensibles.

Contrairement au JSON et au HTML, les fichiers JavaScript **ne sont pas soumis à la politique de même origine** (_same-origin policy_) du navigateur.
N'importe quel site peut donc inclure vos fichiers JS avec une balise `<script>`.
Si vous y stockez des données sensibles (identifiants, jetons, informations utilisateur), un attaquant peut créer un faux site qui importe votre JavaScript et récupère ces données pour chaque victime qui le visite.

### Protection

Ne jamais inclure de données sensibles directement dans des fichiers JavaScript.
Utilisez plutôt des **URLs JSON** chargées dynamiquement, ou encodez les données dans le HTML de la page.
Ces deux types de contenu sont soumis à la politique de même origine et ne peuvent pas être exploités dans une attaque XSSI.

Référence: https://www.hacksplaining.com/app/lessons/cross-site-script-inclusion/prevention

## Empoisonnement DNS

En anglais: _DNS Poisoning_

Un attaquant exploite des vulnérabilités dans l'infrastructure DNS pour retourner de fausses adresses IP en réponse aux requêtes.
Cela lui permet d'intercepter, de lire et de manipuler le trafic destiné à votre site.

Les usages malveillants incluent :

* **Hameçonnage** — rediriger les utilisateurs vers un faux site qui imite le vôtre pour voler leurs identifiants;
* **Attaque de l'intercepteur** (_Monster-in-the-Middle_) — écouter ou altérer les communications entre deux parties;
* **DDoS** — diriger un volume massif de trafic vers une cible;
* **Distribution de logiciels malveillants** — rediriger les utilisateurs vers un serveur hébergeant des maliciels.

### Protections

La bonne nouvelle : si vous utilisez **HTTPS**, l'impact est limité.
Un attaquant qui redirige votre trafic via DNS devra aussi présenter un certificat valide au navigateur.
S'il utilise le vôtre, il ne pourra pas déchiffrer le trafic; s'il utilise le sien, le navigateur affichera une alerte de sécurité.

Pour aller plus loin, **DNSSEC** (_DNS Security Extensions_) permet aux serveurs DNS de signer cryptographiquement leurs réponses, empêchant ainsi l'empoisonnement.
La plupart des grands hébergeurs et domaines de premier niveau (_TLD_) le supportent.

Référence: https://www.hacksplaining.com/app/lessons/dns-poisoning/prevention

## Squattage de sous-domaine

En anglais: _Subdomain Squatting_

Le squattage de sous-domaine survient quand un attaquant prend le contrôle d'un sous-domaine de votre site.
Cela se produit généralement lorsqu'une entrée DNS de type CNAME pointe vers une ressource qui n'existe plus (ex: un hébergement cloud supprimé).
L'attaquant n'a qu'à réclamer cette ressource pour servir du contenu sous votre domaine.

Les risques incluent :

* Lire les cookies définis depuis le domaine principal;
* Héberger du JavaScript malveillant pour lancer des attaques XSS;
* Contourner la politique CSP pour capturer des identifiants;
* Lancer des campagnes d'hameçonnage depuis une URL de confiance (les filtres de courriels font moins confiance aux alertes sur un domaine reconnu).

### Protections

* Toujours supprimer les entrées DNS **avant** de déprovisionner une ressource;
* Tenir un inventaire à jour de tous vos domaines et sous-domaines;
* Lors du provisionnement : revendiquer l'hôte virtuel d'abord, créer l'entrée DNS en dernier;
* Lors du déprovisionnenment : supprimer l'entrée DNS en premier;
* Scanner périodiquement vos sous-domaines avec des outils comme [Sublist3r](https://github.com/aboul3la/Sublist3r) ou [OWASP Amass](https://github.com/owasp-amass/amass);
* Éviter les certificats _wildcard_ si vous n'en avez pas besoin — énumérez explicitement vos sous-domaines;
* Ne partager les cookies avec les sous-domaines que si nécessaire (éviter l'attribut `domain` dans `Set-Cookie` par défaut).

Référence: https://www.hacksplaining.com/app/lessons/subdomain-squatting/prevention

