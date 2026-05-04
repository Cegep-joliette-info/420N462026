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

## Exemple complet

```php
// Version non testable

class ArticleController extends BaseController {
    function store(): void {
        $titre   = $_POST['titre'];
        $contenu = $_POST['contenu'];

        $repo = new ArticleRepository();
        $repo->create($titre, $contenu);

        $_SESSION['message'] = 'Article créé!';

        header('Location: /articles');
        exit;
    }
}

// Version testable

class ArticleController extends BaseController {
    private ArticleRepository $repo;
    private MessageManager    $messages;
    private Request           $request;

    public function __construct(
        ArticleRepository $repo     = new ArticleRepository(),
        MessageManager    $messages = new MessageManager(),
        Request           $request  = new Request(),
    ) {
        $this->repo     = $repo;
        $this->messages = $messages;
        $this->request  = $request;
    }

    public function store(): void {
        $titre   = $this->request->post('titre');
        $contenu = $this->request->post('contenu');

        $this->repo->create($titre, $contenu);

        $this->messages->add('Article créé!');

        $this->redirect('/articles');
    }
}
```

### Test unitaire

```php
class ArticleControllerTest extends TestCase {
    public function testStoreCreeeUnArticleEtRedirige(): void {
        // 1. Mock du repository
        $mockRepo = $this->getMockBuilder(ArticleRepository::class)
            ->onlyMethods(['create'])
            ->getMock();

        $mockRepo->expects($this->once())
            ->method('create')
            ->with('Mon titre', 'Mon contenu');

        // 2. Mock du MessageManager
        $mockMessages = $this->getMockBuilder(MessageManager::class)
            ->onlyMethods(['add'])
            ->getMock();

        $mockMessages->expects($this->once())
            ->method('add')
            ->with('Article créé!');

        // 3. Mock de la Request
        $mockRequest = $this->getMockBuilder(Request::class)
            ->onlyMethods(['post'])
            ->getMock();

        $mockRequest->method('post')
            ->willReturnMap([
                ['titre',   'Mon titre'],
                ['contenu', 'Mon contenu'],
            ]);

        // 4. Mock du contrôleur pour intercepter redirect()
        $controller = $this->getMockBuilder(ArticleController::class)
            ->setConstructorArgs([$mockRepo, $mockMessages, $mockRequest])
            ->onlyMethods(['redirect'])
            ->getMock();

        $controller->expects($this->once())
            ->method('redirect')
            ->with('/articles');

        // 5. Appeler l'action
        $controller->store();
    }
}
```