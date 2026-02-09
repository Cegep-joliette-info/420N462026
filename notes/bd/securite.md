# Sécurité

Les trucs de sécurités suivants sont uniquement bon pour les serveurs de productions. Si votre environnement de développement n'est pas sécuritaire, ce n'est pas trop grave...

 1. Chaque BD devrait avoir son propre utilisateur qui n'a accès qu'à cette base de données.
 2. PHPMyAdmin est l'interface de MySQL le plus utilisé, mais il n'est pas sécuritaire! On trouve souvent des failles de sécurités dans PHPMyAdmin. Si vous l'utilisez en production, assurez-vous qu'il soit à jour ou protégé par Firewall.
 3. En ligne de commande sur une nouvelle installation, exécuter "mysql_secure_install".
 4. Supprimer l'utilisateur admin par défaut (ou lui mettre un mot de passe). Habituellement l'utilisateur par défaut est 'root' et le mot de passe est vide (pas "", vraiment rien). Certains logiciels n'aiment pas ne pas avoir de mots de passe, c'est donc une bonne idée de mettre un mot de passe à root.
 5. Par défaut seuls les accès locaux sont disponibles. Donc PHP peut accéder à la BD mais je ne peux pas accéder à la BD sur le serveur à partir de mon ordinateur. Dans votre projet final vous devrez publier sur le serveur du cours, il est configuré de cette manière! C'est possible aussi de restreindre l'accès à un IP spécifique, mais restreindre à localhost est encore mieux.
 6. Protéger la BD par le firewall. MariaDB écoute sur le port 3306 par défaut. Changez ça et assurez vous que le firewall empêche les connexion distantes sur ce port.
 7. Et plusieurs autres trucs, mais c'est assez intense comme ça...