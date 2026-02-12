<?php
/** @var $bd PDO */
require_once('config.php');

if (!isset($_SESSION['joueur']) || !isset($_SESSION['monstre'])) {
    $_SESSION['error'] = 'Vous devez choisir une classe avant de combattre';

    header('location: index.php');
    die();
}

// On commente les variables sessions pour l'IDE
/** @var Combatant $joueur */
$joueur = $_SESSION['joueur'];
/** @var Combatant $monstre */
$monstre = $_SESSION['monstre'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $joueur->attaquer($monstre);
    if ($monstre->vie > 0) {
        $monstre->attaquer($joueur);
    }

    if ($joueur->vie <= 0 || $monstre->vie <= 0) {
        header('location: fin.php');
        die();
    }
}
?>
<!-- Prompt AI pour l'interface: Afficher en 2 colonnes: à gauche le personnage et à droite le monstre. Pour les deux on affiche leur nom, leur attaque, leur défense et leur vie restante sur la vie max. La vie doit être accompagné d'une barre rouge encadré noir, qui est remplis au ratio de la vie. En bas un bouton attaquer. -->
<!doctype html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Combat - Atelier 5</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Combat</h1>
    </header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Personnage (Joueur) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="mb-4 text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $joueur->nom ?></h2>

            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="font-semibold text-slate-700 dark:text-slate-300">Attaque:</dt>
                    <dd class="text-slate-900 dark:text-slate-100"><?= $joueur->attaque ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-semibold text-slate-700 dark:text-slate-300">Défense:</dt>
                    <dd class="text-slate-900 dark:text-slate-100"><?= $joueur->defense ?></dd>
                </div>
                <div>
                    <div class="mb-1 flex justify-between">
                        <dt class="font-semibold text-slate-700 dark:text-slate-300">Vie:</dt>
                        <dd class="text-slate-900 dark:text-slate-100"><?= $joueur->vie ?> / <?= $joueur->vieMax ?></dd>
                    </div>
                    <div class="h-6 overflow-hidden rounded border-2 border-black bg-white dark:bg-slate-950">
                        <div class="h-full bg-red-600 transition-all duration-300" style="width: <?= ($joueur->vie / $joueur->vieMax * 100) ?>%"></div>
                    </div>
                </div>
            </dl>
        </div>

        <!-- Monstre -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="mb-4 text-2xl font-bold text-slate-900 dark:text-slate-100"><?= $monstre->nom ?></h2>

            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="font-semibold text-slate-700 dark:text-slate-300">Attaque:</dt>
                    <dd class="text-slate-900 dark:text-slate-100"><?= $monstre->attaque ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-semibold text-slate-700 dark:text-slate-300">Défense:</dt>
                    <dd class="text-slate-900 dark:text-slate-100"><?= $monstre->defense ?></dd>
                </div>
                <div>
                    <div class="mb-1 flex justify-between">
                        <dt class="font-semibold text-slate-700 dark:text-slate-300">Vie:</dt>
                        <dd class="text-slate-900 dark:text-slate-100"><?= $monstre->vie ?> / <?= $monstre->vieMax ?></dd>
                    </div>
                    <div class="h-6 overflow-hidden rounded border-2 border-black bg-white dark:bg-slate-950">
                        <div class="h-full bg-red-600 transition-all duration-300" style="width: <?= ($monstre->vie / $monstre->vieMax * 100) ?>%"></div>
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-8 text-center">
        <form action="combat.php" method="post">
            <button type="submit" name="action" value="attaquer" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-8 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white dark:focus-visible:ring-slate-400 dark:focus-visible:ring-offset-slate-950">
                Attaquer
            </button>
        </form>
    </div>
</main>
</body>
</html>
