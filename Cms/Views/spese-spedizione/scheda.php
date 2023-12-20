<?= $this->extend('Lc5\Cms\Views\layout/body') ?>
<?= $this->section('content') ?>
<?= view('Lc5\Cms\Views\layout/components/tools_tabs') ?>
<script>
    let count_rows = 0;
</script>
<form class="form" method="POST" action="">
    <?= user_mess($ui_mess, $ui_mess_type) ?>
    <div class="settings_header">
        <h1><?= $module_name ?></h1>
    </div>
    <div class="row form-row">

        <div class="col-12 col-lg-9 scheda_body">
            <div class="first-row">
                <div class="row">
                    <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nome', 'name' => 'nome', 'value' => $entity->nome, 'width' => 'col-md-12', 'placeholder' => 'Nome']]) ?>
                    <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Titolo', 'name' => 'titolo', 'value' => $entity->titolo, 'width' => 'col-md-12', 'placeholder' => 'Titolo']]) ?>
                </div>
                <div class="row form-row">
                    <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Peso max (Kg)', 'name' => 'peso_max', 'value' => $entity->peso_max, 'placeholder' => 'Peso massimo (grammi)', 'step' => '0.01', 'decimal' => 2]]) ?>
                    <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Prezzo Imponibile', 'name' => 'prezzo_imponibile', 'value' => $entity->prezzo_imponibile, 'placeholder' => 'Prezzo Imponibile', 'step' => '0.01', 'decimal' => 2]]) ?>
                    <?= view('Lc5\Cms\Views\form-cmp/number', ['item' => ['label' => 'Aliquota Iva', 'name' => 'prezzo_aliquota', 'value' => $entity->prezzo_aliquota, 'placeholder' => 'Iva %']]) ?>
               
                    <?php /*
                    <?= view('Lc5\Cms\Views\form-cmp/text', ['item' => ['label' => 'Nazione', 'name' => 'nazione', 'value' => $entity->nazione, 'width' => 'col-md-12', 'placeholder' => 'Nazione']]) ?>
                    */ ?>
                    <?= view('Lc5\Cms\Views\form-cmp/select', ['item' => ['label' => 'Consegna in', 'name' => 'consegna', 'value' => $entity->consegna, 'width' => 'col-md-12', 'sources' => $spedizioni_per_list, 'no_empty' => true]]) ?>

                </div>
                <div class="row form-row">
                    <?= view('Lc5\Cms\Views\form-cmp/html-editor', ['item' => ['label' => 'Testo', 'name' => 'testo', 'value' => (isset($entity->testo)) ? $entity->testo : '', 'width' => 'col-md-12', 'placeholder' => '...']]) ?>
                </div>

            </div>

        </div>
        <div class="scheda-sb margin-top-0">
            <div class="row">
                <div class="col-12">
                    <button type="submit" name="save" value="save" class="btn bottone_salva btn-primary"><span class="oi oi-check"></span>Salva</button>
                </div>
                <div class="col-12">
                    <div class="bg-light p-4 px-lg-2 py-lg-3 rounded">
                        <div class="col-sm-8 col-lg-12">
                            <div class="row">
                                <?= view('Lc5\Cms\Views\form-cmp/readonly', ['item' => ['label' => 'Guid', 'value' => $entity->guid, 'width' => 'col-12', 'placeholder' => '']]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/select', ['item' => ['label' => 'Public', 'name' => 'public', 'input_class' => 'public', 'value' => $entity->public, 'width' => 'col-md-12', 'sources' => $bool_values, 'no_empty' => true]]); ?>
                        </div>
                        <div class="row">
                            <?= view('Lc5\Cms\Views\form-cmp/select', ['item' => ['label' => 'Default', 'name' => 'is_default', 'input_class' => 'is_default', 'value' => $entity->is_default, 'width' => 'col-md-12', 'sources' => $bool_values, 'no_empty' => true]]); ?>
                            <?= view('Lc5\Cms\Views\form-cmp/select', ['item' => ['label' => 'Gratuita', 'name' => 'is_free', 'input_class' => 'is_free', 'value' => $entity->is_free, 'width' => 'col-md-12', 'sources' => $bool_values, 'no_empty' => true]]); ?>
                        </div>
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
    $(document).ready(function() {});
</script>



<?= $this->endSection() ?>