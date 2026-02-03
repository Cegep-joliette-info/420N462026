<!-- Crée une page HTML qui utilise tailwind, la page doit supporter le dark theme, être responsive et être accessible selon le WCAG niveau 2. Le titre de la page est "Atelier 3 - no3a". La page contient un formulaire en post et contient les deux champs suivants: Nom et Prénom Ajouter aussi un bouton vert Soumettre au formulaire. La page ne doit pas contenir de CSS ni JavaScript. Ne pas faire de saut de ligne au milieu d'une balise HTML. Bien indenter le HTML. Ne pas mettre de commentaires HTML. Ajouter ce prompt comme commentaire HTML en haut de la page. -->
<!DOCTYPE html>
<html lang="fr-CA" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier 3 - no3a</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
<div class="min-h-screen p-4 sm:p-8">
    <div class="max-w-md mx-auto">
        <h1 class="text-3xl sm:text-4xl font-bold mb-8 text-center">Atelier 3 - no3a</h1>
        <form method="POST" action="atelier3-3b.php" class="space-y-6 border border-gray-300 dark:border-gray-600 rounded-lg p-6 bg-gray-50 dark:bg-gray-800">
            <div class="space-y-2">
                <label for="nom" class="block font-semibold">Nom</label>
                <input type="text" id="nom" name="nom" required aria-required="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="space-y-2">
                <label for="prenom" class="block font-semibold">Prénom</label>
                <input type="text" id="prenom" name="prenom" required aria-required="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <button type="submit" class="w-full px-6 py-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Soumettre
            </button>
        </form>
    </div>
</div>
</body>
</html>
