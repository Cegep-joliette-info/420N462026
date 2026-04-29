# Stripe en PHP — Mode développement

## Installation

```bash
composer require stripe/stripe-php

# Ou
docker compose exec php composer require stripe/stripe-php
```

---

## Configuration des clés API

Stripe fournit deux paires de clés : **test** (développement) et **live** (production).

```php
// config.php
define('STRIPE_SECRET_KEY', 'sk_test_VOTRE_CLE_SECRETE');
define('STRIPE_PUBLIC_KEY', 'pk_test_VOTRE_CLE_PUBLIQUE');
```

> Les clés de test se trouvent dans le [Dashboard Stripe](https://dashboard.stripe.com/test/apikeys) sous **Developers > API keys**.

---

## Initialisation

```php
require_once 'vendor/stripe/stripe-php/init.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
```

---

## Cartes de crédit de test

Ces numéros fonctionnent **uniquement avec les clés `sk_test_`**.

| Numéro de carte        | Réseau        | Résultat               |
|------------------------|---------------|------------------------|
| `4242 4242 4242 4242`  | Visa          | Paiement réussi        |
| `4000 0566 5566 5556`  | Visa (débit)  | Paiement réussi        |
| `5555 5555 5555 4444`  | Mastercard    | Paiement réussi        |
| `3782 8224 6310 005`   | Amex          | Paiement réussi        |
| `4000 0000 0000 9995`  | Visa          | Fonds insuffisants     |
| `4000 0000 0000 0002`  | Visa          | Carte refusée          |
| `4000 0025 0000 3155`  | Visa          | Authentification 3D Secure requise |
| `4000 0000 0000 3220`  | Visa          | 3D Secure — authentification échouée |

Pour **toutes** ces cartes :
- **Date d'expiration** : n'importe quelle date future (ex. `12/34`)
- **CVC** : n'importe quels 3 chiffres (ex. `123`)
- **Code postal** : n'importe lequel (ex. `J6E 4T1`)

---

## Créer un PaymentIntent

Un `PaymentIntent` représente l'intention de collecter un paiement.

```php
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount'   => 2000,       // montant en cents (20,00 $CAD)
        'currency' => 'cad',
        'payment_method_types' => ['card'],
    ]);

    echo $paymentIntent->client_secret; // à transmettre au frontend
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Erreur Stripe : ' . $e->getMessage();
}
```

---

## Confirmer un paiement côté serveur (sans frontend)

Utile pour tester rapidement sans interface JS.

```php
// 1. Créer un PaymentMethod avec une carte de test
$paymentMethod = \Stripe\PaymentMethod::create([
    'type' => 'card',
    'card' => [
        'number'    => '4242424242424242',
        'exp_month' => 12,
        'exp_year'  => 2034,
        'cvc'       => '123',
    ],
]);

// 2. Créer et confirmer le PaymentIntent en une seule étape
$paymentIntent = \Stripe\PaymentIntent::create([
    'amount'               => 2000,
    'currency'             => 'cad',
    'payment_method'       => $paymentMethod->id,
    'confirm'              => true,
    'return_url'           => 'https://exemple.com/retour',
]);

echo 'Statut : ' . $paymentIntent->status; // "succeeded"
```

### Ce qui se passe en arrière-plan

En mode test, il n'y a pas de vérification 3DS, donc le return_url est ignoré. Mais en mode production, voici le flux complet :

1. **Ton serveur → Stripe** : tu envoies la demande de paiement avec `confirm: true`
2. **Stripe → Visa/Mastercard** : Stripe communique avec le réseau de la carte
3. **Visa demande une vérification 3DS** : Stripe le signale dans la réponse (`status: requires_action`)
4. **L'utilisateur est redirigé** vers une page de **sa banque** pour entrer un code SMS ou approuver via son app bancaire
5. **Après vérification** : la banque redirige vers Stripe (en coulisse), puis Stripe redirige vers ton `return_url`

Le `return_url` est obligatoire avec `confirm: true`, même pour des cartes qui ne déclenchent pas de 3DS — Stripe en a besoin au cas où la banque l'exige.

### Paramètres ajoutés au return_url

Stripe ajoute automatiquement ces paramètres à ton URL de retour :

| Paramètre | Description |
|---|---|
| `payment_intent` | L'ID du PaymentIntent (ex. `pi_xxx`) |
| `payment_intent_client_secret` | Le secret client du PaymentIntent |
| `redirect_status` | `succeeded`, `failed`, ou `canceled` |

Exemple d'URL reçue :
```
https://exemple.com/retour?payment_intent=pi_xxx&payment_intent_client_secret=pi_xxx_secret_xxx&redirect_status=succeeded
```

Côté PHP, tu vérifies le vrai statut ainsi :

```php
$paymentIntentId = $_GET['payment_intent'];
$paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

if ($paymentIntent->status === 'succeeded') {
    echo 'Paiement confirmé';
} else {
    echo 'Paiement échoué : ' . $paymentIntent->status;
}
```

> Ne pas faire confiance uniquement à `redirect_status` dans l'URL — il peut être falsifié. Toujours récupérer le PaymentIntent depuis l'API Stripe pour confirmer le vrai statut.

---

## Récupérer un paiement existant

```php
$paymentIntent = \Stripe\PaymentIntent::retrieve('pi_IDENTIFIANT');
echo $paymentIntent->status;
```

---

## Créer un remboursement

```php
$refund = \Stripe\Refund::create([
    'payment_intent' => 'pi_IDENTIFIANT',
    // 'amount' => 1000, // optionnel : remboursement partiel en cents
]);

echo 'Remboursement : ' . $refund->status; // "succeeded"
```

---

## Gestion des erreurs

```php
try {
    // appel Stripe...
} catch (\Stripe\Exception\CardException $e) {
    // Carte refusée
    echo 'Carte refusée : ' . $e->getError()->message;
} catch (\Stripe\Exception\RateLimitException $e) {
    echo 'Trop de requêtes.';
} catch (\Stripe\Exception\InvalidRequestException $e) {
    echo 'Paramètre invalide : ' . $e->getMessage();
} catch (\Stripe\Exception\AuthenticationException $e) {
    echo 'Clé API invalide.';
} catch (\Stripe\Exception\ApiConnectionException $e) {
    echo 'Erreur réseau.';
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Erreur générique Stripe : ' . $e->getMessage();
}
```

---

## Vérifier qu'on est bien en mode test

```php
$account = \Stripe\Account::retrieve();
if ($account->settings->dashboard->display_name !== null) {
    // Vérifier la clé utilisée
    $isTestMode = str_starts_with(STRIPE_SECRET_KEY, 'sk_test_');
    echo $isTestMode ? 'Mode TEST' : 'Mode PRODUCTION — attention !';
}
```

---

## Ressources utiles

- Dashboard de test : `https://dashboard.stripe.com/test/payments`
- Toutes les cartes de test : `https://stripe.com/docs/testing#cards`
- Référence API PHP : `https://stripe.com/docs/api?lang=php`
