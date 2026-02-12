<?php

class Clerc extends Combatant {
    public $nom = "Clerc";

    public $attaque = 4;
    public $defense = 4;

    public function attaquer(Combatant $adversaire): int {
        $this->vie = min($this->vieMax, $this->vie + 1);
        return parent::attaquer($adversaire);
    }
}