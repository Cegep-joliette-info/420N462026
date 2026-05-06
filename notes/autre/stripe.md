# Stripe en PHP

## Installation

```bash
composer require stripe/stripe-php

# Ou avec Docker
docker compose exec php composer require stripe/stripe-php
```

---

## Configuration et initialisation

Stripe fournit deux paires de clés : **test** (développement) et **live** (production). Les clés de test se trouvent dans le [Dashboard Stripe](https://dashboard.stripe.com/test/apikeys) sous **Developers > API keys**.

La clé publique (`pk_test_`) est utilisée dans le frontend (JavaScript) pour tokeniser les cartes, tandis que la clé secrète (`sk_test_`) est utilisée dans le backend (PHP) pour créer des paiements.

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

---

## Flux de paiement complet

Les numéros de carte ne doivent **jamais** transiter par ton serveur — c'est une exigence PCI DSS. Stripe fournit **Stripe.js** pour tokeniser la carte directement dans le navigateur.

```
Navigateur → Stripe (via Stripe.js) → retourne un paymentMethod.id
                                                    ↓
                               Ton serveur PHP reçoit seulement l'ID
```

### Étape 1 — Frontend (HTML + JS)

```html
<!-- Charger Stripe.js depuis les serveurs de Stripe -->
<script src="https://js.stripe.com/v3/"></script>

<form id="formulaire-paiement">
    <!-- Stripe injecte un iframe sécurisé dans ce div -->
    <div id="card-element"></div>
    <button type="submit">Payer</button>
    <p id="erreur"></p>
</form>

<script>
const stripe = Stripe('pk_test_VOTRE_CLE_PUBLIQUE');

// Créer le champ de carte (iframe géré par Stripe)
// hidePostalCode: true évite le champ ZIP américain (incompatible avec les codes postaux canadiens)
const elements = stripe.elements();
const card = elements.create('card', { hidePostalCode: true });
card.mount('#card-element');

document.getElementById('formulaire-paiement').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Stripe envoie les données directement à ses serveurs — ton serveur ne voit rien
    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: card,
    });

    if (error) {
        document.getElementById('erreur').textContent = error.message;
        return;
    }

    // Envoyer seulement l'ID au backend
    const response = await fetch('/payer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_method_id: paymentMethod.id }),
    });

    const data = await response.json();

    if (data.status === 'succeeded') {
        alert('Paiement réussi !');
    } else {
        alert('Erreur : ' + data.status);
    }
});
</script>
```

### Étape 2 — Backend (payer.php)

```php
// Cet API devrait être dans une action/controlleur sécurisé, pas juste un script public
require_once 'vendor/stripe/stripe-php/init.php';

define('STRIPE_SECRET_KEY', 'sk_test_VOTRE_CLE_SECRETE'); // pourrait être défini dans un .env ou config.php

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$data = json_decode(file_get_contents('php://input'), true);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount'         => 2000,           // en cents (20,00 $CAD)
        'currency'       => 'cad',
        'payment_method' => $data['payment_method_id'],
        'confirm'        => true,
        'return_url'     => 'https://exemple.com/retour',
    ]);
    // Sauvegarder dans la base de données et ajouter aux logs

    echo json_encode(['status' => $paymentIntent->status]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
```

### Étape 3 — Vérifier le statut après redirection 3DS

Certaines cartes déclenchent une vérification 3D Secure : l'acheteur est redirigé vers sa banque, puis Stripe redirige vers ton `return_url` avec ces paramètres :

| Paramètre | Description |
|---|---|
| `payment_intent` | L'ID du PaymentIntent (ex. `pi_xxx`) |
| `payment_intent_client_secret` | Le secret client |
| `redirect_status` | `succeeded`, `failed`, ou `canceled` |

```php
// retour.php
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

## Ressources utiles

- Dashboard de test : `https://dashboard.stripe.com/test/payments`
- Toutes les cartes de test : `https://stripe.com/docs/testing#cards`
- Référence API PHP : `https://stripe.com/docs/api?lang=php`
