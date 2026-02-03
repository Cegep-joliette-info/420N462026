<?php
$filtre = $_GET["filtre"] ?? "";
$jeux = [
    "The Legend of Zelda: Breath of the Wild",
    "The Legend of Zelda: Tears of the Kingdom",
    "Portal 2",
    "Resident Evil 2",
    "Uncharted 2: Among Thieves",
    "Elden Ring",
    "Stardew Valley",
    "Hades",
    "Hollow Knight",
    "Cyberpunk 2077",
];
sort($jeux);

// str_contains est sensible à la casse, on crée une version insensible à la casse
function str_icontains(string $haystack, string $needle): bool {
    return str_contains(strtolower($haystack), strtolower($needle));
}

if ($filtre) {
    $filtre = strtolower($filtre);

    // Solution 1, avec array_filter
    $jeux = array_filter($jeux, function($jeu) use ($filtre) {
        return str_icontains(strtolower($jeu), $filtre);
    });
    /*
    // Solution 2, avec array_filter, mais avec une fonction fléchée (lambda)
    $jeux = array_filter($jeux, fn($value) => str_icontains($value, $filtre));

    // Solution 3, avec un 2e tableau et une boucle
    $jeuxFiltres = [];
    foreach ($jeux as $jeu) {
        if (str_icontains($jeu, $filtre)) {
            $jeuxFiltres[] = $jeu;
        }
    }
    $jeux = $jeuxFiltres;

    // Solution 4, avec une boucle et suppression dans le tableau original
    $i = 0;
    while ($i < count($jeux)) {
        if (!str_icontains($jeux[$i], $filtre)) {
            array_splice($jeux, $i, 1);
        } else {
            $i++;
        }
    }*/
}
?>
<!--
Prompt du HTML:
Ajoute le CDN tailwind, dans une interface compatible avec le dark-theme, fait un formulaire qui contient un champ texte filtre et un bouton bleu filtrer. En dessous, une liste de 10 jeux vidéos, similaire au list-group de bootstrap. La page doit seulement contenir du HTML, pas de CSS ni Javascript
-->
<!DOCTYPE html>
<html lang="fr-CA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier 3-1</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
<div class="min-h-screen p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center">Jeux Vidéos</h1>

        <form class="mb-8 flex gap-3">
            <label for="filtre" class="sr-only">Filtrer les jeux</label>
            <input name="filtre" id="filtre" value="<?= $filtre ?>" type="text" placeholder="Filtrer les jeux..." class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-200">
                Filtrer
            </button>
        </form>

        <?php if ($jeux) { ?>
        <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden divide-y divide-gray-300 dark:divide-gray-600">
            <?php foreach ($jeux as $jeu) { ?>
                <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                    <?= $jeu ?>
                </div>
            <?php } ?>
        </div>
        <?php } else { ?>
            <p class="text-center text-gray-500 dark:text-gray-400">Aucun jeu trouvé.</p>
        <?php } ?>
    </div>
</div>
</body>
</html>

