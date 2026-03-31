# Paramètres de sécurité laxistes

En anglais: _lax security settings_.

Un site est aussi sécurisé que sa configuration le définit. (merci à Claude pour la phrase).

## Vecteurs d'attaque courants

* **Comptes par défaut** — les serveurs de bases de données, serveurs d'application et CMS viennent souvent avec des identifiants préinstallés (ex: `admin`/`admin`). Un pirate peut les essayer systématiquement.
* **Interfaces d'administration exposées** — un panneau d'admin accessible depuis internet est une cible directe.
* **Environnements de pré-production** — des environnements de développement ou de staging mal sécurisés peuvent exposer des données ou des accès réels.
* **Requêtes de moteur de recherche ciblées** — des fichiers de configuration, des pages d'erreur ou des répertoires mal protégés peuvent être indexés et trouvés via des recherches ciblées.
* **Identifiants dans le code** — des clés d'API ou mots de passe commités dans le dépôt peuvent être découverts.

## Pour vous protéger

* Automatiser le processus de déploiement pour éviter qu'une mauvaise configuration passe en production.
* Séparer clairement le code de la configuration; stocker les paramètres sensibles (mots de passe, clés) hors du dépôt, dans des fichiers de configuration ou un gestionnaire de secrets.
* Utiliser des identifiants différents pour chaque environnement (développement, staging, production).
* Bloquer les accès réseau entre environnements pour limiter les déplacements latéraux en cas de compromission.
* Ne jamais exposer les interfaces d'administration sur internet; les protéger derrière un VPN ou une liste d'adresses autorisées.
* Changer immédiatement les identifiants par défaut de tout nouveau logiciel ou service installé.
* Activer l'authentification à deux facteurs pour les comptes d'administration.
* Supprimer ou désactiver toute fonctionnalité inutile (ports ouverts, services, comptes inactifs).

Référence: https://www.hacksplaining.com/app/lessons/lax-security-settings/prevention