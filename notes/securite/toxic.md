# Dépendance toxique

Non problématique pour nous (vous ne pouvez pas utilisez des librairies dans vos TP), mais en entreprise, c'est un problème réel.
Surtout si vous utilisez node.js, la programmation avec cette technologie est basé sur l'inclusion de dépendance.

On inclut des dépendances pour sauver du temps et aussi, pour augmenter la sécurité.
Par exemple des librairies d'authentifications vont tout faire l'authentification pour nous (login, logout, register, forgot password, login avec Google, etc.), ce qui nous sauve beaucoup de temps.
En plus ils vont corriger des vulnérabilités PHP que nous ignorons l'existence!
Par contre, si cette librairie couve une faille qui est découverte, les pirates vont tester l'existence de cette faille sur notre site.

Exemples réels: Log4J, Heartbleed, XcodeGhost.

Donc une librairie va nous sauver du temps et souvent être plus sécuritaire, mais il faut rester vigilant.
Gardez des outils de veilles ou de gestion de dépendances (comme npm) a porté de main.

## Attaque par confusion de dépendances

Un pirate publie un paquet malicieux sur un registre public (npm, Packagist) avec le même nom qu'une dépendance privée de votre organisation.
Selon la configuration du gestionnaire de paquets, il peut télécharger la version publique plutôt que la version interne, introduisant du code malicieux dans votre projet.

## Pour vous protéger

* Épingler les versions exactes de vos dépendances (_pin_) plutôt que d'utiliser des plages indéterminées (ex: `^1.0.0`) pour éviter les mises à jour surprises.
* Configurer le gestionnaire de paquets pour prioriser les registres privés sur les registres publics. Comme ça si votre entreprise a une librairie interne nommée `auth`, elle sera utilisée à la place d'une librairie publique du même nom.
* Intégrer un outil de scan automatisé dans votre pipeline CI/CD pour détecter les dépendances compromises dans tout l'arbre de dépendances.
* Surveiller les bulletins de sécurité des librairies utilisées.
* Déployer uniquement à partir du code source versionné (éviter les installations manuelles en production).

Référence: https://www.hacksplaining.com/app/lessons/toxic-dependencies/prevention