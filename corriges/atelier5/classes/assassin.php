<?php

class Assassin extends Combatant {
    public $nom = "Assassin";
    public $vieMax = 4;
    public $invisible;

    public function attaquer(Combatant $adversaire): int {
        $this->invisible = rand(0, 1) == 1;
        return parent::attaquer($adversaire);
    }

    public function recevoirDegats(int $degats): void {
        if (!$this->invisible) {
            parent::recevoirDegats($degats);
        }
    }
}