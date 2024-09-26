<?php if (isset($single_items) && $single_items != null) { ?>
	<div class="lcshop-card <?= ($single_items->in_promo) ? ' is_in_promo' : '' ?>">
		<form method="post" name="add_to_cart_form">

			<?= (isset($single_items->permalink) && $single_items->permalink != null) ? '<a href="' . $single_items->permalink . '" title="' . $single_items->titolo . '" class="shop_product_link" >' : '' ?>
			<?= single_img($single_items->main_img_path, 'thumbs', 'shop_product_thumb', 'Immagine ' . $single_items->titolo) ?>

			<div class="lcshop-card-dettagli">
				<div class="lcshop-card_dettagli-txts">
					<?= h5($single_items->titolo) ?>
				</div>
				<div class="shop_product_prices">
					<?php if ($single_items->in_promo) { ?>
						<div class="price price_promo">
							<span class="price_coin"><?= $single_items->prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
						<div class="price price_nosale">
							<span class="price_coin"><?= $single_items->prezzo_pieno_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
					<?php } else { ?>
						<div class="price">
							<span class="price_coin"><?= $single_items->prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
					<?php } ?>
				</div>
			</div>
			<?= (isset($single_items->permalink) && $single_items->permalink != null) ? '</a>' : '' ?>
			<input type="hidden" name="prod_id" value="<?= $single_items->id ?>">
			<!-- MODELLI -->
			<?php if (isset($single_items->modelli) && is_array($single_items->modelli)) { ?>
				<?php if (count($single_items->modelli) > 1) { ?>
					<select name="prod_model_id" class="sel_prod_model_id">
						<?php foreach ($single_items->modelli as $modello) { ?>
							<option value="<?= $modello->id ?>"><?= $modello->full_nome_prodotto ?></option>
						<?php } ?>
					</select>
				<?php } else { ?>
					<input type="hidden" name="prod_model_id" value="<?= $single_items->modelli[0]->id ?>" />
				<?php } ?>
			<?php } else { ?>
				<input type="hidden" name="prod_model_id" value="<?= $single_items->id ?>" />
			<?php } ?>
			<?= view(customOrDefaultViewFragment('shop/components/add_to_cart_component',  'LcShop'), ['giacenza' => $single_items->giacenza]) ?>
			<div class="product_giac_mess <?= (intval($single_items->giacenza) > 0) ? 'available' : 'unavailable'  ?>">
				<div class="prodotto_esaurito prodotto_esaurito_detail">Prodotto esaurito</div>
			</div>
		</form>
	</div>
<?php } ?>
<?php /*
<?php if (isset($single_items) && $single_items != null) { ?>
	<div class="shop_product_card <?= ($single_items->in_promo) ? ' is_in_promo' : '' ?>">
		<form method="post" name="add_to_cart_form">

			<?= (isset($single_items->permalink) && $single_items->permalink != null) ? '<a href="' . $single_items->permalink . '" title="' . $single_items->titolo . '" class="shop_product_link" >' : '' ?>
			<?= single_img($single_items->main_img_path, 'thumbs', 'shop_product_thumb', 'Immagine ' . $single_items->titolo) ?>

			<div class="shop_product_card_dettagli">
				<div class="shop_product_card_dettagli_txts">
					<?= h5($single_items->titolo) ?>
				</div>
				<div class="shop_product_prices">
					<?php if ($single_items->in_promo) { ?>
						<div class="price price_promo">
							<span class="price_coin"><?= $single_items->prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
						<div class="price price_nosale">
							<span class="price_coin"><?= $single_items->prezzo_pieno_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
					<?php } else { ?>
						<div class="price">
							<span class="price_coin"><?= $single_items->prezzo_coin ?></span><span class="price_spacer">/</span><span class="price_um"><?= $single_items->um ?></span>
						</div>
					<?php } ?>
				</div>
			</div>
			<?= (isset($single_items->permalink) && $single_items->permalink != null) ? '</a>' : '' ?>
			<input type="hidden" name="prod_id" value="<?= $single_items->id ?>">
			<!-- MODELLI -->
			<?php if (isset($single_items->modelli) && is_array($single_items->modelli)) { ?>
				<?php if (count($single_items->modelli) > 1) { ?>
					<select name="prod_model_id" class="sel_prod_model_id">
						<?php foreach ($single_items->modelli as $modello) { ?>
							<option value="<?= $modello->id ?>"><?= $modello->full_nome_prodotto ?></option>
						<?php } ?>
					</select>
				<?php } else { ?>
					<input type="hidden" name="prod_model_id" value="<?= $single_items->modelli[0]->id ?>" />
				<?php } ?>
			<?php } else { ?>
				<input type="hidden" name="prod_model_id" value="<?= $single_items->id ?>" />
			<?php } ?>
			<?= view(customOrDefaultViewFragment('shop/components/add_to_cart_component',  'LcShop'), ['giacenza' => $single_items->giacenza]) ?>
			<div class="product_giac_mess <?= (intval($single_items->giacenza) > 0) ? 'available' : 'unavailable'  ?>">
				<div class="prodotto_esaurito prodotto_esaurito_detail">Prodotto esaurito</div>
			</div>
		</form>
	</div>
<?php } ?>
*/ ?>