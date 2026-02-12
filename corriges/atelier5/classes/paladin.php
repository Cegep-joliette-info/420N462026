<?php

class Paladin extends Combatant {
    public $nom = "Paladin";
    public $attaque = 3;

    public function recevoirDegats(int $degats): void {
        $this->vie = max(0, floor(($this->vie - $degats) / 2));
    }
}