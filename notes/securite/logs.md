# Journalisation

En anglais: _Logging and Monitoring_

Apache journalise déjà les erreurs 500, mais nous allons créer notre propre journalisation.

Dans votre MVC, dans un dossier d'utilitaires créez une classe de journalisation.
Créez une fonction par niveau de journalisation (par exemple: paiement, protection, debug, etc.).
Habituellement les niveaux de journalisation sont: DEBUG, INFO, WARNING et ERROR.
Chaque fonction va ajouter une ligne dans un fichier journal correspondant:

```php
file_put_contents('logs/payments.log', date("Y.n.j") . $message, FILE_APPEND);
```

Attention à bien mettre vos journaux dans un dossier inaccessible à l'utilisateur sur le CPanel!

Dans l'énoncé du TP2 je vais vous dire quoi journaliser.
Voici les événements importants à journaliser:

* Échecs de validation des entrées (paramètres inattendus, valeurs invalides);
* Succès et échecs d'authentification;
* Échecs d'autorisation (accès refusé);
* Échecs de gestion de session (cookie de session invalide);
* Erreurs applicatives;
* Démarrage et arrêt du serveur;
* Actions utilisateur (inscription, changement de mot de passe, suppression de compte);
* Actions administratives (modification des permissions);
* Appels à des services ou API tiers.

Il ne faut pas journaliser les informations suivantes:

* Mot de passe;
* Chaînes de connexion à la base de données;
* Clés API de services tiers;
* Clé de cryptage;
* Informations personnelles (nom, courriel, etc.);
* Informations de paiement (numéros de carte);
* En-têtes HTTP sensibles (ex: `Authorization`);
* ID de session ou cookies de session;
* Jetons d'accès (réinitialisation de mot de passe, etc.);
* Données pour lesquelles l'utilisateur a exercé son droit à l'oubli.

Faites très attention, vos fichiers journaux ne doivent pas être disponibles au téléchargement par un visiteur.

## Contenu d'une entrée de journal

Chaque ligne d'un fichier journal devrait contenir au minimum:

* Un horodatage;
* Le message;
* Le fichier et le numéro de ligne dans le code.

Selon le contexte, il peut être utile d'ajouter: l'URL et le code HTTP, l'adresse IP, le nom d'utilisateur connecté, ou le message d'erreur complet avec la trace de la pile.

## Agrégation des journaux

En entreprise, les journaux de plusieurs serveurs sont centralisés sur un serveur dédié.
Des outils comme LogStash, Graylog ou Splunk permettent de regrouper, rechercher et analyser les journaux en temps réel.

## Surveillance et alertes

La journalisation seule ne suffit pas : il faut surveiller activement ce qui se passe.
Les outils de surveillance suivent des métriques clés comme le temps de réponse, le nombre de requêtes par seconde, l'utilisation de la mémoire et les performances de la base de données.

Configurez des alertes qui avertissent votre équipe (par courriel ou messagerie) lorsqu'une erreur inhabituelle survient ou qu'une métrique atteint un seuil critique.
En entreprise, une rotation de garde assure qu'un développeur est toujours disponible pour réagir.

Préparez aussi un plan de dépannage à l'avance: redémarrer un serveur, bloquer une adresse IP malveillante, ou escalader vers un spécialiste (DBA, administrateur réseau).

## Temps de bon fonctionnement - *uptime*

Bonne pratique en entreprise:
Utilisez ou codez un outil qui permet de détecter si votre site fonctionne encore, tel que [Uptime Robot](https://uptimerobot.com/).
Cet outil vous envoie un courriel si votre site ne répond plus, il vérifie à toutes les 5 min.

En entreprise j'en avais codé un en Python. Pas besoin de le faire pour le TP2.

Référence: https://www.hacksplaining.com/app/lessons/logging-and-monitoring/prevention