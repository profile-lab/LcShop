<div class="mini-cart-cnt" id="mini-cart-cnt">
    <div class="mini-cart-cnt-header">
        <a href="#" onclick="document.getElementById('mini-cart-cnt').classList.toggle('mobile-show'); return false;" class="mini-cart-title-show-minicart">
            <?= appLabel('Il tuo carrello', $app->labels, true) ?>
            <span class="ico-accordion">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="m256-424-56-56 280-280 280 280-56 56-224-223-224 223Z" />
                </svg>
            </span>
        </a>
    </div>
    <div class="mini-cart-cnt-data">
        <?php if (isset($site_cart)) { ?>
            <a href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>" class="mini-cart-title-link">
                <h5 class="mini-cart-title">
                    <?= appLabel('Il tuo carrello', $app->labels, true) ?>
                </h5>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37 30">
                    <path class="cart_icon_pat" d="M257.042,396.3H234.391l-.569-2.967h24.5l2.974-15.165H230.949l-1.093-5.769H224.3v1.154h4.6l4.326,22.8a3.046,3.046,0,1,0,3.579,3,3.018,3.018,0,0,0-.68-1.9h18.547a3.018,3.018,0,0,0-.68,1.9,3.049,3.049,0,1,0,3.05-3.05Zm-7.974-4.121h-3l.029-12.857h3.833Zm2.016-12.857h3.829l-1.69,12.857h-3Zm-6.173,12.857h-3l-.8-12.857h3.834Zm-4.159,0h-3l-1.632-12.857h3.829Zm16.623,0h-2.989l1.69-12.857h3.82Zm-22.418-12.857,1.633,12.857H233.6l-2.435-12.857Zm.694,20.028a1.9,1.9,0,1,1-1.9-1.9A1.9,1.9,0,0,1,235.651,399.351Zm21.391,1.895a1.9,1.9,0,1,1,1.9-1.895A1.9,1.9,0,0,1,257.042,401.246Z" transform="translate(-224.298 -372.4)"></path>
                </svg>
            </a>

            <div class="mini-cart">
                <?php if (isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) { ?>
                    <?php foreach ($site_cart->products as $cart_item) { ?>
                        <div class="mini-cart-row">
                            <div class="mini-cart-col mini-cart-col-name">
                                <span class="mini-cart-qnt-val"><?= $cart_item->qnt ?></span> <span class="mini-cart-qnt-spacer">|</span> <span class="mini-cart-prod-name-val"><?= $cart_item->full_nome_prodotto ?></span>
                            </div>
                            <div class="mini-cart-col mini-cart-col-price">
                                <span class="mini-cart-price-val">&euro; <?= $cart_item->prezzo_formatted ?></span>
                            </div>
                            <div class="mini-cart-col">
                                <a class="cart-page-action" href="<?= route_to(__locale_uri__ . 'web_shop_cart_remove_row', $cart_item->row_key) ?>">&#10005;</a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="mini-cart-row mini-cart-totals">
                        <div class="mini-cart-col mini-cart-col-name">
                            <?= appLabel('Totale prodotti', $app->labels, true) ?>
                        </div>
                        <div class="mini-cart-col mini-cart-col-price">&euro; <span class="mini-cart-price-val"><?= $site_cart->total_formatted ?></span>
                        </div>
                    </div>
                    <div class="mini-cart-row action-cnt">
                        <a href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>" class="cart-action mini-cart-action"><?= appLabel('Vai al Carrello', $app->labels, true) ?></a>
                    </div>
                <?php } else { ?>
                    <div class="mini-cart-mess no_products"><?= appLabel('Non ci sono prodotti', $app->labels, true) ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>