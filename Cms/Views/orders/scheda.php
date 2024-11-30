<?= $this->extend('Lc5\Cms\Views\layout/body') ?>
<?= $this->section('content') ?>
<script>
    let count_rows = 0;
</script>
<form class="form save_after_proc" method="POST" action="">

    <?= user_mess($ui_mess, $ui_mess_type) ?>
    <div class="d-md-flex">
        <div class="d-flex align-items-center">

        </div>
        <div class="d-flex align-items-center ">
            <div>
                <button type="submit" class="btn bottone_salva btn-primary"><span class="oi oi-check"></span>Salva</button>
            </div>
        </div>
    </div>

    <div class="row form-row">
        <div class="col-12 col-lg-9 scheda_body">
            <div class="first-row">
                <div class="row">
                    <?php if (isset($all_users_list) && is_iterable($all_users_list)) { ?>
                        <?= view('Lc5\Cms\Views\form-cmp/select', ['item' => ['label' => 'Utente', 'name' => 'user_id', 'value' => $entity->user_id,  'sources' => $all_users_list, 'no_empty' => true]]) ?>
                    <?php } ?>

                </div>
                <div class="row">
                    <h5>Articoli</h5>
                    <hr />
                </div>
                <div class="row">
                    <?php if ($order_items && is_iterable($order_items)) { ?>
                        <table class="lista_prodotti_ordine">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Nome</th>
                                    <th>Modello</th>
                                    <th>Quantità</th>
                                    <th>Prezzo</th>
                                    <th>Totale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $article) { ?>
                                    <tr>
                                        <td><?= $article->id_prodotto ?>|<?= $article->id_modello ?></td>
                                        <td><?= $article->reference_type ?></td>
                                        <td><?= $article->nome ?></td>
                                        <td><?= $article->modello ?></td>
                                        <td><?= $article->qnt ?></td>
                                        <td>€ <?= $article->prezzo_uni ?></td>
                                        <td>€ <?= $article->prezzo ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div class="row">
                    <h5>Spedizione</h5>
                    <hr />
                </div>
                <div class="row">
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nome', 'name' => 'ship_name', 'value' => $entity->ship_name]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Cognome', 'name' => 'ship_surname', 'value' => $entity->ship_surname]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nazione', 'name' => 'ship_country', 'value' => $entity->ship_country]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Provincia', 'name' => 'ship_district', 'value' => $entity->ship_district]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Città', 'name' => 'ship_city', 'value' => $entity->ship_city]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Cap', 'name' => 'ship_zip', 'value' => $entity->ship_zip]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Indirizzo', 'name' => 'ship_address', 'value' => $entity->ship_address]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Numero', 'name' => 'ship_address_number', 'value' => $entity->ship_address_number]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Telefono', 'name' => 'ship_phone', 'value' => $entity->ship_phone]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'email', 'name' => 'ship_email', 'value' => $entity->ship_email]]) ?>
                    </div>
                    <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Info Spedizione', 'name' => 'ship_infos', 'value' => $entity->ship_infos]]) ?>

                </div>
                <div class="row">
                    <h5>Fatturazione</h5>
                    <hr />
                </div>

                <div class="row">
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nome', 'name' => 'pay_name', 'value' => $entity->pay_name]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Cognome', 'name' => 'pay_surname', 'value' => $entity->pay_surname]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nazione', 'name' => 'pay_country', 'value' => $entity->pay_country]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Provincia', 'name' => 'pay_district', 'value' => $entity->pay_district]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Città', 'name' => 'pay_city', 'value' => $entity->pay_city]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Cap', 'name' => 'pay_zip', 'value' => $entity->pay_zip]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'indirizzo', 'name' => 'pay_address', 'value' => $entity->pay_address]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Numero', 'name' => 'pay_address_number', 'value' => $entity->pay_address_number]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Telefono', 'name' => 'pay_phone', 'value' => $entity->pay_phone]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Email', 'name' => 'pay_email', 'value' => $entity->pay_email]]) ?>
                    </div>
                    <div class="row form-row">
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Partita Iva', 'name' => 'pay_vat', 'value' => $entity->pay_vat]]) ?>
                        <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Codice Fiscale', 'name' => 'pay_fiscal', 'value' => $entity->pay_fiscal]]) ?>
                    </div>
                    <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Extra info', 'name' => 'pay_infos', 'value' => $entity->pay_infos]]) ?>

                </div>
                <div class="row">
                    <h5>Info pagamenti</h5>
                    <textarea class="form-control" rows="3"><?=
                                                            trim($entity->paypal_string) ? $entity->paypal_string . '
' : '' ?><?=
            trim($entity->stripe_pi) ? 'Stripe PI: ' . $entity->stripe_pi . '
' : '' ?><?=
            trim($entity->payment_code) ? 'Payment code: ' . $entity->payment_code . '
' : '' ?></textarea>
                </div>
            </div>


        </div>
        <div class="scheda-sb margin-top-0">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light rounded">
                        <?php if (!isset($entity->parent_entity)) { ?>
                            <div class="row">
                                <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Ordine inviato il', 'value' => humanData($entity->created_at)]]) ?>
                                <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Stato Ordine (' . humanData($entity->last_status_change) . ')', 'value' => (isset($all_order_status_labels[$entity->order_status])) ? $all_order_status_labels[$entity->order_status] : $entity->order_status]]) ?>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <hr />
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Spedizione', 'value' => $entity->spedizione_name]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Tipo spedizione', 'value' => (isset($all_spedizioni_type_labels[$entity->spedizione_type])) ? $all_spedizioni_type_labels[$entity->spedizione_type] : $entity->spedizione_type]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'spese di spedizione €', 'name' => 'spese_spedizione', 'value' => $entity->spese_spedizione, 'step' => '0.01', 'decimal' => 2]]) ?>
                            <?php /*
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'spese spedizione imponibile €', 'name' => 'spese_spedizione_imponibile', 'value' => $entity->spese_spedizione_imponibile, 'step' => '0.01', 'decimal' => 2]]) ?> 
                            */ ?>
                        </div>
                        <div class="row">
                            <hr />
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'imponibile €', 'name' => 'imponibile_total', 'value' => $entity->imponibile_total, 'step' => '0.01', 'decimal' => 2]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'iva €', 'name' => 'iva_total', 'value' => $entity->iva_total, 'step' => '0.01', 'decimal' => 2]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'totale ordine €', 'name' => 'total', 'value' => $entity->total, 'step' => '0.01', 'decimal' => 2]]) ?>
                            <?php /*
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'promo_total', 'name' => 'promo_total', 'value' => $entity->promo_total, 'step' => '0.01', 'decimal' => 2]]) ?> 
                            */ ?>

                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Totale pagato €', 'name' => 'pay_total', 'value' => $entity->pay_total, 'step' => '0.01', 'decimal' => 2]]) ?>
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Tipo pagamento', 'value' => (isset($all_payment_type_labels[$entity->payment_type])) ? $all_payment_type_labels[$entity->payment_type] : $entity->payment_type]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Stato pagamento (' . humanData($entity->payed_at) . ')', 'value' => (isset($all_payment_status_labels[$entity->payment_status])) ? $all_payment_status_labels[$entity->payment_status] : $entity->payment_status]]) ?>
                        </div>

                        <div class="row">
                            <hr />
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Peso (g)', 'name' => 'peso_totale_grammi', 'value' => $entity->peso_totale_grammi, 'step' => '0.01', 'decimal' => 2]]) ?>
                            <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Peso (kg)', 'name' => 'peso_totale_kg', 'value' => $entity->peso_totale_kg, 'step' => '0.01', 'decimal' => 2]]) ?>
                        </div>

                        <div class="row">
                            <hr />
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>

<script type="text/javascript">
    $(document).ready(function() {
    });
</script>

<style>

    .lista_prodotti_ordine {
        width: 100%;
        margin: 0 0 2rem 0;
    }
    .lista_prodotti_ordine thead th {
        text-align: left;
        background-color: #5cb5ff;
        color: #FFF;
        padding: 0.8rem .8rem;
        border-bottom: 1px solid #FFF;
    }
    .lista_prodotti_ordine thead tr th:last-child,
    .lista_prodotti_ordine tbody tr td:last-child {
        text-align: right;
    }

    .lista_prodotti_ordine tbody td {
        padding: 0.8rem .8rem;
    }

    .lista_prodotti_ordine tbody tr:nth-child(odd) td {
        background-color: #FFF;
        border: none;
        border-bottom: 1px solid #f2f3f7;
    }

    .lista_prodotti_ordine tbody tr:nth-child(even) td {
        background-color: #d6e9f8;
        border-bottom: 1px solid #FFF;
    }

    .lista_prodotti_ordine .btn {
        padding: 0.3rem .7rem;
        margin: 0;
    }

    .lista_prodotti_ordine tbody tr.order_row_status_CART td{
       opacity: 0.5;
    }




    .input-tools-cnt {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
        padding: 1em;
    }

    .input-tool-item {
        padding: .8em .5em;
        margin: .8em .5em;
        background-color: #f8f9fa;
    }

    .input-tools-row {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: .3em .2em;
    }

    .input-tools-row input {
        width: auto
    }

    .input-tools-from {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .input-tools-result {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;

    }

    .input-tools-result-label {
        font-size: .7em;
        font-weight: bold;
        margin-right: .5em;
    }
</style>


<?= $this->endSection() ?>