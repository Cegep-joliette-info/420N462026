# Gestion du mot de passe

En anglais: _password mismanagement_.

## Authentification

4 méthodes existent pour une connexion fiable:

1. Authentification HTTP native.
   Le navigateur vous demande un nom d'utilisateur et un mot de passe.
   Difficile à sécuriser, c'est le serveur web qui devra tout gérer.
   Souvent vu pour les authentifications avec un serveur LDAP.
2. Authentification PHP.
   C'est ce qu'on fait, on gère tout en HTML/PHP.
3. Authentification avec une librairie.
   Il existe du code déjà fait pour gérer l'authentification, mais vous vous exposez à une faille du type "dépendance toxique".
4. Authentification avec un service tier.
   Par exemple un bouton "Connexion avec Google", le site vous transfère vers Google, Google vous demande si vous voulez bien vous connecter à cet autre site et vous êtes connectés.
   Nous verrons comment faire si nous avons le temps à la fin de la session.
   Il existe aussi des services de SSO (Single Sign-On) qui entre dans cette catégorie.

## Valider l'adresse courriel

Suite à la création du compte, on génère un jeton aléatoire qui est valide pendant un temps restraint (1h par exemple).
Le code pourrait ressembler à:

```php
$jeton = bin2hex(random_bytes(12));
$echeance = time() + 3600;
```

On enregistre les deux données dans la base de données et on envoie un courriel qui contient un lien qui pointe vers une action d'activation du compte.
L'URL pourrait ressembler à: `http://localhost/user/activate/jetonactivation` ou `http://localhost/user/activate?jeton=jetonactivation`.
Si le jeton est encore valide, on débloque l'utilisateur, il peut maintenant se connecter (pas de connexion automatique).

Pour plus de sécurité, on pourrait bannir les adresses courriels "jetables".
Vous pouvez trouver des listes de ces domaines en ligne.

Si un utilisateur se connecte (avec succès) avec un compte non-activé:
  - Si le jeton n'est pas échue, on dit à l'utilisateur de consulter ses courriels.
  - Si le jeton est échue, on renvoie un courriel à l'utilisateur avec un nouveau jeton.

## Oublie de mot de passe

C'est la même logique de la validation de courriel, un jeton, une expiration et un courriel.

Pour plus de sécurité, on demande de saisir l'adresse courriel ou le nom d'utilisateur avec le nouveau mot de passe.

Lorsqu'un utilisateur oublie son mot de passe, j'active aussi le compte si ce n'est pas fait.

## Changement du mot de passe

Lorsque l'utilisateur est connecté et qu'il veut changer son mot de passe, demandez son ancien mot de passe afin d'accepter la modification.

Lorsque le mot de passe est modifié, invalidez le jeton d'oublie de mot de passe.

## Complexité du mot de passe

C'est une bonne idée de forcer une complexité de mot de passe minimale (ne pas mettre de maximum).
N'allez pas trop complexe! Une règle trop sévère va donner des mauvaises pratiques de sécurité à vos utilisateurs (comme l'écrire sur Facebook).
N'oubliez pas que la longueur du mot de passe est plus importante que sa complexité!

Faites attention, votre validation de la complexité du mot de passe en HTML/JavaScript doit donner le même résultat que votre Validation PHP.

À la création du compte et lors d'oublie/modification de mot de passe, demandez une confirmation de mot de passe.

## Entreposage du mot de passe

Le mot de passe doit être entreposé dans la base de données de manière sécuritaire.
Il doit être salé et haché avec une technique sécuritaire.
Voici un extrait de code qui montre la logique du sel:

```php
$salt = bin2hex(random_bytes(12));
$pass = $_POST['password'] . $salt;
$secure_pass = hash('sha256', $pass);
```

Le `secret` est le même pour tous les utilisateurs, on peut le mettre dans un fichier de configuration.
Le `salt` et le `secure_pass` sont entreposés dans la base de données dans la table utilisateur, ils seront utiles lorsqu'on voudra valider le mot de passe lors de la connexion.

Mais c'est très laborieux, utilisez les méthodes `password_hash` et `password_verify` qui gèrent automatiquement le salt, secret et hash dans un seul champ.

Le mot de passe doit toujours circuler en HTTPS, voir le chapitre 4 sur la sécurité `Défaillances cryptographiques` pour plus de détails.

## Authentification multi-facteur

Lors de la connexion d'un utilisateur vous pouvez demander un code secret envoyé à un autre dispositif:

1. Par courriel, méthode la plus simple, avec un jeton et une expiration.
2. Par SMS, similaire au courriel, mais il y a des frais pour envoyer des SMS.
3. Par code QR avec Google Authenticator (par exemple), plus complexe que les autres méthodes à coder.
4. Par notification sur cellulaire, vous avez besoin de votre propre application...

Pour le TP2, pas besoin de faire une double-authentification.

## Déconnexion

Pour déconnecter l'utilisateur de manière sécuritaire, exécutez les deux lignes suivantes:

```php
$_SESSION = [];
session_destroy();
```

Le `session_destroy` détruit l'ID de session, mais les informations de sessions existent encore.
Il faut donc vider la session avant de la détruire.

## Se souvenir de moi - *remember me*

La session PHP est valide tant que le navigateur est ouvert, par défaut.
C'est bien, mais si on veut rester connecté plus longtemps on fait quoi?

Lorsque l'utilisateur coche la case "Se souvenir de moi", on génère un jeton et une échéance (7 jours par exemple).
On enregistre ce jeton dans un cookie sécuritaire qui sera valide pour le temps déterminé (il faut quand même vérifier l'échéance en PHP).
Créez une classe/fonction utilitaire que vous appelez dans le routeur.
Dans cette fonction:
1. Si l'utilisateur est connecté dans la session on ne fait rien (`return`);
2. Si le jeton est présent dans le cookie, qu'il est valide et non-échue, on connecte l'utilisateur;
3. Sinon, l'utilisateur reste déconnecté;

## Attaque de type force brute

L'attaque de type force brute (_bruteforce_) consiste à essayer toutes les combinaisons possibles de mot de passe pour trouver le bon. La meilleure méthode de protection est détaillé dans [énumération d'utilisateurs](notes/securite/enumeration.md).

Certains sites mettent un délai entre les tentatives de connexion, par exemple 1 minute après 3 tentatives échouées, 5 minutes après 6 tentatives échouées, etc. Mais cette technique va nuire à l'expérience utilisateur, car le vrai utilisateur peut se faire bloquer par un attaquant qui essaye de se connecter avec son nom d'utilisateur.

Bloquer la connexion après un certain nombre de tentatives échouées selon l'adresse IP est aussi une technique, mais l'adresse du client peut être manipulée ce qui rend cette technique peu fiable. Cette technique va aussi nuire au UX car une erreur d'un utilisateur pourrait bloquer tous les utilisateurs qui se connectent à partir de la même adresse IP (ex: le Cégep).

Vous pouvez aussi implémenter un Captcha.
Il est conseillé d'utiliser [reCaptcha de Google](https://developers.google.com/recaptcha/).
Dans la version gratuite, il y a une limite de 1000 appels par seconde et 1 million par mois, il faut se protéger contre les attaques de type bruteforce dans ce cas!

Pour le TP2, vous n'avez pas besoin de faire une protection contre les attaques de type force brute.

## Sécurité supplémentaire

Non-utile pour le TP2, mais pratique:

Vous pouvez déconnecter l'utilisateur après une période d'inactivité (comme Moodle fait).
Ça prend du JavaScript coordonné avec du PHP pour que ça fonctionne.

Référence: https://www.hacksplaining.com/app/lessons/password-mismanagement/prevention