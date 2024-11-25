<?php
if (isset($latest_user_orders)) {
    $current_orders_list = $latest_user_orders;
} else if (isset($user_orders_list)) {
    $current_orders_list = $user_orders_list;
}


if (isset($current_orders_list) && is_array($current_orders_list) && count($current_orders_list) > 0) { ?>
    <section class="lcuser_orders_list">
        <div class="lcuser_orders_list_rows">
            <?php $conta_row = 0 ?>
            <div class="lcuser_orders_list_row lcuser_orders_list_row_head">
                <div>Data</div>
                <div>Stato</div>
                <div>Pagamento</div>
                <div>Totale</div>
                <div class="lcuser_orders_list_row_actions">
                    <div>&nbsp;</div>
                </div>
            </div>

            <?php foreach ($current_orders_list as $order) { ?>
                <div class="lcuser_orders_list_row <?= ($conta_row % 2 == 0) ? 'even' : 'odd' ?>">
                    <div><?= langLabel('Ordine') ?> del <?= humanData($order->created_at)  ?></div>
                    <div><?= $order->order_status_label ?></div>
                    <div><?= ($order->payment_status == 'PENDING') ? '' :  $order->payment_type_label . '/' ?><?= $order->payment_status_label ?></div>
                    <div>€ <?= $order->pay_total_formatted ?></div>
                    <div class="lcuser_orders_list_row_actions">
                        <?php if ($order->payment_status != 'COMPLETED') { ?>
                            <a href="<?= route_to('web_shop_pay_on_stripe_app', $order->id) ?>" class="button button-min">Paga</a>
                        <?php } ?>
                        <a href="<?= route_to('web_user_order_dett', $order->id) ?>" class="button button-min">Dettagli</a>
                    </div>

                </div>
                <?php $conta_row++ ?>
            <?php } ?>
        </div>
    </section>
<?php } else { ?>
    <div class="user-module-main">
        <?= h6('Nessun ordine trovato') ?>
    </div>
<?php } ?>