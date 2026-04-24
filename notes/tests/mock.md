# Éléments de remplacements et simulacres

Un test unitaire devrait tester une fonction précise sans dépendances. Votre fonction ne dois donc pas toucher au réseau, à la BD ou à d'autres classes. Alors comment tester ces fonctions? Pour le réseau et la BD on ne pourra jamais tester ces fonctions, il faut donc mettre tous les appels à la BD ou au réseaux dans des fonctions très simples qui ne feront rien d'autres. Ces fonction ne seront pas testés avec les tests unitaires. Par contre, la plupart des fonctions en utilisent d'autres, comment les tester? Il faut utiliser des simulacres (mock en anglais) et des éléments de remplacements (stubs en anglais).

Je me sert plus souvent des éléments de remplacements, qui servent à simuler l'appel à une fonction. Les simulacres servent à s'assurer que notre fonction en appel une autre.

Je veux tester la fonction Moyenne du code suivant:

```php
class Mathematique {
    function Somme(int $a, int $b) : int {
        return $a + $b;
    }

    function Moyenne(int $a, int $b) : int {
        return intdiv($this->Somme($a, $b), 2);
    }
}
```

Comme Moyenne utilise Somme, je dois créer un Stub qui va remplacer la méthode Somme dans mon test de la fonction Moyenne:

```php
public function testMoyenne(): void {
    $stub = $this->getMockBuilder(Mathematique::class)
        ->onlyMethods(['Somme'])
        ->getMock();
    $stub->method('Somme')->willReturn(4);
    $this->assertEquals(2, $stub->Moyenne(2, 2));
}
```

Explication des 5 lignes de la fonction:

 1. Préparer un objet basé sur la classe Mathématique;
 2. Ne "mock" que la fonction Somme;
 3. Créer le stub;
 4. Lorsque je vais appeler la fonction Somme, elle va me retourner la valeur 4;
 5. J'effectue le test (Affirmation);

De cette manière, si ma fonction Somme est brisé, mon test de moyenne va bien fonctionner.