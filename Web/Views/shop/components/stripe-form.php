<div id="payment-form-cnt">
    <h5><?= appLabel('Paga ora con carta di credito', $app->labels, true) ?></h5>
    <h6><?= appLabel('Evasione dell\'ordine immediata', $app->labels, true) ?></h6>
    <form id="payment-form" class="stripe_form">
        <div class="group payment_form_field_cnt carta_nome_cnt">
            <label for="card_owner"><?= appLabel('Nome', $app->labels, true) ?> <?= appLabel('Cognome', $app->labels, true) ?></label>
            <input class="input_classic" type="text" id="card_owner" value="" required />
        </div>
        <div class="group payment_form_field_cnt carta_cnt">
            <label for="card-element"><?= appLabel('Carta di credito', $app->labels, true) ?></label>
            <div id="card-element"></div>
            <!-- Used to display form errors. -->
            <div id="card-errors" class="stripe_pay_mess" role="alert"></div>

        </div>
        <div class="payment_form_field_cnt send_payment_cnt center no_border">
            <div id="card-message" class="stripe_pay_mess"></div>
            <button class="btn_carta" id="card-button" data-secret="<?= $stripeOB->intent->client_secret ?>"><?= appLabel('Paga Ora', $app->labels, true) ?> € <?= $riepilogo_order_data->pay_total_formatted  ?></button>
            <div class="pay-type-mess"><?= appLabel('Utilizziamo i server sicuri di Stripe', $app->labels, true) ?></div>
        </div>
    </form>
</div>
<style>
    #payment-form-cnt {
        width: 100%;
        max-width: 400px;
        margin: 0;
        border: 1px solid #e6e6e6;
        background-color: #f5f5f5;
        padding: 1rem;
        border-radius: 1rem;
    }

    .stripe_form label {
        color: #8898AA;
        font-weight: 300;
        height: 40px;
        line-height: 40px;
        display: flex;
        flex-direction: row;
    }

    .stripe_form input.input_classic {
        color: #111;
        background: #FFF;
        font-size: 1rem;
        line-height: 1rem;
        padding: .5rem;
        margin: 0 0 5px 0;
        border-radius: 1rem;
        border: 1px solid #e6e6e6;
        box-sizing: border-box;
        width: 100%;
    }

    #card-element {
        color: #111;
        background: #FFF;
        font-size: 1rem;
        line-height: 1rem;
        padding: .5rem;
        margin: 0 0 5px 0;
        border-radius: 1rem;
        border: 1px solid #e6e6e6;
        box-sizing: border-box;
        width: 100%;
    }

    .stripe_form button {
        display: block;
        background: #666EE8;
        color: white;
        box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
        border-radius: 4px;
        border: 0;
        margin-top: 20px;
        font-size: 15px;
        font-weight: 400;
        width: 100%;
        /* height: 40px; */
        line-height: 38px;
        outline: none;
        font-weight: 700;
    }

    .stripe_pay_mess {
        color: #C00;
        padding: .5rem 0 .2rem 0;
        display: block;
        text-align: center;
        font-size: .875rem;
    }

    .pay_mess_ok {
        color: green;
        font-size: 1.5em;
        text-align: center;
    }
    .pay-type-mess {
        color: #8898AA;
        padding-top: .5rem;
        font-size: .875rem;
        text-align: center;
    }
</style>

<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var stripe = Stripe('<?= env("custom.stripe_public_key") ?>');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');
    // Submit the payment to Stripe from the client
    var card_owner = document.getElementById('card_owner');
    var cardButton = document.getElementById('card-button');
    var cardMessage = document.getElementById('card-message');
    var cardErrors = document.getElementById('card-errors');

    cardButton.addEventListener('click', function(ev) {
        ev.preventDefault();
        cardMessage.textContent = "";
        cardErrors.textContent = "";

        if (card_owner.value != '') {
            cardMessage.textContent = "Connessione in corso";
            stripe.handleCardPayment('<?= $stripeOB->intent->client_secret ?>', cardElement, {
                payment_method_data: {
                    billing_details: {
                        name: card_owner.value
                    }
                }
            }).then(function(result) {
                cardMessage.textContent = "";
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var stripe_container = document.getElementById('stripe_container');
                    stripe_container.innerHTML = '<h3 class="pay_mess_ok"><?= appLabel("Pagamento avvenuto con successo", $app->labels, true) ?></h3>';
                    setTimeout(function() {
                        window.location.replace('<?= $stripeOB->ok_pay_page ?>');
                    }, 2000);
                    // Send the token to your server
                }
            });
        } else {
            cardMessage.textContent = "<?= appLabel('Inserisci il nome del titolare della carta', $app->labels, true) ?>";
        }
    });
</script>