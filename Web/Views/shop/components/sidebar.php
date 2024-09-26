<aside class="lcshop-sidebar">
    <?= view(customOrDefaultViewFragment('shop/components/mini-cart', 'LcShop')) ?>
    <?php if (isset($categories) && is_array($categories) && count($categories) > 0) { ?>
        <div class="lcshop-category-list-cnt">
            <div class="lcshop-sidebar-tit"><?= appLabel('Categorie', $app->labels, true) ?></div>
            <ul class="lcshop-category-list">
                <?php foreach ($categories as $category) { ?>
                    <li>
                        <a class="<?= (isset($shop_category_guid) && $shop_category_guid == $category->guid) ? 'is_current' : '' ?>" href="<?= $category->permalink ?>"><?= $category->nome ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</aside>