<?php
/** @var $bd PDO */
require_once('config.php');

if (!isset($_SESSION['joueur']) || !isset($_SESSION['monstre'])) {
    header('location: index.php');
    die();
}

/** @var Combatant $joueur */
$joueur = $_SESSION['joueur'];
/** @var Combatant $monstre */
$monstre = $_SESSION['monstre'];

if ($joueur->vie > 0 && $monstre->vie > 0) {
    header('location: combat.php');
    die();
}

// Déterminer victoire ou défaite
$victoire = $joueur->vie > 0;
?>
<!doctype html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $victoire ? 'Victoire' : 'Défaite' ?> - Atelier 5</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex min-h-full items-center justify-center bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<main class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
    <?php if ($victoire) { ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-950 p-8 text-center shadow-lg">
            <!-- Victoire -->
            <svg class="mx-auto h-24 w-24 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <h1 class="mt-6 text-4xl font-bold tracking-tight text-green-900 dark:text-green-100 sm:text-5xl">Victoire !</h1>
            <p class="mt-4 text-lg text-green-800 dark:text-green-200">
                Félicitations, vous avez vaincu <?= $monstre->nom ?> !
            </p>
    <?php } else { ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-950 p-8 text-center shadow-lg">
            <!-- Défaite -->
            <svg class="mx-auto h-24 w-24 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <h1 class="mt-6 text-4xl font-bold tracking-tight text-red-900 dark:text-red-100 sm:text-5xl">Défaite...</h1>
            <p class="mt-4 text-lg text-red-800 dark:text-red-200">
                <?= $monstre->nom ?> vous a vaincu.
            </p>
            <p class="mt-2 text-base text-red-700 dark:text-red-300">
                Vous avez combattu vaillamment !
            </p>
        </div>
    <?php } ?>

    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="index.php" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white dark:focus-visible:ring-slate-400 <?= $victoire ? 'dark:focus-visible:ring-offset-green-950' : 'dark:focus-visible:ring-offset-red-950' ?>">
            Recommencer
        </a>
    </div>
</main>
</body>
</html>
