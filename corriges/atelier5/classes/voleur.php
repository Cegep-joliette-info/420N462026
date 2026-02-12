<?php

class Voleur extends Combatant {
    public $nom = "Voleur";
    public $vieMax = 6;

    function roulerAttaque(): int {
        $success = 0;
        for ($i = 0; $i < $this->attaque; $i++) {
            $jet = rand(1, 10);
            if ($jet == 10) {
                $success += 2;
            }
            elseif ($jet >= 6) {
                $success++;
            }
        }
        return $success;    }
}