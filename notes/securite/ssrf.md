# SSRF 

SSRF est un acronyme pour _Server Side Request Forgery_

Attaque possible si votre site fait appel à un autre site.
Par exemple:

* Appel à un SSO pour une connexion;
* Appel à un API de géolocalisation;
* Recherche d'image par URL;
* etc.

Un pirate remarque que vous utilisez ce service et fait appel à votre page qui utilise ce service.
Le pirate peut donc DDOS l'autre système en utilisant votre site!
Le pire, votre site sera accusé de DDOS et se fera bloquer.

Pour vous protéger:

* Éviter d'appeler d'autres sites en JavaScript, JavaScript va appeler votre API qui lui va, en PHP, appeler l'autre site.
  De cette manière le pirate ne pourra pas DDOS par JavaScript.
* Valider les données sur votre serveur avant de les envoyer.
* Ne pas faire d'appel vers un domaine saisi par l'utilisateur, ou alors faire une liste d'autorisation de domaines.
* Ne pas faire d'appel HTTP pour des utilisateurs anonymes.

Référence: https://www.hacksplaining.com/app/lessons/ssrf/prevention