# CSRF

CSRF est un acronyme pour _Cross-Site Request Forgery_

Le principe d'une attaque CSRF est simple, par exemple j'envoie à l'admin le lien `http://siteweb.com/users/delete/1`.
L'admin est connecté, lorsqu'il clique sur le lien, ça supprime l'utilisateur qui a l'ID 1.
Qui est le premier utilisateur créé habituellement (donc avec ID 1)? L'admin.

[Exemple de formulaire vulnérable](/exemples/csrf/unsafe.php)

Ce type d'attaque est valide pour toute action modifiant quelque chose dans la base de données ou la session.
Habituellement tous les formulaires doivent être protégés.
Le bouton de déconnexion doit aussi être protégé (il modifie la session).

Une méthode de protection naive serait de juste utiliser POST, de cette manière un pirate ne peux pas seulement envoyer un lien à un utilisateur connecté.
Par contre, je pourrais envoyer un POST à partir d'un autre site, à partir d'un formulaire ou de manière invisible à l'utilisateur avec fetch.
Par contre, ce type de protection est en accord avec l'anatomie d'une URL REST: GET ne devrait rien modifier sur le site, contrairement à POST, PUT et DELETE.

La vraie méthode de protection est d'utiliser un jeton aléatoire nommé "CSRF_TOKEN" dans tous les formulaires POST.

1. À l'affiche d'une vue contenant un formulaire, on génère un jeton aléatoire.
   1. On sauvegarde ce jeton dans la session.
   2. On met aussi le jeton dans un champ de type "hidden" dans notre formulaire.
2. Lorsqu'on reçoit le formulaire en POST, on devrait avoir le jeton qui était caché.
   1. Si le jeton reçu en POST est le même que celui dans la session, il s'agit d'un formulaire valide.
   2. Si le jeton est absent ou différent, il doit s'agir d'une tentative d'un pirate.

Le jeton peut être généré de plusieurs manières, chaque méthode a un niveau de sécurité et d'inconvénient différent:

1. On génère un jeton pour chaque formulaire qu'on sauvegarde dans la session.
   Méthode la plus sécuritaire, mais si l'utilisateur ouvre un 2e onglet ça invalide le premier formulaire ouvert.
2. On génère un jeton unique à la connection de l'utilisateur.
   On corrige le problème précédent des multiples onglets, mais ça donne plus de chance au pirate de trouver le bon jeton.
3. On hache la concaténation entre une chaîne aléatoire générée à la connection de l'utilisateur avec un identifiant unique de formulaire.
   De cette manière le problème de multiple onglet est corrigé et chaque formulaire aura son propre jeton, si un pirate trouve un jeton son attaque sera limité à ce formulaire.

[Exemple de formulaire sécurisé avec la méthode 1](/exemples/csrf/safe.php)

Pour votre TP2 vous devrez utiliser la 3e méthode.