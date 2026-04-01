# SSRF 

SSRF est un acronyme pour _Server Side Request Forgery_

Attaque possible si votre site fait des requêtes HTTP vers d'autres serveurs à partir d'une URL fournie par l'utilisateur.
Par exemple:

* Recherche d'image par URL;
* Appel à un API de géolocalisation;
* Prévisualisation d'un lien (comme Facebook ou Slack);
* etc.

Le vrai danger du SSRF est qu'un pirate peut faire envoyer des requêtes par votre serveur vers des **ressources internes** normalement inaccessibles depuis l'extérieur :

* Services internes non exposés publiquement (API d'administration, base de données);
* Adresses IP privées (`127.0.0.1`, `192.168.x.x`, `10.x.x.x`);
* Service de métadonnées cloud AWS à l'adresse `169.254.169.254`, qui expose les clés d'accès du serveur;
* Fichiers locaux via le protocole `file://`.

Un pirate peut aussi utiliser votre serveur pour faire un DDoS sur un autre système — votre site sera alors accusé de l'attaque et bloqué.

Pour vous protéger:

* Ne pas faire d'appel vers une URL saisie par l'utilisateur, ou alors faire une liste d'autorisation de domaines.
* Bloquer les plages d'IP privées et locales avant d'effectuer la requête.
* Éviter d'appeler d'autres sites directement en JavaScript côté client — passer par votre propre API en PHP. De cette manière le pirate ne contrôle pas directement l'URL appelée.
* Valider et nettoyer les données sur votre serveur avant de les envoyer.
* Ne pas faire d'appel HTTP pour des utilisateurs anonymes.

```php
function estUrlSure(string $url): bool
{
    $infos = parse_url($url);

    // Autoriser seulement http et https
    if (!in_array($infos['scheme'] ?? '', ['http', 'https'])) {
        return false;
    }

    $ip = gethostbyname($infos['host'] ?? '');

    // Bloquer les adresses IP privées et locales
    return !filter_var($ip, FILTER_VALIDATE_IP, [
        'flags' => FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
    ]) === false;
}

$url = $_GET['url'] ?? '';

if (!estUrlSure($url)) {
    http_response_code(403);
    exit('URL non autorisée.');
}

$contenu = file_get_contents($url);
```

Références:
  * https://www.hacksplaining.com/app/lessons/ssrf/prevention
  * https://portswigger.net/web-security/ssrf