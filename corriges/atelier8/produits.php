<?php
$produits = [
    new Produit(8, 'Manette sans fil', 59.99),
    new Produit(12, 'Casque gaming', 89.99),
    new Produit(14, 'Tapis de souris XXL', 29.99)
];

class Produit {
    public function __construct(
        public int $id,
        public string $nom,
        public float $prix
    ) {}

    public function getPrixAffichage(): string {
        return number_format($this->prix, 2, ',');
    }
}