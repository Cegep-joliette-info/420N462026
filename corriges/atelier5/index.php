<?php
/** @var $bd PDO */
require_once('config.php');

// Pas demandé, mais une bonne idée de gérer les erreurs
$error = $_SESSION['error'] ?? false;
if ($error) {
    unset($_SESSION['error']);
}
?>
<!-- J'ai oublié de sauvegarder la prompt AI pour cette page -->
<!doctype html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atelier 5</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Atelier 5</h1>
        <p class="mt-2 text-base text-slate-700 dark:text-slate-300">Choisissez votre classe pour le combat</p>
    </header>
    <form class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" action="choix.php" method="post">
        <?php if ($error) { ?>
            <div id="error-zone" class="mb-4 rounded-lg border-l-4 border-red-600 bg-red-50 p-4 text-red-900 dark:border-red-500 dark:bg-red-950 dark:text-red-100" role="alert" aria-live="polite" aria-labelledby="error-title">
                <h2 id="error-title" class="font-semibold">Erreur</h2>
                <p id="error-message" class="mt-1 text-sm"><?= $error ?></p>
            </div>
        <?php } ?>
        <fieldset class="space-y-4">
            <legend class="text-sm font-semibold text-slate-700 dark:text-slate-300">Classe</legend>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <label for="classe-guerrier" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-guerrier" name="classe" type="radio" value="Guerrier" class="sr-only" required>
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    <span class="text-sm font-semibold">Guerrier</span>
                </label>
                <label for="classe-voleur" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-voleur" name="classe" type="radio" value="Voleur" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    <span class="text-sm font-semibold">Voleur</span>
                </label>
                <label for="classe-magicien" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-magicien" name="classe" type="radio" value="Magicien" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                    <span class="text-sm font-semibold">Magicien</span>
                </label>
                <label for="classe-barbare" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-barbare" name="classe" type="radio" value="Barbare" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z"/></svg>
                    <span class="text-sm font-semibold">Barbare</span>
                </label>
                <label for="classe-paladin" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-paladin" name="classe" type="radio" value="Paladin" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z"/></svg>
                    <span class="text-sm font-semibold">Paladin</span>
                </label>
                <label for="classe-clerc" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-clerc" name="classe" type="radio" value="Clerc" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                    <span class="text-sm font-semibold">Clerc</span>
                </label>
                <label for="classe-assassin" class="group relative flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-slate-200 p-4 transition hover:border-slate-400 has-[:checked]:border-slate-900 has-[:checked]:bg-slate-50 dark:border-slate-800 dark:hover:border-slate-600 dark:has-[:checked]:border-slate-100 dark:has-[:checked]:bg-slate-800">
                    <input id="classe-assassin" name="classe" type="radio" value="Assassin" class="sr-only">
                    <svg class="h-12 w-12 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/></svg>
                    <span class="text-sm font-semibold">Assassin</span>
                </label>
            </div>
        </fieldset>
        <div class="mt-6">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white dark:focus-visible:ring-slate-400 dark:focus-visible:ring-offset-slate-900">
                Commencer
            </button>
        </div>
    </form>
</main>
</body>
</html>
