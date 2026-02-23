<?php
// L'atelier stipule du HTML pure, mais j'ai préféré partager les infos de produits entre les 2 pages, ce n'est pas nécessaire
include 'produits.php';
/** @var $produits Produit[] */
?>
<!-- Crée une page HTML séparé qui affiche le panier (3 produits avec chacun un prix différent, une quantité et des boutons + et -), et le total incluant: le nombre de produits, le sous-total, tps, tvq et le total -->
<!-- Ajuste la page pour mettre les 2 sections côtes à côtes sur un écran large et met le nombre de produits dans un "badge" au lieu de l'avoir dans le tableau -->
<!-- Met le badge dans la boite "Résumé de la commande", à droite complètement à côté du titre -->
<!DOCTYPE html>
<html lang="fr" class="dark:bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
<main class="container mx-auto max-w-6xl px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-green-700 dark:text-green-400">Mon Panier</h1>
    <div id="error-message" class="mb-6 hidden rounded-lg border border-red-300 bg-red-100 p-4 text-red-700 dark:border-red-600 dark:bg-red-900 dark:text-red-200" role="alert" aria-live="assertive"></div>
    <div class="flex flex-col gap-8 lg:flex-row">
    <section aria-labelledby="cart-items-heading" class="flex-1">
        <h2 id="cart-items-heading" class="sr-only">Articles du panier</h2>
        <ul class="divide-y divide-slate-300 rounded-lg border border-slate-300 bg-white shadow-sm dark:divide-slate-600 dark:border-slate-600 dark:bg-slate-800">
            <?php foreach ($produits as $produit) { ?>
                <li class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-slate-900 dark:text-slate-100"><?= $produit->nom ?></p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Prix unitaire: <?= $produit->getPrixAffichage() ?> $</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button data-action="-" data-id="<?= $produit->id ?>" type="button" aria-label="Diminuer la quantité de Manette sans fil" class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-md bg-slate-200 text-slate-700 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">-</button>
                        <span id="qte-<?= $produit->id ?>" class="w-8 text-center font-medium" aria-label="Quantité">-</span>
                        <button data-action="+" data-id="<?= $produit->id ?>" type="button" aria-label="Augmenter la quantité de Manette sans fil" class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-md bg-slate-200 text-slate-700 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">+</button>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </section>
    <section aria-labelledby="cart-summary-heading" class="h-fit rounded-lg border border-slate-300 bg-white p-6 shadow-sm dark:border-slate-600 dark:bg-slate-800 lg:w-80">
        <div class="mb-4 flex items-center justify-between">
            <h2 id="cart-summary-heading" class="text-xl font-bold text-slate-900 dark:text-slate-100">Résumé</h2>
            <span id="qte" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white dark:bg-green-500">-</span>
        </div>
        <dl class="space-y-2">
            <div class="flex justify-between">
                <dt class="text-slate-600 dark:text-slate-400">Sous-total</dt>
                <dd id="sous-total" class="font-medium text-slate-900 dark:text-slate-100">- $</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-600 dark:text-slate-400">TPS (5%)</dt>
                <dd id="tps" class="font-medium text-slate-900 dark:text-slate-100">- $</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-600 dark:text-slate-400">TVQ (9,975%)</dt>
                <dd id="tvq" class="font-medium text-slate-900 dark:text-slate-100">- $</dd>
            </div>
            <div class="flex justify-between border-t border-slate-300 pt-4 dark:border-slate-600">
                <dt class="text-lg font-bold text-slate-900 dark:text-slate-100">Total</dt>
                <dd id="total" class="text-lg font-bold text-green-700 dark:text-green-400">- $</dd>
            </div>
        </dl>
    </section>
    </div>
</main>
<!-- Le paramètre t permet de désactiver la cache -->
<script src="cart.js?t=<?= time() ?>"></script>
</body>
</html>
