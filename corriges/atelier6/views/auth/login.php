<?php
/** @var $error string|null */
?>
<?php require __DIR__ . '/../layout/_header.php'; ?>

<header class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Atelier 6</h1>
</header>

<?php if ($error) { ?>
    <div class="mb-6 rounded-xl border border-red-400 bg-red-50 p-4 dark:border-red-700 dark:bg-red-900">
        <p class="font-semibold text-red-700 dark:text-red-200"><em><?= $error ?></em></p>
    </div>
<?php } ?>

<form class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" action="controllers/auth/doLogin.php" method="post">
    <div class="space-y-5">
        <div>
            <label for="username" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Nom d'utilisateur
            </label>
            <input type="text" id="username" name="username" class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 hover:border-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:hover:border-slate-600 dark:focus:border-slate-400 dark:focus:ring-slate-400 dark:focus:ring-offset-slate-900" autocomplete="username">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Mot de passe
            </label>
            <input type="password" id="password" name="password" class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm transition placeholder:text-slate-400 hover:border-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:hover:border-slate-600 dark:focus:border-slate-400 dark:focus:ring-slate-400 dark:focus:ring-offset-slate-900" autocomplete="current-password">
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white dark:focus-visible:ring-slate-400 dark:focus-visible:ring-offset-slate-900">
            Connexion
        </button>
    </div>
</form>

<?php require __DIR__ . '/../layout/_footer.php'; ?>
