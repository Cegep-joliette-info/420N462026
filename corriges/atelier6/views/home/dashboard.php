<?php
/** @var $succes string|null */
?>
<?php require __DIR__ . '/../layout/_header.php'; ?>
<!-- Prompt: Utilise le _header et le _footer, fait un écran nommé "Tableau de bord" qui contient une image provenant de https://picsum.photos/200/300, un graphique et un bouton déconnexion -->

<header class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Tableau de bord</h1>
</header>

<?php if ($succes) { ?>
    <div class="mb-6 rounded-xl border border-green-400 bg-green-50 p-4 dark:border-green-700 dark:bg-green-900">
        <p class="font-semibold text-green-700 dark:text-green-200"><em><?= $succes ?></em></p>
    </div>
<?php } ?>

<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
        <img src="https://picsum.photos/200/300" alt="" class="h-auto w-full max-w-[200px] rounded-xl object-cover" loading="lazy">

        <div class="flex-1">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Statistiques</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Activite recente (7 jours)</p>

            <div class="mt-4">
                <svg role="img" aria-labelledby="chart-title chart-desc" viewBox="0 0 320 160" class="h-40 w-full">
                    <title id="chart-title">Graphique des activites</title>
                    <desc id="chart-desc">Barres representant 7 jours d activite.</desc>
                    <rect x="0" y="0" width="320" height="160" rx="12" class="fill-slate-100 dark:fill-slate-800" />
                    <g class="fill-slate-700 dark:fill-slate-200">
                        <rect x="24" y="90" width="24" height="46" rx="4" />
                        <rect x="64" y="70" width="24" height="66" rx="4" />
                        <rect x="104" y="48" width="24" height="88" rx="4" />
                        <rect x="144" y="62" width="24" height="74" rx="4" />
                        <rect x="184" y="30" width="24" height="106" rx="4" />
                        <rect x="224" y="58" width="24" height="78" rx="4" />
                        <rect x="264" y="80" width="24" height="56" rx="4" />
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="../auth/doLogout.php" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white dark:focus-visible:ring-slate-400 dark:focus-visible:ring-offset-slate-900">
            Deconnexion
        </a>
    </div>
</section>

<?php require __DIR__ . '/../layout/_footer.php'; ?>
