# Atelier 9 - Atelier en continu

Cet atelier sera bonifié tout le reste de la session. Le but sera de faire une connexion, création de compte et oublie de mot de passe sécuritaire. Cet atelier pourra être utilisé tel-quel dans votre TP2. Aucun corrigé ne sera fournis.

## Partie 1 - MVC avancé

Dans un MVC avancé, créez une page d'accueil qui continent au moins un lien vers une page de connexion.

La page de connexion demande: nom d'utilisateur, mot de passe (type password, mais avec un bouton pour afficher/masquer le mot de passe), se souvenir de moi (checkbox) et un bouton de connexion. Il y a aussi un lien vers une page d'inscription et un lien vers une page de récupération de mot de passe.

La page de création de compte demande: adresse courriel, mot de passe, confirmation du mot de passe et un bouton de création de compte. Notez qu'il n'y a pas de nom d'utilisateur.
Les champs mots de passe et confirmation du mot de passe doivent être de type password, mais avec un bouton pour afficher/masquer les mots de passe.

Finalement, la page de récupération de mot de passe demande seulement l'adresse courriel et un bouton pour envoyer les instructions de récupération.

Les 3 pages doivent avoir une validation côté client et côté serveur. Par exemple, les mots de passe doivent être d'au moins 8 caractères, contenir une majuscule, une minuscule et un chiffre. L'adresse courriel doit être valide, etc. Les messages d'erreurs doivent être clairs et précis.

Un utilisateur connecté n'a plus accès aux 3 pages précédentes, mais a accès à un bouton de déconnexion.

## Partie 2 - Sécurité

 * Protégez toutes les pages avec un bon ACL (pas de sécurité par obfuscation).
 * Protégez les 3 pages contre les attaques CSRF.

 * Protégez les pages contre tous les types d'attaques XSS.
 * Protégez les pages contre les injections SQL.

 * Sécurisez la session et le cookie de session.
 * Protégez les pages contre les attaques de recensement d'utilisateurs.