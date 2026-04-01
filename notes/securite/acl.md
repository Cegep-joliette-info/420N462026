# Manquement dans l'ACL

En anglais: *Broken Access Control*

L'ACL (_Access Control List_) est une technique de sécurité qui permet de gérer facilement les droits d'accès.
Dans notre MVC avancé, chaque action de chaque contrôleur sera responsable de son contrôle d'accès.
Vous pouvez vous faire une classe qui regroupe toutes les possibilités d'accès, ce qui allège vos contrôleurs.
On pourra y trouver des fonctions comme _isGuest_, _isConnected_, _isAdmin_, _isMyPost_, etc.

Un problème souvent vu est d'utiliser la technique de "Sécurité par obfuscation" au lieu d'un ACL.
Cette technique consiste à se dire "si je cache le lien, l'utilisateur n'a plus accès à la page".
Par contre n'importe qui d'un peu débrouillard pourra trouver les liens intéressants en jouant dans la barre d'adresse.

Une autre erreur très courante est une ACL défaillante, qui protège par obfuscation.
Disons que nous avons un contrôleur _messageController_ avec une action _edit_.
Cette action vérifie si l'utilisateur est connecté.
Donc je clique sur le lien `/message/edit?id=42` ce qui m'affiche le formulaire de modification pour mon message.
Comme je suis un petit coquin, je modifie l'id qui est dans l'URL ce qui donne `/message/edit?id=1`.
Le contrôleur vérifie que je suis bien connecté et m'affiche le formulaire!
Par contre ce n'est pas mon message...
On a une ACL, mais elle est défaillante.

## Protection

Un bon contrôle d'accès doit couvrir trois niveaux :

- **Authentification** — identifier correctement l'utilisateur (est-il connecté ?).
- **Autorisation** — définir ce que l'utilisateur a le droit de faire selon son rôle (admin, utilisateur, invité...).
- **Vérification des permissions** — au moment de l'action, vérifier que l'utilisateur est bien autorisé à agir sur *cette ressource précise*.

C'est ce troisième niveau qui manquait dans l'exemple précédent. L'action `edit` doit vérifier non seulement que l'utilisateur est connecté, mais aussi que le message lui appartient :

```php
// ACL défaillante : vérifie seulement la connexion
public function isConnected(): bool
{
    return isset($_SESSION['user']);
}

// ACL correcte : vérifie aussi la propriété du message
public function isMyPost(int $postId): bool
{
    $post = $this->postModel->find($postId);
    return $post && $post->userId === $_SESSION['user']['id'];
}
```

### Principe de refus par défaut

Si aucune règle d'accès ne correspond explicitement, l'accès doit être **refusé**. On accorde les permissions une par une, plutôt que de tout autoriser et d'essayer de bloquer les cas problématiques.

Référence: https://www.hacksplaining.com/app/lessons/broken-access-control/prevention