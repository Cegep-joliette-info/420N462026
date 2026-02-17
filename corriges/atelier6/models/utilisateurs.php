<?php

namespace Models;

class Utilisateurs {
    public int $id;
    public string $nomUtilisateur;
    public string $motDePasse;

    /** @return self[] */
    public static function obtenirTout(\PDO $bd): array {
        return $bd->query('SELECT id, nom_utilisateur nomUtilisateur, mot_de_passe motDePasse FROM utilisateurs', PDO::FETCH_CLASS, self::class)->fetchAll();
    }

    public static function obtenirParNomUtilisateur(\PDO $bd, string $nomUtilisateur): ?Utilisateurs {
        $stmt = $bd->prepare('SELECT id, nom_utilisateur nomUtilisateur, mot_de_passe motDePasse FROM utilisateurs WHERE nom_utilisateur = ?');
        $stmt->execute([$nomUtilisateur]);
        return $stmt->fetchObject(self::class) ?: null;
    }
}