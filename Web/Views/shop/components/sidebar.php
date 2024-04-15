<aside class="sidebar shop_sidebar">
    <?= view(customOrDefaultViewFragment('shop/components/mini-cart', 'LcShop')) ?>
    <?php if (isset($categories) && is_array($categories) && count($categories) > 0) { ?>
        <h4><?= appLabel('Categorie', $app->labels, true) ?></h4>
        <ul class="shop_category_list">
            <?php foreach ($categories as $category) { ?>
                <li>
                    <a href="<?= $category->permalink ?>"><?= $category->nome ?></a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</aside>