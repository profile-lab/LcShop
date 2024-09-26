<nav class="lcshop-breadcrumb">
    <div class="myIn">
        <ul class="breadcrumb">
            <li><a href="<?= route_to('web_homepage') ?>" title="Homepage">Home</a></li>
            <li><a href="<?= route_to('web_shop_home') ?>" title="Shop">Shop</a></li>
            <?php if (isset($category_obj) && $category_obj) { ?>
                <li><a href="<?= route_to('web_shop_category', $category_obj->guid) ?>" title="category"><?= $category_obj->nome ?></a></li>
                <?php }else if(isset($shop_page_type) && $shop_page_type == 'shop_category' && isset($shop_category_guid) && trim($shop_category_guid) && isset($nome) && trim($nome)){ ?>
                    <li><a href="<?= route_to('web_shop_category', $shop_category_guid) ?>" title="category"><?= $nome ?></a></li>

            <?php } ?>
            <?php if (isset($prod_id) && $prod_id > 0 && isset($guid) && trim($guid) && isset($nome) && trim($nome)) { ?>
                <li><a href="<?= route_to('web_shop_detail', $guid) ?>" title="category"><?= $nome ?></a></li>
            <?php } ?>
        </ul>
    </div>
</nav>