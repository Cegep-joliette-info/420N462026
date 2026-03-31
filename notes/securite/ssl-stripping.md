# Dégradation SSL

En anglais: _SSL stripping_.

Attaque de type man-in-the-middle où un pirate dégrade une connexion HTTPS en HTTP.

Le problème de base: les serveurs acceptent par défaut le trafic sur le port 80 (HTTP) et le port 443 (HTTPS).
Lorsqu'un utilisateur visite un site sans taper explicitement `https://`, son navigateur fait d'abord une requête HTTP.
Un pirate positionné entre l'utilisateur et le serveur intercepte cette requête et supprime la redirection vers HTTPS.
L'utilisateur se retrouve en HTTP sans s'en rendre compte, et le pirate peut lire tout le trafic en clair.

C'est lié à [SSL/HTTPS](ssl.md): la redirection HTTP → HTTPS et le certificat SSL seuls ne suffisent pas, car l'attaque se produit avant même que la redirection ait lieu.

## Pour vous protéger

* Voir les notes sur [SSL](ssl.md) pour forcer l'utilisation de HTTPS. HSTS est recommandé, mais l'important est de ne rien afficher en HTTP.

Référence: https://www.hacksplaining.com/app/lessons/ssl-stripping/prevention
