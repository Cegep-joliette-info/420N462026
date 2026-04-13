# Escalade de privilèges

En anglais: _privilege escalation_.

Cette faille existe seulement si le programmeur fait quelque chose de vraiment mal.

Par exemple, le site doit gérer le status de l'utilisateur (anonyme, connecté ou admin), il enregistre donc ce status dans un cookie.
Le pirate remarque ça, change son status pour admin et bingo! Il est admin.

Ce type de données doit être emmagasiné dans la session.
En limitant le nombre de données chez le client, vous limitez les failles possibles.
Utilisez donc tout le temps la session.

Si vous avez besoin de cette information en JavaScript, vous pouvez la mettre dans un cookie, mais PHP doit toujours se baser sur la donnée en session.

Exemple de données problématiques:
* Rôle de l'utilisateur;
* ID de l'utilisateur;
* Temps avant une déconnexion;
* etc.

## Sécuriser un cookie

Si vous devez absolument stocker des données sensibles dans un cookie, on peut le sécuriser selon la méthode du JWT (JSON Web Token).

Une version du JWT est en 2 parties: le _payload_ et la _signature_.
Le payload contient les données, par exemple le rôle de l'utilisateur, et la signature est un hash du payload avec un secret.
Lorsque le serveur reçoit le cookie, il peut vérifier que la signature est valide pour le payload, ce qui garantit que les données n'ont pas été modifiées par le client.

Avoir un hash garantis que les données n'ont pas été modifiées, mais elles sont toujours visibles par le client.

Par exemple:

```php
$secret = bin2hex(random_bytes(12)); // Le générer juste une fois et le stocker dans un fichier de configuration sécurisé ou dans la session

$payload = json_encode(['role' => 'admin']);
$signature = hash_hmac('sha256', $payload, $secret);
$cookie_value = base64_encode($payload) . '.' . $signature;
```

On peut aussi chiffrer les informations du cookie au lieu de les hasher.

L'intégrité n'est pas garantie, mais les données ne sont pas visibles par le client.

Par exemple:

```php
$secret = bin2hex(random_bytes(12)); // Le générer juste une fois et le stocker dans un fichier de configuration sécurisé ou dans la session

$iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
$payload = json_encode(['role' => 'admin']);
$encrypted_payload = openssl_encrypt($payload, 'aes-256-cbc', $secret, 0, $iv);
$cookie_value = base64_encode($encrypted_payload) . '.' . base64_encode($iv);
```

Référence: https://www.hacksplaining.com/app/lessons/privilege-escalation/prevention