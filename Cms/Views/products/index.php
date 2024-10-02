<?= $this->extend('Lc5\Cms\Views\layout/body') ?>
<?= $this->section('content') ?>
<div class="d-md-flex">
    <h1><?= $module_name ?></h1>
    <div class="d-flex align-items-center">
        <div>
            <a class="btn btn-primary" href="<?= site_url(route_to($route_prefix . '_new')) ?>"><span class="oi oi-plus mr-1"></span>Crea nuovo</a>
        </div>
    </div>
</div>
<?= user_mess($ui_mess, $ui_mess_type) ?>
<?php if (count($list) > 0) { ?>
    <div class="listIndex">
        <ul>
            <?php foreach ($list as $item) { ?>
                <li>
                    <div class="list_item_row">
                        <div class="list_item_id"><?= $item->id ?></div>
                        <div class="list_item_nome">
                            <a href="<?= site_url(route_to($route_prefix . '_edit', $item->id)) ?>"><?= $item->nome ?></a>
                            <br /><small><?= $item->modello ?></small>
                            <?php if( isset($item->category_obj) && isset($item->category_obj->nome) ) { ?>
                                <br /><small>Categoria: <?= $item->category_obj->nome ?></small>
                            <?php } ?>
                        </div>
                        <div class="list_item_dati">
                            <div class="list_item_prezzo"><?= $item->prezzo ?> €</div>
                        </div>
                        <div class="list_item_dati">
                            <div class="list_item_giacenza"><?= $item->giacenza ?> / <?= $item->um ?></div>
                        </div>
                        <div class="list_item_tools">
                            <a class="btn btn-danger btn-sm a_delete" href="<?= site_url(route_to($route_prefix . '_delete',  $item->id)) ?>" data-bs-toggle="tooltip" title="Elimina"><span class="oi oi-trash"></span></a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } else { ?>
<?php } ?>

<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>
<style>
    .list_item_row {
        display: grid;
        grid-template-columns: 50px 2fr 1fr 1fr 50px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
<?= $this->endSection() ?>