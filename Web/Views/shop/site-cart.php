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


<section class="lcshop-carrello">
    <div class="myIn">
        <div class="cart-page">
            <?php if (isset($site_cart) && isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) { ?>
                <div class="cart-page-row cart-page-row-header">
                    <div class="cart-page-col cart-page-col-name">
                        <b>Prodotto</b>
                    </div>
                    <div class="cart-page-col cart-page-col-qnt">
                        <b>Quantità</b>
                    </div>
                    <div class="cart-page-col cart-page-col-price">Prezzo €</div>
                    <div class="cart-page-col cart-page-col-price">Totale €</div>
                    <div class="cart-page-col cart-page-col-action">
                        
                    </div>
                </div>
                <div class="cart-page-rows">
                    <?php foreach ($site_cart->products as $cart_item) { ?>
                        <div class="cart-page-row">
                            <div class="cart-page-col cart-page-col-name">
                                <a class="cart-page-name" href="<?= $cart_item->permalink ?>">
                                    <?= $cart_item->nome ?>
                                </a>
                                <div class="cart-page-fullname">
                                    <?= $cart_item->full_nome_prodotto ?>
                                </div>
                            </div>
                            <div class="cart-page-col cart-page-col-qnt">
                                <a class="cart-page-qnt-action" href="<?= route_to(__locale_uri__ . 'web_shop_cart_decrement_qnt', $cart_item->row_key) ?>">-</a>
                                <span class="cart-page-qnt-val">
                                    <?= $cart_item->qnt ?>
                                </span>
                                <a class="cart-page-qnt-action" href="<?= route_to(__locale_uri__ . 'web_shop_cart_increment_qnt', $cart_item->row_key) ?>">+</a>
                            </div>
                            <div class="cart-page-col cart-page-col-price">&euro; <span class="cart-page-price-val"><?= $cart_item->prezzo_uni_formatted ?></span></div>
                            <div class="cart-page-col cart-page-col-price">&euro; <span class="cart-page-price-val"><?= $cart_item->prezzo_formatted ?></span></div>
                            <div class="cart-page-col cart-page-col-action">
                                <a class="cart-page-action" href="<?= route_to(__locale_uri__ . 'web_shop_cart_remove_row', $cart_item->row_key) ?>">&#10005;</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="cart-page-totals">

                    <?= view(customOrDefaultViewFragment('shop/components/cart-totals', 'LcShop')) ?>
                </div>

                <div class="shop-action-tools-rows">
                    <a class="button lcshop-button" href="<?= route_to(__locale_uri__ . 'web_shop_cart_empty') ?>"><?= appLabel('Svuota carrello', $app->labels, true) ?></a>
                    <a class="button lcshop-button lcshop-button-next" href="<?= route_to(__locale_uri__ . 'web_shop_make_order') ?>"><?= appLabel('Procedi', $app->labels, true) ?></a>
                </div>
            <?php } else { ?>
                <?= view(customOrDefaultViewFragment('shop/components/no-products-message', 'LcShop')) ?>
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