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
                            <?= getServerMessage() ?>

                            <form method="POST" class="form_spedizione_cnt">
                                <header class="sped_type">
                                    <h4>Spedizione</h4>
                                    <h5>Inserisci i dati di spedizione del tuo ordine</h5>
                                </header>
                                <div class="form_spedizione">
                                    <?= user_mess($ui_mess, $ui_mess_type) ?>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label>Nome</label>
                                            <input type="text" name="name" value="<?= getReq('name') ?: $ship_data->name ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label>Cognome</label>
                                            <input type="text" name="surname" value="<?= getReq('surname') ?: $ship_data->surname ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label>Stato</label>
                                            <input type="text" name="country" value="<?= getReq('country') ?: $ship_data->country ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label>Provincia</label>
                                            <input type="text" name="district" value="<?= getReq('district') ?: $ship_data->district ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label>Località</label>
                                            <input type="text" name="city" value="<?= getReq('city') ?: $ship_data->city ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label>Cap</label>
                                            <input type="text" name="cap" value="<?= getReq('cap') ?: $ship_data->cap ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label>Via/Piazza</label>
                                            <input type="text" name="address" value="<?= getReq('address') ?: $ship_data->address ?>" />
                                        </div>
                                        <div class="form-field">
                                            <label>Civico</label>
                                            <input type="text" name="street_number" value="<?= getReq('street_number') ?: $ship_data->street_number ?>" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label>Altre Info</label>
                                            <input type="text" name="infos" value="<?= getReq('infos') ?: '' ?>" placeholder="Interno, scala, citofono..." />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-field">
                                            <label class="check-label">
                                                <input type="checkbox" class="check-input" name="save_in_user" value="salva" <?= ($request->getPost('save_in_user')) ? 'checked' : '' ?> />
                                                Salva i dati per il prossimo ordine
                                            </label>
                                        </div>
                                    </div>
                                    <div class="navigation_tools">
                                        <div class="backBtnCnt">
                                            <a href="<?= site_url(route_to('site_cart')) ?>">
                                                <svg class="left-arrow"><svg xmlns="/2000/svg" xmlns:xlink="/1999/xlink"><svg id="left_arrow" viewBox="0 0 12 20">
                                                            <path d="M10 .667L.667 10 10 19.332h1.333V18l-8-8 8-8V.667" fill-rule="evenodd"></path>
                                                        </svg></svg></svg>
                                                Torna al carrello
                                            </a>
                                        </div>
                                        <div class="nextBtnCnt">
                                            <button type="submit" name="ship_send" value="next" class="next_step">
                                                Scegli la consegna
                                                <svg class="right-arrow"><svg xmlns="/2000/svg" xmlns:xlink="/1999/xlink"><svg id="right_arrow" viewBox="0 0 12 20">
                                                            <path d="M10 .667L.667 10 10 19.332h1.333V18l-8-8 8-8V.667" fill-rule="evenodd"></path>
                                                        </svg></svg></svg>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>



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