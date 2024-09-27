<div class="lcshop-cart-totals">
        <div class="lcshop-totals-row">
            <div class="cart-page-col cart-page-col-name">
                <?= appLabel('Totale prodotti', $app->labels, true) ?>
            </div>
            <div class="cart-page-col cart-page-col-price">
                &euro; <span class="cart-page-col-price-val"><?= $site_cart->total_formatted ?></span>
            </div>
        </div>

        <?php /*
        <div class="lcshop-totals-row">
            <div class="cart-page-col cart-page-col-name">
                <?= appLabel('Peso', $app->labels, true) ?>
            </div>
            <div class="cart-page-col cart-page-col-kg">
                <span class="cart-page-col-price-val"><?= $site_cart->peso_totale_kg ?> Kg</span>
            </div>
        </div>
        */ ?>

        <div class="lcshop-totals-row">
            <div class="cart-page-col cart-page-col-name">
                <?= appLabel('Imponibile', $app->labels, true) ?>
            </div>
            <div class="cart-page-col cart-page-col-price">
                &euro; <span class="cart-page-col-price-val"><?= $site_cart->imponibile_total_formatted ?></span>
            </div>
        </div>
        <div class="lcshop-totals-row">
            <div class="cart-page-col cart-page-col-name">
                <?= appLabel('Iva', $app->labels, true) ?>
            </div>
            <div class="cart-page-col cart-page-col-price">
                &euro; <span class="cart-page-col-price-val"><?= $site_cart->iva_total_formatted ?></span>
            </div>
        </div>
        <div class="lcshop-totals-row">
            <div class="cart-page-col cart-page-col-name">
                <?= $site_cart->spedizione_name ?>
            </div>
            <?php if ($site_cart->spese_spedizione > 0) { ?>
                <div class="cart-page-col cart-page-col-price">
                    &euro; <span class="cart-page-col-price-val"><?= $site_cart->spese_spedizione_formatted ?></span>
                </div>
            <?php } else { ?>
                <div class="cart-page-col cart-page-col-price cart-page-col-price-free">
                    <span class="cart-page-col-price-val"><?= $site_cart->spese_spedizione_formatted ?></span>
                </div>
            <?php } ?>
        </div>
        <div class="lcshop-totals-row lcshop-totals-row-totale">
            <div class="cart-page-col cart-page-col-name">
                <?= appLabel('Totale', $app->labels, true) ?>
            </div>
            <div class="cart-page-col cart-page-col-price">
                &euro; <span class="cart-page-col-price-val"><?= $site_cart->pay_total_formatted ?></span>
            </div>
        </div>
</div>