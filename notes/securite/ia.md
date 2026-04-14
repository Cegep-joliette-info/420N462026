# Utiliser l'IA de façon sécuritaire

Le document sur la [sécurité de l'IA](ia-dev.md) couvre les risques du point de vue d'un développeur qui **construit** des systèmes IA.
Cette page s'adresse plutôt au développeur qui **utilise** des outils IA (assistant de code, chatbot, génération de texte) dans son travail quotidien.

## Ne pas partager d'informations sensibles

Les conversations envoyées à un service IA externe (ChatGPT, Claude, Copilot, etc.) peuvent être utilisées pour entraîner de futurs modèles ou être accessibles aux employés du fournisseur.

Ne jamais soumettre à un outil IA :

* Des mots de passe ou clés API;
* Des chaînes de connexion à une base de données;
* Des informations personnelles de clients ou d'utilisateurs;
* Du code contenant de la propriété intellectuelle confidentielle;
* Des données couvertes par un accord de confidentialité (NDA).

En entreprise, vérifiez la politique de votre organisation concernant l'utilisation des outils IA avant de leur soumettre du code.

## Vérifier le code généré

Les modèles d'IA peuvent suggérer du code fonctionnel mais non sécuritaire :

* Fonctions dépréciées ou retirées du langage;
* Bibliothèques avec des vulnérabilités connues;
* Méthodes d'authentification faibles;
* Code vulnérable aux injections SQL, XSS, etc.

Traitez le code généré par une IA comme du code écrit par un stagiaire : **il doit être revu avant d'être intégré**.
Ne copiez jamais du code sans le comprendre.

## Méfiance envers les hallucinations

Un modèle peut vous présenter des faits inventés avec une apparente confiance.
Cela inclut :

* Des fonctions ou méthodes qui n'existent pas dans la documentation officielle;
* Des références bibliographiques ou des URLs inexistantes;
* Des explications plausibles mais fausses sur le fonctionnement d'une technologie.

Toujours valider les informations importantes avec la documentation officielle.

## Risques liés aux extensions IDE

Les extensions IA pour les éditeurs de code (GitHub Copilot, Cursor, etc.) ont souvent accès à l'ensemble de votre projet, incluant les fichiers de configuration.
Assurez-vous que :

* Vos fichiers `.env` sont dans le `.gitignore` **et** exclus de l'indexation de l'outil IA si possible;
* Vous comprenez quelles données l'extension envoie au service distant.

## Droits d'auteur

Le statut légal du code généré par IA est encore flou dans plusieurs juridictions.
En contexte professionnel, vérifiez les politiques de votre employeur sur l'utilisation de code généré par IA, particulièrement pour du code destiné à être livré à un client.

## Taille du contexte et coût

Les modèles d'IA ont une **fenêtre de contexte** limitée : la quantité de texte (code, instructions, historique) qu'ils peuvent traiter en une seule requête.
Lorsque le contexte est plein, le modèle oublie les éléments les plus anciens, ce qui peut dégrader la qualité des réponses sur de longues conversations.

En contexte professionnel, chaque requête a un coût calculé selon le nombre de _tokens_ (unités de texte) envoyés et reçus.
Envoyer de gros fichiers ou des historiques de conversation très longs fait grimper la facture rapidement.

Conseils pratiques :

* Démarrer une nouvelle conversation plutôt que de prolonger une très longue session;
* Fournir seulement le code pertinent à la question, pas l'ensemble du projet;
* Pour des tâches répétitives, privilégier des outils locaux (modèles tournant sur votre machine) afin de contrôler les coûts et d'éviter d'envoyer du code à l'externe.

## Réutilisation de code généré

Comme le contexte d'IA est limité, il peut arriver qu'au lieu de réutiliser une classe existante, le modèle génère une nouvelle classe similaire.
Cela peut créer de la confusion, du code redondant, plus de code à maintenir, plus de risques de bugs et plus de contexte pour maintenir.

Vérifiez le code généré avant de l'intégrer. Les entreprises considèrent que l'IA triple la productivité, si vous allez 10x plus vite que sans IA, probablement que vous ne vérifiez pas assez le code généré. 

> Ce document a été rédigé avec l'aide de l'intelligence artificielle.
