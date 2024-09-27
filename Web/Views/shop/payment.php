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
        <div class="make-order-page">
            <?php if (isset($site_cart) && isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) { ?>
                <div class="make-order-page-content">
                    <?php if ($app_user_data) { ?>
                        <?= getServerMessage() ?>
                        <div class="make-order-page-main">
                            <form method="POST" class="form_spedizione_cnt">
                                <header class="sped_type">
                                    <h4><?= appLabel('Fatturazione', $app->labels, true) ?></h4>
                                    <h5><?= appLabel('Inserisci i dati di fatturazione del tuo ordine', $app->labels, true) ?></h5>
                                </header>
                                <div class="form_spedizione">
                                    <?= user_mess($ui_mess, $ui_mess_type) ?>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Nome', $app->labels, true) ?></label>
                                            <input type="text" name="pay_name" value="<?= getReq('pay_name') ?: $order_data->pay_name ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Cognome', $app->labels, true) ?></label>
                                            <input type="text" name="pay_surname" value="<?= getReq('pay_surname') ?: $order_data->pay_surname ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Ragione sociale', $app->labels, true) ?></label>
                                            <input type="text" name="pay_fiscal" value="<?= getReq('pay_fiscal') ?: $order_data->pay_fiscal ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Partita iva / Codice Fiscale', $app->labels, true) ?></label>
                                            <input type="text" name="pay_vat" value="<?= getReq('pay_vat') ?: $order_data->pay_vat ?>" />
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Email', $app->labels, true) ?></label>
                                            <input type="email" name="pay_email" value="<?= getReq('pay_email') ?: $order_data->pay_email ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Telefono', $app->labels, true) ?></label>
                                            <input type="tel" name="pay_phone" value="<?= getReq('pay_phone') ?: $order_data->pay_phone ?>" />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Stato', $app->labels, true) ?></label>
                                            <input type="text" name="pay_country" value="<?= getReq('pay_country') ?: $order_data->pay_country ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label><?= appLabel('Provincia', $app->labels, true) ?></label>
                                            <input type="text" name="pay_district" value="<?= getReq('pay_district') ?: $order_data->pay_district ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field form-field-2-3">
                                            <label><?= appLabel('Località', $app->labels, true) ?></label>
                                            <input type="text" name="pay_city" value="<?= getReq('pay_city') ?: $order_data->pay_city ?>" />
                                        </div>
                                        <div class="form-field form-field-1-3">
                                            <label><?= appLabel('Cap', $app->labels, true) ?></label>
                                            <input type="text" name="pay_zip" value="<?= getReq('pay_zip') ?: $order_data->pay_zip ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field form-field-3-4">
                                            <label><?= appLabel('Via/Piazza', $app->labels, true) ?></label>
                                            <input type="text" name="pay_address" value="<?= getReq('pay_address') ?: $order_data->pay_address ?>" />
                                        </div>
                                        <div class="form-field form-field-1-4">
                                            <label><?= appLabel('Civico', $app->labels, true) ?></label>
                                            <input type="text" name="pay_address_number" value="<?= getReq('pay_address_number') ?: $order_data->pay_address_number ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label><?= appLabel('Altre Info', $app->labels, true) ?></label>
                                            <input type="text" name="pay_infos" value="<?= getReq('pay_infos') ?: $order_data->pay_infos ?>" placeholder="<?= appLabel('Altre info di fatturazione', $app->labels, true) ?>..." />
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
                                    <a class="shop-action-tools-action  shop-action-tools-action-prev" href="<?= route_to(__locale_uri__ . 'web_shop_make_order') ?>"><?= appLabel('Spedizione', $app->labels, true) ?></a>
                                    <button type="submit" name="pay_send" value="next" class="shop-action-tools-action shop-action-tools-action-next"><?= appLabel('Vai al Pagamento', $app->labels, true) ?></button>
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
        <aside class="sidebar lcshop-riepilogo-sidebar">
            <h6>Riepilogo Carrello</h6>
            <?= view(customOrDefaultViewFragment('shop/components/cart-totals', 'LcShop')) ?>
        </aside>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {



    });
</script>

<?= $this->endSection() ?>