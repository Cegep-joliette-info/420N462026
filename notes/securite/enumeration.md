# Recensement des utilisateurs

En anglais: _user enumeration_.

Ce n'est pas une attaque dangereuse, mais une méthode pour obtenir la liste de vos utilisateurs.
Si j'obtiens la liste de tous les nom d'utilisateur ou courriel de votre site, je peux cibler mes attaques de type "force brute".

Je peux aussi fouiller sur le côté sombre du Web pour trouver une liste de mot de passes "leakés".
Comme les utilisateurs utilisent le même mot de passe sur plusieurs sites, ce n'est même plus de la force brute.

Pour se protéger, il faut d'abord identifier tous les formulaires qui peuvent donner des indices sur la présence d'un nom d'utilisateur ou courriel sur le site.
Nous aurons souvent:

## Formulaire de connexion

Utilisez un message générique comme "Nom d'utilisateur ou mot de passe invalide".
Évitez les messages comme "Nom d'utilisateur inexistant" ou "Le mot de passe n'est pas valide pour cet utilisateur".

## Création de compte

Évitez le message "L'adresse courriel existe déjà".
Privilégiez un message comme: "Un courriel a été envoyé à l'adresse rick@roll.com, cliquez sur le lien reçu pour activer votre compte.". Si le compte existe déjà, on envoie un courriel qui dit "Vous avez déjà un compte sur le site X, vous pouvez vous connecter (lien) ou modifier votre mot de passe si vous l'avez oublié (lien)".

## Oublie de mot de passe

Évitez les messages "L'adresse courriel n'existe pas" et "Vous recevrez un lien par courriel".
Privilégiez un message comme: "Un courriel contenant un lien pour réinitialiser votre mot de passe a été envoyé à l'adresse saisie, si le compte existe.".

Pour plus de sécurité, si le compte n'existe pas il faut ajouter un délais aléatoire avant de répondre, sinon un pirate peut faire du timing attack pour deviner si le compte existe ou pas.

## Autre

Portez attention aux autres formulaires qui permettent de faire un recensement d'utilisateurs, comme une modification de profil ou des pages de profils.

Si les techniques précédentes ne sont pas possibles, implémentez un reCaptcha.
Le recensement automatique via un script sera impossible.

Référence: https://www.hacksplaining.com/app/lessons/user-enumeration/prevention