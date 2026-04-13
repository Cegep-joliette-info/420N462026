# Envoie de courriel

## Fonction `mail()`

PHP offre une fonction native `mail()` pour envoyer des courriels :

```php
mail('destinataire@example.com', 'Sujet du courriel', 'Corps du message');
```

Sur votre cPanel, cette fonction est disponible et fonctionne sans configuration supplémentaire. Par contre, les courriels envoyés ainsi **tombent souvent dans le dossier spam** du destinataire, car ils ne passent pas par un serveur SMTP authentifié et n'ont pas de signature DKIM/SPF.

## SMTP

Pour envoyer des courriels qui arrivent dans la boîte de réception, il faut passer par un serveur SMTP authentifié (Gmail, Outlook, un hébergeur Web, etc.).

### Avec PHPMailer

[PHPMailer](https://github.com/PHPMailer/PHPMailer) est la librairie la plus utilisée pour envoyer des courriels en PHP.

Installation via Composer (n'oubliez pas d'ajouter le dossier `vendor` à votre `.gitignore`): :

```bash
composer require phpmailer/phpmailer
```

Exemple avec un compte Gmail :

```php
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'votre.adresse@gmail.com';
$mail->Password   = 'votre_mot_de_passe_application';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

$mail->setFrom('votre.adresse@gmail.com', 'Nom expéditeur');
$mail->addAddress('destinataire@example.com', 'Nom destinataire');

$mail->Subject = 'Sujet du courriel';
$mail->Body    = 'Corps du message en texte brut';
$mail->AltBody = 'Corps du message en texte brut (fallback)';

$mail->send();
```

Pour Gmail, il faut générer un **mot de passe d'application** dans les paramètres du compte Google (pas votre mot de passe habituel).

Dans votre MVC avancé je ferais une classe `Mailer` dans le namespace/dossier `Utils` qui utilise PHPMailer.

## En développement — Mailtrap

En localhost, les courriels ne peuvent pas être envoyés vers de vraies adresses. On utilise un service qui intercepte les courriels envoyés et les affiche dans une interface web.

[Mailtrap](https://mailtrap.io) est le service le plus populaire. La version gratuite permet de tester l'envoi de courriels sans qu'ils arrivent à de vrais destinataires.

Une alternative gratuite et open source : **Mailpit** (remplaçant de Mailhog), qui s'installe localement.

Configuration de PHPMailer avec Mailtrap :

```php
$mail->isSMTP();
$mail->Host       = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth   = true;
$mail->Username   = 'votre_username_mailtrap';
$mail->Password   = 'votre_password_mailtrap';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 2525;
```

Les identifiants se trouvent dans votre projet Mailtrap sous **SMTP Settings**.

Il est conseillé de mettre ces valeurs dans votre fichier de configuration plutôt que directement dans le code, afin de pouvoir basculer facilement entre l'environnement de développement et la production.
