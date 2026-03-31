# Attaque par déclassement

En anglais: _Downgrade Attack_

Attaque où un pirate force une connexion à utiliser une version plus ancienne et vulnérable de TLS.

TLS évolue continuellement: TLS 1.0, 1.1, 1.2, 1.3. Les serveurs acceptent souvent plusieurs versions pour la compatibilité avec les vieux navigateurs. Un pirate en man-in-the-middle peut manipuler la négociation de protocole pour forcer l'utilisation d'une version ancienne qui contient des failles connues.

C'est lié au [SSL stripping](ssl-stripping.md): les deux sont des attaques de type man-in-the-middle ciblant le chiffrement, mais le SSL stripping dégrade HTTPS vers HTTP tandis qu'une attaque par déclassement dégrade TLS 1.3 vers une vieille version de TLS/SSL.

## Attaques connues

* **POODLE** (_Padding Oracle On Downgraded Legacy Encryption_) — force un déclassement vers SSL 3.0 pour décrypter le trafic.
* **BEAST** (_Browser Exploit Against SSL/TLS_) — exploite les failles de TLS 1.0.

## Pour vous protéger

* Désactiver les protocoles obsolètes: SSLv3, TLS 1.0, TLS 1.1.
* N'autoriser que TLS 1.2 et TLS 1.3 sur le serveur.
* Activer `TLS_FALLBACK_SCSV` sur le serveur pour empêcher la négociation vers une version inférieure.

En pratique, **cPanel gère déjà cette configuration** pour vous: les vieilles versions de TLS sont désactivées et seules TLS 1.2 et 1.3 sont autorisées sur nos serveurs.

Référence: https://www.hacksplaining.com/app/lessons/downgrade-attacks/prevention
