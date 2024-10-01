<?= $this->extend(customOrDefaultViewFragment('layout/body')) ?>
<?= $this->section('content') ?>
<article>
    <?= view(customOrDefaultViewFragment('shop/components/breadcrumb', 'LcShop')) ?>
    <section class="lcshop-header">
        <div class="myIn">

            <hgroup>
                <?= h1($titolo) ?>
                <?= h2($sottotitolo) ?>
                <?= h5($modello) ?>
            </hgroup>
        </div>
    </section>
    <div class="myIn lcshop-flex">
        <div class="lcshop-scheda">
            <?php /*
            <?= view(customOrDefaultViewFragment('shop/components/sidebar', 'LcShop')) ?>
            */ ?>
            <div class="lcshop-content lcshop-detail <?= ($in_promo) ? ' is_in_promo' : '' ?>">
                <div class="lcshop-detail-medias">
                    <?php if (isset($gallery_obj) && count($gallery_obj) > 0) { ?>
                        <?= view(customOrDefaultViewFragment('components/slider'), ['gallery_obj' => $gallery_obj, 'format_folder' => '']) ?>
                    <?php } else { ?>
                        <?= single_img($main_img_path, '') ?>
                    <?php } ?>
                </div>
                <section class="lcshop-detail-main">
                    <div class="lcshop-detail-txts">
                        <?php if (isset($modelli) && is_array($modelli) && count($modelli) > 1) { ?>
                            <div class="lcshop-varianti-tit"><?= appLabel('Seleziona il modello', $app->labels, true)  ?></div>
                            <div class="lcshop-varianti-cnt">
                                <div class="lcshop-varianti-current">
                                    <div class="lcshop-varianti-img"><?= single_img($main_img_path, '') ?></div>
                                    <div class="lcshop-varianti-name"><?= $modello ?></div>
                                    <div class="lcshop-varianti-open-sub">
                                        <span class="ico-accordion">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                <path d="m256-424-56-56 280-280 280 280-56 56-224-223-224 223Z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <ul class="lcshop-varianti-list">
                                    <?php foreach ($modelli as $modello) { ?>
                                        <li class="<?= ($id == $modello->id) ? 'current' : '' ?>">
                                            <a class="lcshop-varianti-list-item" href="<?= $modello->permalink ?>">
                                                <div class="lcshop-varianti-list-item-img"><?= single_img($modello->main_img_path, '') ?></div>
                                                <div class="lcshop-varianti-list-item-name"><?= $modello->modello ?></div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <div class="lcshop-dati">
                            <?php if (isset($misura_obj) && $misura_obj != null && isset($misura_obj->nome)) { ?>
                                <div class="lcshop-dati-item lcshop-dati-item-misura">
                                    <span class="lcshop-dati-item-label"><?= langLabel('Misura') ?>: </span>
                                    <span class="lcshop-dati-item-val"><?= $misura_obj->nome ?></span>
                                </div>
                            <?php } ?>
                            <?php if (isset($colore_obj) && $colore_obj != null && isset($colore_obj->nome)) { ?>
                                <div class="lcshop-dati-item lcshop-dati-item-color">
                                    <span class="lcshop-dati-item-label"><?= langLabel('Colore') ?>: </span>
                                    <span class="lcshop-dati-item-val"><?= $colore_obj->nome ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <?= txt($testo, 'description', 'div', null, null, false) ?>
                    </div>
                    <div class="lcshop-detail-tools">
                        <?php if ($giacenza && $giacenza > 0) { ?>
                            <form method="post" name="add_to_cart_form">
                                <?= view(customOrDefaultViewFragment('shop/components/add_to_cart_component',  'LcShop'), ['giacenza' => $giacenza]) ?>
                                <input type="hidden" name="prod_id" value="<?= $prod_id ?>">
                                <input type="hidden" name="prod_model_id" value="<?= $prod_model_id ?>" />
                                <div class="shop_product_scheda_dettagli">
                                    <div class="shop_product_scheda_dettagli_price">
                                        <?php if ($in_promo) { ?>
                                            <div class="price price_promo">
                                                <span class="price_coin"><?= $prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                            </div>
                                            <div class="price price_nosale">
                                                <span class="price_coin"><?= $prezzo_pieno_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="price">
                                                <span class="price_coin"><?= $prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                        <?php } else { ?>
                            <div class="prodotto_esaurito prodotto_esaurito_detail"><?= appLabel('Prodotto esaurito', $app->labels, true)  ?></div>
                            <div class="shop_product_scheda_dettagli">
                                <div class="shop_product_scheda_dettagli_price">
                                    <?php if ($in_promo) { ?>
                                        <div class="price price_promo">
                                            <span class="price_coin"><?= $prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                        </div>
                                        <div class="price price_nosale">
                                            <span class="price_coin"><?= $prezzo_pieno_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="price">
                                            <span class="price_coin"><?= $prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $um ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>

            </div>
            <div class="lcshop-content lcshop-detail lcshop-detail-row-tech">
                <div class="lcshop-detail-tech-img">
                    <?= single_img($alt_img_path, '') ?>
                </div>
                <?= txt($scheda_tecnica, 'lcshop-detail-tech-txt', 'div', null, null, false, 'Scheda tecnica', '', 'h5') ?>
            </div>
        </div>
    </div>
</article>

<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<?= $this->endSection() ?>