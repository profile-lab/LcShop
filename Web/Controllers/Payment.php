<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test_...');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_19f5f6b3bcee7ac3b2fa968931e5a37a86e15c1257227e5a2fa24925819c20d2';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'checkout.session.async_payment_failed':
    $session = $event->data->object;
  case 'checkout.session.async_payment_succeeded':
    $session = $event->data->object;
  case 'checkout.session.completed':
    $session = $event->data->object;
  case 'checkout.session.expired':
    $session = $event->data->object;
  case 'payment_intent.amount_capturable_updated':
    $paymentIntent = $event->data->object;
  case 'payment_intent.canceled':
    $paymentIntent = $event->data->object;
  case 'payment_intent.created':
    $paymentIntent = $event->data->object;
  case 'payment_intent.partially_funded':
    $paymentIntent = $event->data->object;
  case 'payment_intent.payment_failed':
    $paymentIntent = $event->data->object;
  case 'payment_intent.processing':
    $paymentIntent = $event->data->object;
  case 'payment_intent.requires_action':
    $paymentIntent = $event->data->object;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
  case 'payout.canceled':
    $payout = $event->data->object;
  case 'payout.created':
    $payout = $event->data->object;
  case 'payout.failed':
    $payout = $event->data->object;
  case 'payout.paid':
    $payout = $event->data->object;
  case 'payout.reconciliation_completed':
    $payout = $event->data->object;
  case 'payout.updated':
    $payout = $event->data->object;
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);