<div class="order-summary">
    <div class="order-summary-products">
        <?php foreach ($site_cart->products as $cart_item) { ?>
            <div class="order-summary-products-row">
                <?= $cart_item->qnt ?> | <?= $cart_item->full_nome_prodotto ?>
            </div>
        <?php } ?>
    </div>
    <div class="order-summary-total">
        <div class="order-summary-total-row">
            Totale prodotti
            &euro; <span class="make-order-page-col-price-val"><?= $site_cart->total_formatted ?></span>
        </div>
        <div class="order-summary-total-row">
            <?= $site_cart->spedizione_name ?>
            <?php if ($site_cart->spese_spedizione > 0) { ?>
                &euro; <span class="make-order-page-col-price-val"><?= $site_cart->spese_spedizione_formatted ?></span>
            <?php } else { ?>
                <span class="make-order-page-col-price-val"><?= $site_cart->spese_spedizione_formatted ?></span>
            <?php } ?>
        </div>
        <div class="order-summary-total-row">
            Totale
            &euro; <span class="make-order-page-col-price-val"><?= $site_cart->total_formatted ?></span>
        </div>
    </div>

</div>