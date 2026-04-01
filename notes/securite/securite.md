# Sécurité WEB - base

L'OWASP (Open Web Application Security Project) a regroupé les types de failles et les a priorisés en ordre d'occurrence (et plusieurs autres critères).
Ce chapitre est construit en suivant l'ordre suggéré par le [TOP 10 de l'OWASP 2025](https://owasp.org/Top10/), sauf cette page qui montre la base de la sécurité informatique.

En sécurisant un site web, rappelez-vous que c'est impossible de faire un site impossible à pirater.
Votre but sera donc de:
1. Nuire le plus possible au pirate;
2. Dissimuler le plus d'information possible sur le code et le serveur;

## Données non-fiables

Les données pouvant être manipulées par un utilisateur sont considérées comme non-fiables.
Ça inclut toutes les variables superglobales sauf `$_SESSION`.
Attention, si une donnée non-fiable a été sauvegardée dans la base de données ou dans la session, on la considère quand même comme non-fiable.

Toutes les données non-fiables doivent être analysées pour s'assurer qu'aucune faille puisse être exploitée.

## Liste d'autorisation ou liste de rejet

Anciennement liste blanche et liste noire, mais c'est raciste...
En anglais c'est _allow list_ et _deny list_.

- Liste d'autorisation: On permet les éléments suivants.
- Liste de rejet: On refuse les éléments suivants.

Les deux listes vont arriver au même résultat, mais la liste d'autorisation va être plus sécuritaire.
Si vous oubliez un élément dans la liste d'autorisation, le client vous contacte et vous ajustez le code.
Si vous oubliez un élément dans la liste de rejet, le pirate s'amuse.

Exemple de projet où vous pourriez choisir entre une liste d'autorisation ou de rejet:
- WYSIWYG HTML, pour la liste des balises permises;
- Filtre par adresse IP;
- Liste de fichiers téléchargeables;

## Principe du moindre privilège

Plus de la configuration que de la programmation.
Le principe est de donner le moins de droit possible à votre utilisateur système qui exécute le logiciel Apache ou MariaDB.
Par exemple, si un pirate réussit à injecter du code PHP dans votre site, mais que votre utilisateur apache a accès root au système, il peut avoir beaucoup trop de plaisir.
Donc votre utilisateur apache doit avoir accès seulement aux fichiers web.
Votre utilisateur MariaDB doit seulement pouvoir faire les types de requêtes SQL utilisés par votre système.

C'est plus facile d'ajouter un privilège à un utilisateur que de réparer un système...

## Champ de mot de passe

Un _input_ pour un mot de passe doit toujours être de type _password_.
De cette manière, quelqu'un qui regarde votre écran ne verra pas votre mot de passe.
Pour l'accessibilité Web, on va souvent voir une icône d'oeil à droite du champ.
Un clic sur cette icône va changer, en JavaScript, le type du champ de _password_ à _text_ et vice-versa.
