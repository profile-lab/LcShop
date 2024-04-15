<div class="order-summary-cnt" id="order-summary-cnt">
    <div class="order-summary-header">
        <a href="#" onclick="document.getElementById('order-summary-cnt').classList.toggle('mobile-show'); return false;" class="order-summary-title-show-minicart">
            Il tuo ordine
            <span class="ico-accordion">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="m256-424-56-56 280-280 280 280-56 56-224-223-224 223Z" />
                </svg>
            </span>
        </a>
    </div>
    <div class="order-summary-data">
        <?php

        $current_oder_data_type = null;
        $current_oder_data = null;
        $current_oder_data_products = null;
        if (isset($riepilogo_order_data) && $riepilogo_order_data) {
            $current_oder_data_type = 'order';
            $current_oder_data = $riepilogo_order_data;
            if (isset($orders_items) && is_iterable($orders_items) && count($orders_items) > 0) {
                $current_oder_data_products = $orders_items;
            }
        } else if (isset($site_cart)) {
            $current_oder_data_type = 'cart';
            $current_oder_data = $site_cart;
            if (isset($site_cart->products) && is_iterable($site_cart->products) && count($site_cart->products) > 0) {
                $current_oder_data_products = $site_cart->products;
            }
        }

        ?>
        <?php if (isset($current_oder_data)) { ?>
            <?php if ($current_oder_data_type == 'cart') { ?>
                <a href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>" class="order-summary-title-link">
                    <h5 class="order-summary-title">
                        Il tuo ordine 
                    </h5>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37 30">
                        <path class="cart_icon_pat" d="M257.042,396.3H234.391l-.569-2.967h24.5l2.974-15.165H230.949l-1.093-5.769H224.3v1.154h4.6l4.326,22.8a3.046,3.046,0,1,0,3.579,3,3.018,3.018,0,0,0-.68-1.9h18.547a3.018,3.018,0,0,0-.68,1.9,3.049,3.049,0,1,0,3.05-3.05Zm-7.974-4.121h-3l.029-12.857h3.833Zm2.016-12.857h3.829l-1.69,12.857h-3Zm-6.173,12.857h-3l-.8-12.857h3.834Zm-4.159,0h-3l-1.632-12.857h3.829Zm16.623,0h-2.989l1.69-12.857h3.82Zm-22.418-12.857,1.633,12.857H233.6l-2.435-12.857Zm.694,20.028a1.9,1.9,0,1,1-1.9-1.9A1.9,1.9,0,0,1,235.651,399.351Zm21.391,1.895a1.9,1.9,0,1,1,1.9-1.895A1.9,1.9,0,0,1,257.042,401.246Z" transform="translate(-224.298 -372.4)"></path>
                    </svg>
                </a>
            <?php  } else { ?>
                <div href="<?= route_to(__locale_uri__ . 'web_shop_cart') ?>" class="order-summary-title-link">
                    <h5 class="order-summary-title">
                        Riepilogo ordine
                    </h5>

                </div>
            <?php  } ?>

            <div class="order-summary">
                <?php if (isset($current_oder_data_products) && is_iterable($current_oder_data_products) && count($current_oder_data_products) > 0) { ?>
                    <?php foreach ($current_oder_data_products as $cart_item) { ?>
                        <div class="order-summary-row">
                            <div class="order-summary-col order-summary-col-name">
                                <span class="order-summary-qnt-val"><?= $cart_item->qnt ?></span> <span class="order-summary-qnt-spacer">|</span> <span class="order-summary-prod-name-val"><?= $cart_item->full_nome_prodotto ?></span>
                            </div>
                            <div class="order-summary-col order-summary-col-price">
                                <span class="order-summary-price-val">&euro; <?= $cart_item->prezzo_formatted ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="order-summary-totals">
                        <?php if ($current_oder_data_type == 'cart') { ?>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    Totale prodotti
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->total_formatted ?></span>
                                </div>
                            </div>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    <?= $current_oder_data->spedizione_name ?>
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    <?php if ($current_oder_data->spese_spedizione > 0) { ?>
                                        &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->spese_spedizione_formatted ?></span>
                                    <?php } else { ?>
                                        <span class="make-order-page-col-price-val"><?= $current_oder_data->spese_spedizione_formatted ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    Totale
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->pay_total_formatted ?></span>
                                </div>
                            </div>
                        <?php  } else { ?>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    Imponibile
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->imponibile_total_formatted ?></span>
                                </div>
                            </div>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    Iva
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->iva_total_formatted ?></span>
                                </div>
                            </div>
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    <?= $current_oder_data->spedizione_name ?>
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                    <?php if ($current_oder_data->spese_spedizione > 0) { ?>
                                        &euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->spese_spedizione_formatted ?></span>
                                    <?php } else { ?>
                                        <span class="make-order-page-col-price-val"><?= $current_oder_data->spese_spedizione_formatted ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                            <div class="order-summary-row order-summary-totals-row">
                                <div class="order-summary-col order-summary-col-name">
                                    <b>Totale ordine</b>
                                </div>
                                <div class="order-summary-col order-summary-col-price">
                                <b>&euro; <span class="make-order-page-col-price-val"><?= $current_oder_data->pay_total_formatted ?></span></b>
                                </div>
                            </div>
                        <?php  } ?>
                    </div>

                <?php } else { ?>
                    <div class="order-summary-mess no_products">Non ci sono prodotti!</div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>