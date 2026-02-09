<?php
try {
    // Voir atelier4.sql pour la création de la base de données et de la table
    $bd = new PDO('mysql:dbname=test;host=172.17.0.1', 'root', 'root');
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit();
}

if (isset($_GET['delete'])) {
    // Suppression d'un jeu
    $query = $bd->prepare('DELETE FROM games WHERE id = ?');
    $query->bindParam(1, $_GET['delete'], PDO::PARAM_INT);
    $query->execute();
    session_start();
    $_SESSION['success_message'] = 'Le jeu a été supprimé avec succès.';
    // On redirige pour enlever le paramètre GET de l'URL
    header('Location: atelier4.php');
    exit();
}

$query = $bd->query('SELECT g.id, g.name, c.name categ FROM games g JOIN categories c ON g.category_id = c.id ORDER BY g.name ASC');
$games = $query->fetchAll(PDO::FETCH_ASSOC);

// On utilise la session pour afficher les messages de succès ou d'erreur après une redirection
session_start();
$success = '';
$error = '';
if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Atelier 4</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-50">
<div class="mx-auto max-w-3xl px-4 py-10">
    <header class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-semibold tracking-tight">Atelier 4</h1>
        <a href="atelier4-form.php" class="inline-flex items-center gap-2 rounded-md border border-slate-900 bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm outline-none ring-offset-2 transition hover:bg-slate-800 focus-visible:ring-2 focus-visible:ring-slate-900 dark:border-slate-50 dark:bg-slate-50 dark:text-slate-900 dark:hover:bg-slate-100 dark:focus-visible:ring-slate-50" aria-label="Ajouter un jeu">
            <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span class="hidden sm:inline">Nouveau</span>
        </a>
    </header>

    <?php if ($error) { ?>
        <div class="rounded-lg border border-red-300 bg-red-50 p-4 mt-8 dark:border-red-700 dark:bg-red-900">
            <div class="flex items-center gap-3">
                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200"><?= $error ?></p>
            </div>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="rounded-lg border border-green-300 bg-green-50 p-4 mt-8 dark:border-green-700 dark:bg-green-900">
            <div class="flex items-center gap-3">
                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0 text-green-600 dark:text-green-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <p class="text-sm font-medium text-green-800 dark:text-green-200"><?= $success ?></p>
            </div>
        </div>
    <?php } ?>

    <main class="mt-8">
        <ul class="space-y-4">
            <?php foreach ($games as $game) { ?>
                <li class="flex items-center justify-between gap-4 rounded-lg border border-slate-300 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold"><?= $game['name'] ?></h2>
                        <p class="text-sm text-slate-700 dark:text-slate-200"><?= $game['categ'] ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="atelier4-form.php?id=<?= $game['id'] ?>" class="inline-flex items-center rounded-md border border-emerald-700 bg-emerald-700 px-3 py-2 text-sm font-semibold text-white outline-none ring-offset-2 transition hover:bg-emerald-800 focus-visible:ring-2 focus-visible:ring-emerald-700 dark:border-emerald-300 dark:bg-emerald-300 dark:text-emerald-950 dark:hover:bg-emerald-200 dark:focus-visible:ring-emerald-300" aria-label="Modifier Celeste">
                            <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 0 1 3.182 3.182L8.868 18.845a4.5 4.5 0 0 1-1.897 1.13l-3.356 1.117 1.117-3.356a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487z"/>
                            </svg>
                        </a>
                        <a href="?delete=<?= $game['id'] ?>" class="inline-flex items-center rounded-md border border-rose-700 bg-rose-700 px-3 py-2 text-sm font-semibold text-white outline-none ring-offset-2 transition hover:bg-rose-800 focus-visible:ring-2 focus-visible:ring-rose-700 dark:border-rose-300 dark:bg-rose-300 dark:text-rose-950 dark:hover:bg-rose-200 dark:focus-visible:ring-rose-300" aria-label="Supprimer Celeste">
                            <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5V20.25A1.5 1.5 0 0 0 7.5 21.75h9A1.5 1.5 0 0 0 18 20.25V7.5m-13.5 0h15m-10.5 0V5.25A1.5 1.5 0 0 1 10.5 3.75h3A1.5 1.5 0 0 1 15 5.25V7.5"/>
                            </svg>
                        </a>
                    </div>
                <?php } ?>
            </li>
        </ul>
    </main>
</div>
</body>
</html>
