<?php if (isset($products_list) && is_iterable($products_list) && count($products_list) > 0) { ?>
	<?php foreach ($products_list as $single) { ?>
		<?= view(customOrDefaultViewFragment('shop/components/product-listing-card',  'LcShop'), ['single_items' => $single]) ?>
	<?php } ?>
<?php } ?>