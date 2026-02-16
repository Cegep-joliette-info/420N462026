# Atelier 6

Créez une table `utilisateurs` qui contient: id, nom_utilisateur et mot_de_passe. Insérez au moins 1 utilisateur.

Avec une architecture MVC simple, faites une page de connexion (login). Lorsque l'utilisateur soumet le formulaire affichez un des messages suivants:

 * Erreurs
    * Veuillez saisir un nom d'utilisateur
    * Veuillez saisir un mot de passe
    * Aucun utilisateur avec ce nom à été trouvé
    * Le mot de passe n'est pas valide
 * Succès
    * Connection réussis!

Consignes supplémentaires: 

 * En cas d'erreur, le nom d'utilisateur doit rester dans le champ mais pas le mot de passe.
 * En cas de succès cacher le formulaire
 * Affichez l'erreur ou le succès dans une couleur appropriée
 * Le mot de passe ne dois pas être visible (afficher des points au lieu des lettres saisies)