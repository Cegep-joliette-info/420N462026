<?php

class Barbare extends Combatant {
    public $nom = "Barbare";

    public function roulerAttaque(): int {
        return $this->rouler($this->attaque, 4);
    }

    public function roulerDefense(): int {
        return $this->rouler($this->defense, 8);
    }
}