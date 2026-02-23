# Atelier 5

## Partie 1

Dans votre BD, créez une table monstre qui contient:

 * ID (PK)
 * Nom
 * Vie
 * Force
 * Défense

Insérez manuellement au moins 3 monstres. Créez la classe PHP "Combatant" qui représente cette table.

Chaque classe de personnage sera une classe dans le code, elles ne proviennent pas de la BD (voir Partie 2 de l'atelier, pour l'instant toutes les classes peuvent être un Combatant).

Dans votre index.php, créez un select ou plusieurs bouton radio pour choisir une classe de personnage (juste 2 ou 3 classes de personnage). Pour l'instant il n'y a aucune différence entre les classes.

![Page 1](../images/atelier05/choix.png)

Une fois soumis, on va à la 2e page. On affiche le nom de la classe choisie ainsi qu'un monstre choisi au hasard.

![Page 2](../imgs/atelier05/combat.png)

Quand j'appuie sur Attaquer, ça envoie une requête GET ou POST. Le personnage attaque suivit immédiatement du monstre s'il est en vie.

Pour attaquer et défendre, nous allons utiliser le système de D10. Pour chaque point de statistique, choisir un nombre aléatoire entre 1 et 10, si le nombre est 6 ou plus, on compte un succès. Si l'attaque a plus de succès que la défense, l'attaquant effectue la différence en dommage. Par exemple:

 * Le joueur a 10 d'attaque, ses "dés" donnent: 1, 2, 3, 4, 5, 6, 7, 8, 9 et 10, soit 5 succès
 * Le monstre a 4 de défense, ses "dés" donnent: 1, 1, 10, 10, 10, soit 3 succès
 * Le joueur effectue donc 2 dommages au monstre

Lorsque le joueur ou le monstre tombe à zéro point de vie, on redirige vers une 3e page qui indique si on a gagné ou perdu, avec un lien pour recommencer.

## Partie 2

Chaque classe doit avoir un pouvoir spécial. Utilisez l'héritage ou les traits. Voici des idées:

 - Guerrier: Rien de spécial
 - Voleur: En attaque, un résultat 10 sur un dé compte pour deux succès, mais il a seulement 6 de vie
 - Magicien: En attaque, si l'attaque réussis, ignore la défense pour calculer les dommages, mais il a seulement 5 de vie et 3 de défense
 - Barbare: En attaque, il a besoin de 4 ou plus pour un succès. En défense, il a besoin de 8 ou plus pour un succès.
 - Paladin: En défense, il divise par deux les dégâts reçu, arrondis au plus bas, mais il a seulement 3 de force.
 - Clerc: À chaque attaque il se guéris de 1, il ne peux pas dépasser sa vie maximale. Il a 4 de force et 4 de défense.
 - Assassin: Il a une chance sur deux de disparaitre après une attaque, l'ennemi ne peux pas l'attaquer, mais il a seulement 4 de vie.