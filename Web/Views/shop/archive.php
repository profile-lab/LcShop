<?= $this->extend($base_view_folder . 'layout/body') ?>
<?= $this->section('content') ?>
<article>
    <?php if ($rows_code =  printPostRows($entity_rows)) { ?>
        <?= $rows_code ?>
    <?php } else { ?>
        <header>
            <div class="myIn">
                <?= h1($titolo, 'shop_archive_title') ?>
            </div>
        </header>
    <?php } ?>
</article>
<section class="shop_listing">
    <div class="myIn shop_flex">
        <div class="shop_content shop_content_listing">
            <?php if (isset($products_archive) && is_iterable($products_archive) && count($products_archive) > 0) { ?>
                <?php foreach ($products_archive as $single) { ?>
                    <?= view(customOrDefaultViewFragment('shop/components/product-listing-card',  'LcShop'), ['single_items' => $single]) ?>
                <?php } ?>
            <?php } ?>
        </div>
        <?= view(customOrDefaultViewFragment('shop/components/sidebar', 'LcShop')) ?>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    let products_archive = [];

    <?php if (isset($products_archive) && is_iterable($products_archive) && count($products_archive) > 0) {
        echo "products_archive = [";
        foreach ($products_archive as $single) {
            echo '{';
            echo "'id': " . $single->id . ", 'permalink': '" . $single->permalink . "', 'nome': '" . $single->nome . "', 'prezzo_coin': '" . $single->prezzo_coin . "', 'prezzo_pieno_coin': '" . $single->prezzo_pieno_coin . "', 'um': '" . $single->um . "', 'giacenza': '" . $single->giacenza . "', 'in_promo': " . (($single->in_promo) ? 'true' : 'false') . ", 'main_img_path': '"  . single_img_url($single->main_img_path, 'thumbs')  . "',";
            if (isset($single->modelli) && is_array($single->modelli)) {
                echo "
                'modelli': [";
                foreach ($single->modelli as $modello) {
                    echo "{";
                    echo "'id': " . $modello->id . ", 'permalink': '" . $modello->permalink . "', 'nome': '" . $modello->nome . "', 'prezzo_coin': '" . $modello->prezzo_coin . "', 'prezzo_pieno_coin': '" . $modello->prezzo_pieno_coin . "', 'um': '" . ((isset($modello->um) && $modello->um) ? $modello->um : $single->um) . "', 'giacenza': '" . ((isset($modello->giacenza) && $modello->giacenza) ? $modello->giacenza : $single->giacenza) . "', 'in_promo': '" . ((isset($modello->in_promo) && $modello->in_promo) ? 'true' : 'false') . "', 'main_img_path': '" . single_img_url($modello->main_img_path, 'thumbs') . "'";
                    echo "}, ";
                }
                echo "]";
            }
            echo "
            }, ";
        }
        echo "];";
    } ?>
    $(document).ready(function() {
        $('.sel_prod_model_id').on('change', function(e) {
            e.preventDefault();
            const prod_id = $(this).closest('.shop_product_card').find('input[name="prod_id"]').val();
            const prod_model_id = $(this).val();
            console.log('cambio', prod_id, prod_model_id);
            if (prod_id && prod_model_id) {
                const prod = products_archive.find(x => x.id == prod_id);
                if (prod) {
                    const model = prod.modelli.find(x => x.id == prod_model_id);
                    if (model) {
                        // console.log('trovato', model);
                        const prod_card = $(this).closest('.shop_product_card');
                        prod_card.removeClass('is_in_promo');
                        const shop_product_prices = prod_card.find('.shop_product_prices');
                        const agg_cart_cnt = prod_card.find('.agg_cart_cnt');
                        const product_giac_mess = prod_card.find('.product_giac_mess');
                        product_giac_mess.html('');
                        agg_cart_cnt.show();
                        product_giac_mess.hide();


                        if (model.in_promo == 'true') {
                            prod_card.addClass('is_in_promo');
                            const price_promo = $('<div class="price price_promo" />');
                            price_promo.append('<span class="price_coin">' + model.prezzo_coin + '</span><span class="price_spacer">/</span><span class="price_um">' + model.um + '</span>');
                            const price_nosale = $('<div class="price price_nosale" />');
                            price_nosale.append('<span class="price_coin">' + model.prezzo_pieno_coin + '</span><span class="price_spacer">/</span><span class="price_um">' + model.um + '</span>');
                            shop_product_prices.html('');
                            shop_product_prices.append(price_promo);
                            shop_product_prices.append(price_nosale);
                        } else {
                            prod_card.removeClass('is_in_promo');
                            const price_full = $('<div class="price" />');
                            price_full.append('<span class="price_coin">' + model.prezzo_coin + '</span><span class="price_spacer">/</span><span class="price_um">' + model.um + '</span>');
                            shop_product_prices.html('');
                            shop_product_prices.append(price_full);
                        }

                        if (model.giacenza > 0) {

                        } else {
                            product_giac_mess.append('<div class="prodotto_esaurito prodotto_esaurito_detail">Prodotto esaurito</div>');
                            agg_cart_cnt.hide();
                            product_giac_mess.show();

                        }

                        if (model.main_img_path) {
                            const main_img_path = prod_card.find('.shop_product_thumb img');
                            main_img_path.attr('src', model.main_img_path);
                        }
                        if (model.permalink) {
                            const permalink = prod_card.find('.shop_product_link');
                            permalink.attr('href', model.permalink);
                        }


                        // main_img_path = prod_card.find('.shop_product_thumb img');

                    }
                }
            }
        });


    });
</script>

<?= $this->endSection() ?>