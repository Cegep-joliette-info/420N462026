<?php

abstract class Combatant {
    public $nom = '';
    public $vieMax = 10;
    public $vie;
    public $attaque = 5;
    public $defense = 5;

    public function __construct() {
        $this->vie = $this->vieMax;
    }

    public function attaquer(Combatant $adversaire): int {
        $attaque = $this->roulerAttaque();
        $defense = $adversaire->roulerDefense();
        $degats = max(0, $attaque - $defense);
        $adversaire->recevoirDegats($degats);
        return $degats;
    }

    public function recevoirDegats(int $degats): void {
        $this->vie = max(0, $this->vie - $degats);
    }

    public function roulerAttaque(): int {
        return $this->rouler($this->attaque);
    }

    public function roulerDefense(): int {
        return $this->rouler($this->defense);
    }

    public function rouler(int $stats, int $limite = 6): int {
        $success = 0;
        for ($i = 0; $i < $stats; $i++) {
            if (rand(1, 10) >= $limite) {
                $success++;
            }
        }
        return $success;
    }
}