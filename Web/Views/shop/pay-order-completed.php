<?= $this->extend(customOrDefaultViewFragment('layout/body')) ?>
<?= $this->section('content') ?>
<article>
    <header>
        <div class="myIn">
            <?= h1(appLabel('Grazie', $app->labels, true)) ?>
        </div>
    </header>
</article>
<section class="lcshop-carrello">
    <div class="myIn lcshop-flex">
        <?php if (isset($riepilogo_order_data) && isset($orders_items) &&  is_iterable($orders_items) && count($orders_items) > 0) { ?>
            <?php if ($riepilogo_order_data->payment_status == 'COMPLETED') { ?>
                <div class="make-order-page">
                    <h4 class=""><?= appLabel('L\'ordine è stato completato con successo', $app->labels, true) ?></h4>
                    <p>
                        <?= appLabel('Controlla la tua email per visonare i dettagli.', $app->labels, true) ?>.<br />
                        <?= appLabel('Troverai il riepilogo del tuo oradine nella tua area riservata.', $app->labels, true) ?>
                    </p>
                    <p><?= appLabel('Non esitare a contattarci in caso di problemi o richieste.', $app->labels, true) ?></p>
                </div>
            <?php } else { ?>
                <div class="make-order-page">
                    <h4 class=""><?= appLabel('Qualcosa è andato storto', $app->labels, true) ?></h4>
                    <p><?= appLabel('Non esitare a contattarci in caso di problemi o richieste.', $app->labels, true) ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <?= view(customOrDefaultViewFragment('shop/components/no-products-message', 'LcShop')) ?>
        <?php } ?>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {



    });
</script>

<?= $this->endSection() ?>