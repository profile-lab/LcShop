<?= $this->extend(customOrDefaultViewFragment('layout/body')) ?>
<?= $this->section('content') ?>
<article>
    <section class="page-header shop-header">
        <div class="myIn">
            <hgroup>
                <?= h1($full_nome_prodotto) ?>
                <?= h2($sottotitolo) ?>
            </hgroup>
            <?= (isset($category_obj) && $category_obj) ? '<a href="' . $category_obj->permalink . '">' . $category_obj->nome . '</a>' : '' ?>
        </div>
    </section>
    <div class="myIn shop_flex">
        <div class="shop_content shop_content_detail <?= ($in_promo) ? ' is_in_promo' : '' ?>">

            <?= single_img($main_img_path, '') ?>
            <section class="shop_content_detail_main">
                <div class="shop_product_scheda_dettagli_txts">
                    <?php if (isset($modelli) && is_array($modelli) && count($modelli) > 1) { ?>
                        <h5 class="shop_product_scheda_varianti_tit"><?= appLabel('Varianti', $app->labels, true)  ?></h5>
                        <div class="shop_product_scheda_varianti_cnt">
                            <div class="shop_product_scheda_varianti_current"><?= $full_nome_prodotto ?></div>
                            <ul class="shop_product_scheda_varianti_list">
                                <?php foreach ($modelli as $modello) { ?>
                                    <li class="<?= ($id == $modello->id) ? 'current' : '' ?>">
                                        <a href="<?= $modello->permalink ?>"><?= $modello->full_nome_prodotto ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?= txt($testo, 'description', 'div', null, null, false) ?>
                </div>
                <div class="shop_content_detail_tools">
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
            <?php if (isset($gallery_obj) && count($gallery_obj) > 0) { ?>
                <?= view(customOrDefaultViewFragment('components/slider'), ['gallery_obj' => $gallery_obj, 'format_folder' => '']) ?>
            <?php } ?>
            <?= txt($scheda_tecnica, 'scheda_tecnica', 'div', null, null, false, 'Ingredienti', '', 'h5') ?>
        </div>
        <?= view(customOrDefaultViewFragment('shop/components/sidebar', 'LcShop')) ?>

    </div>
</article>

<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<?= $this->endSection() ?>