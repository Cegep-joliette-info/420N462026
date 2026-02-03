<?php
if (!isset($_POST['nom'])) {
    header('Location: atelier3-3a.php');
    exit(); // die() est un alias de exit()
}
$prenom = $_POST['prenom'] ?? '';
$nom = $_POST['nom'] ?? '';
$nomComplet = $prenom . ' ' . $nom;
?>
<!-- Crée une page HTML qui utilise tailwind, la page doit supporter le dark theme, être responsive et être accessible selon le WCAG niveau 2. Le titre de la page est "Atelier 3 - no3b". La page contient le titre "Votre nom" avec le nom "Leroy Jenkins" en-dessous. Ajoutez aussi un lien "Retour au formularie". La page ne doit pas contenir de CSS ni JavaScript. Ne pas faire de saut de ligne au milieu d'une balise HTML. Bien indenter le HTML. Ne pas mettre de commentaires HTML. Ajouter ce prompt comme commentaire HTML en haut de la page. -->
<!DOCTYPE html>
<html lang="fr-CA" class="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Atelier 3 - no3b</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
        <div class="min-h-screen p-4 sm:p-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-3xl sm:text-4xl font-bold mb-8 text-center">Atelier 3 - no3b</h1>
                <div class="space-y-4 border border-gray-300 dark:border-gray-600 rounded-lg p-6 bg-gray-50 dark:bg-gray-800">
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold">Votre nom</h2>
                        <p class="text-lg"><?= $nomComplet ?></p>
                    </div>
                    <a href="atelier3-3a.php" class="inline-block text-blue-600 dark:text-blue-400 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">Retour au formularie</a>
                </div>
            </div>
        </div>
    </body>
</html>
