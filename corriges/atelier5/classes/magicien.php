<?php

class Magicien extends Combatant {
    public $nom = "Magicien";
    public $vieMax = 5;
    public $defense = 3;

    public function attaquer(Combatant $adversaire): int {
        $attaque = $this->roulerAttaque();
        $defense = $adversaire->roulerDefense();
        $degats = $attaque - $defense > 0 ? $attaque : 0;
        $adversaire->recevoirDegats($degats);
        return $degats;
    }
}