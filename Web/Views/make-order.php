<?= $this->extend($base_view_folder . 'layout/body') ?>
<?= $this->section('content') ?>
<article>

    <header>
        <div class="myIn">
            <?= h1($titolo, 'shop_archive_title') ?>
        </div>
    </header>
</article>
<section class="shop_carrello">
    <div class="myIn">
        <div class="make-order-page">
            <?php if (isset($site_cart) && isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) { ?>


                <div class="make-order-page-content">

                    <div class="make-order-page-main">
                        <?php if ($app_user_data) { ?>
                        <?php } else { ?>
                            <div class="shop-login-form-cnt">
                                <?= view($base_view_folder . 'users/components/login-form') ?>
                            </div>
                            <div class="shop-login-altenative">
                                <?= view($base_view_folder . 'users/components/signup-login-alternative') ?>
                            </div>
                        <?php } ?>
                    </div>
                    <aside class="sidebar shop_sidebar">
                        <?= view($base_view_folder . 'shop/components/order-summary') ?>
                    </aside>
                </div>
                <div class="shop-action-tools-rows">
                    <a class="shop-action-tools-action" href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>"><?= appLabel('Torna al carrello', $app->labels, true)  ?></a>
                </div>
            <?php } else { ?>
                <h3 class="make-order-page-mess no_products">Non ci sono prodotti!</h3>
            <?php } ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {



    });
</script>

<?= $this->endSection() ?>