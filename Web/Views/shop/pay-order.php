<?= $this->extend(customOrDefaultViewFragment('layout/body')) ?>
<?= $this->section('content') ?>
<article>
    <header>
        <div class="myIn">
            <?= h1($titolo, 'shop_archive_title') ?>
        </div>
    </header>
</article>
<section class="lcshop-carrello">
    <div class="myIn lcshop-flex">
        <?php if (isset($riepilogo_order_data) && isset($orders_items) &&  is_iterable($orders_items) && count($orders_items) > 0) { ?>
            <?php if ($riepilogo_order_data->payment_status == 'COMPLETED') { ?>
                <div class="make-order-page">
                    <h4 class="sign_up-tit"><?= appLabel('L\'ordine risulta già pagato', $app->labels, true) ?></h4>
                </div>
            <?php } else { ?>
                <div class="make-order-page">
                    <div class="make-order-page-content">

                        <?php if ($app_user_data) { ?>
                            <div class="corsi-registrati-completo stripe_container" id="stripe_container">
                                <?= getServerMessage() ?>
                                <?php if (trim(env("custom.stripe_public_key")) && trim(env("custom.stripe_secret_key")) && isset($stripeOB) && $stripeOB) { ?>
                                    <?= view(customOrDefaultViewFragment('shop/components/stripe-form', 'LcShop')) ?>
                                <?php  } ?>
                            </div>
                        <?php } else { ?>
                            <div class="myIn lcshop-flex">
                                <?= view(customOrDefaultViewFragment('shop/components/user-required', 'LcShop')) ?>
                            </div>
                        <?php } ?>
                    </div>
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