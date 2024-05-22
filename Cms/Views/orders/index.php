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
    <div class="listIndexTableCnt">
        <table class="listIndexTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ordine</th>
                    <th>Data</th>
                    <th>Stato Ordine</th>
                    <th>Stato Pagamento</th>
                    <th>Tools</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $item) { ?>
                    <tr class="order_row order_row_status_<?= $item->order_status ?>">
                        <td><?= $item->id ?></td>
                        <td>
                            <a href="<?= site_url(route_to($route_prefix . '_edit', $item->id)) ?>">Ordine da <?= $item->user->name ?> <?= $item->user->surname ?></a>
                        </td>
                        <td><?= humanData($item->created_at) ?></td>
                        <td>
                            <small><?= $item->order_status ?></small>
                        </td>
                        <td>
                            <small><?= $item->payment_type ?>/<?= $item->payment_status ?></small>
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm a_delete" href="<?= site_url(route_to($route_prefix . '_delete',  $item->id)) ?>" data-bs-toggle="tooltip" title="Elimina"><span class="oi oi-trash"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php } else { ?>
    <div class="alert alert-info">Nessun ordine presente</div>
<?php } ?>

<?= $this->endSection() ?>
<?= $this->section('footer_script') ?>


<style>
    .listIndexTableCnt{
        padding: 1.5rem 1.5rem;
        background: #FFF;
        border-radius: 10px;
    }
    .listIndexTable {
        width: 100%;
        margin: 0;
    }

    .listIndexTable thead th {
        text-align: left;
        background-color: #5cb5ff;
        color: #FFF;
        padding: 0.8rem .8rem;
        border-bottom: 1px solid #FFF;
    }
    .listIndexTable thead tr th:last-child,
    .listIndexTable tbody tr td:last-child {
        text-align: right;
    }

    .listIndexTable tbody td {
        padding: 0.8rem .8rem;
    }

    .listIndexTable tbody tr:nth-child(odd) td {
        background-color: #FFF;
        border: none;
        border-bottom: 1px solid #f2f3f7;
    }

    .listIndexTable tbody tr:nth-child(even) td {
        background-color: #d6e9f8;
        border-bottom: 1px solid #FFF;
    }

    .listIndexTable .btn {
        padding: 0.3rem .7rem;
        margin: 0;
    }

    .listIndexTable tbody tr.order_row_status_CART td{
       opacity: 0.5;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
<?= $this->endSection() ?>