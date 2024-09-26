<?= $this->extend(customOrDefaultViewFragment('layout/body')) ?>
<?= $this->section('content') ?>
<article>
    <?= view(customOrDefaultViewFragment('shop/components/breadcrumb', 'LcShop')) ?>
    <section class="lcshop-header">
        <div class="myIn">
            <hgroup>
                <?= h1($titolo) ?>
            </hgroup>
        </div>
    </section>
</article>

<section class="shop_carrello">
    <div class="myIn lcshop-flex">
        <div class="make-order-page">
            <?php if (isset($site_cart) && isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) { ?>
                <div class="make-order-page-content">
                    <?php if ($app_user_data) { ?>
                        <?= getServerMessage() ?>
                        <div class="make-order-page-main">
                            <form method="POST" class="form_spedizione_cnt">
                                <header class="sped_type">
                                    <h4><?= appLabel('Spedizione', $app->labels, true) ?></h4>
                                    <h5><?= appLabel('Inserisci i dati di spedizione del tuo ordine', $app->labels, true) ?></h5>
                                </header>
                                <div class="form_spedizione">
                                    <?= user_mess($ui_mess, $ui_mess_type) ?>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Nome', $app->labels, true) ?></label>
                                            <input type="text" name="ship_name" value="<?= getReq('ship_name') ?: $order_data->ship_name ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Cognome', $app->labels, true) ?></label>
                                            <input type="text" name="ship_surname" value="<?= getReq('ship_surname') ?: $order_data->ship_surname ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Email', $app->labels, true) ?></label>
                                            <input type="email" name="ship_email" value="<?= getReq('ship_email') ?: $order_data->ship_email ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Telefono', $app->labels, true) ?></label>
                                            <input type="tel" name="ship_phone" value="<?= getReq('ship_phone') ?: $order_data->ship_phone ?>" placeholder="<?= appLabel('Per eventuali comunicazioni dal corriere', $app->labels, true) ?>" />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Stato', $app->labels, true) ?></label>
                                            <input type="text" name="ship_country" value="<?= getReq('ship_country') ?: $order_data->ship_country ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Provincia', $app->labels, true) ?></label>
                                            <input type="text" name="ship_district" value="<?= getReq('ship_district') ?: $order_data->ship_district ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field form-field-2-3">
                                            <label><?= appLabel('Località', $app->labels, true) ?></label>
                                            <input type="text" name="ship_city" value="<?= getReq('ship_city') ?: $order_data->ship_city ?>" />
                                        </div>
                                        <div class="form-field form-field-1-3">
                                            <label><?= appLabel('Cap', $app->labels, true) ?></label>
                                            <input type="text" name="ship_zip" value="<?= getReq('ship_zip') ?: $order_data->ship_zip ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field form-field-3-4">
                                            <label><?= appLabel('Via/Piazza', $app->labels, true) ?></label>
                                            <input type="text" name="ship_address" value="<?= getReq('ship_address') ?: $order_data->ship_address ?>" />
                                        </div>
                                        <div class="form-field form-field-1-4">
                                            <label><?= appLabel('Civico', $app->labels, true) ?></label>
                                            <input type="text" name="ship_address_number" value="<?= getReq('ship_address_number') ?: $order_data->ship_address_number ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Altre Info', $app->labels, true) ?></label>
                                            <input type="text" name="ship_infos" value="<?= getReq('ship_infos') ?: $order_data->ship_infos ?>" placeholder="<?= appLabel('Interno, scala, citofono', $app->labels, true) ?>..." />
                                        </div>
                                    </div>
                                    <?php /*
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label class="check-label">
                                                <input type="checkbox" class="check-input" name="save_in_user" value="salva" <?= ($request->getPost('save_in_user')) ? 'checked' : '' ?> />
                                                Salva i dati per il prossimo ordine
                                            </label>
                                        </div>
                                    </div>
                                    
                                    */ ?>
                                </div>
                                <div class="shop-action-tools-rows">
                                    <a class="shop-action-tools-action  shop-action-tools-action-prev" href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>"><?= appLabel('Torna al carrello', $app->labels, true) ?></a>
                                    <button type="submit" name="ship_send" value="next" class="shop-action-tools-action shop-action-tools-action-next"><?= appLabel('Fatturazione', $app->labels, true) ?> </button>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <div class="make-order-page-main">
                            <div class="shop-login-form-cnt">
                                <?= view(customOrDefaultViewFragment('users/components/login-form', 'LcUsers')) ?>
                            </div>
                            <div class="shop-login-altenative">
                                <?= view(customOrDefaultViewFragment('users/components/signup-login-alternative', 'LcUsers')) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <?= view(customOrDefaultViewFragment('shop/components/no-products-message', 'LcShop')) ?>
            <?php } ?>
        </div>
        <?php /*
        <aside class="sidebar shop_sidebar">
            <?= view(customOrDefaultViewFragment('shop/components/order-summary', 'LcShop')) ?>
        </aside>
        */ ?>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {



    });
</script>

<?= $this->endSection() ?>