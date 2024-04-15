<div class="agg_cart_cnt <?= (intval($giacenza) > 0) ? 'available' : 'unavailable'  ?>">
    <div class="qty">
        <button type="button" class="less" onclick="const input = this.parentNode.querySelector('.qty_input'); input.value = Math.max(parseInt(input.value) - 1, 1); return;" value="-1">-</button>
        <input class="qty_input" name="prod_qty" type="number" min="1" max="<?= ($giacenza) ? $giacenza : 10 ?>" step="1" value="1" \>
        <button type="button" class="more" onclick="const input = this.parentNode.querySelector('.qty_input'); input.value = Math.min(parseInt(input.value) + 1, parseInt(input.max )); return;" value="+1">+</button>
    </div>
    <button type="submit" name="cart_action" value="ADD" class="cart_in">
        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="30" viewBox="0 0 37 30">
            <path class="cart_icon_pat" d="M257.042,396.3H234.391l-.569-2.967h24.5l2.974-15.165H230.949l-1.093-5.769H224.3v1.154h4.6l4.326,22.8a3.046,3.046,0,1,0,3.579,3,3.018,3.018,0,0,0-.68-1.9h18.547a3.018,3.018,0,0,0-.68,1.9,3.049,3.049,0,1,0,3.05-3.05Zm-7.974-4.121h-3l.029-12.857h3.833Zm2.016-12.857h3.829l-1.69,12.857h-3Zm-6.173,12.857h-3l-.8-12.857h3.834Zm-4.159,0h-3l-1.632-12.857h3.829Zm16.623,0h-2.989l1.69-12.857h3.82Zm-22.418-12.857,1.633,12.857H233.6l-2.435-12.857Zm.694,20.028a1.9,1.9,0,1,1-1.9-1.9A1.9,1.9,0,0,1,235.651,399.351Zm21.391,1.895a1.9,1.9,0,1,1,1.9-1.895A1.9,1.9,0,0,1,257.042,401.246Z" transform="translate(-224.298 -372.4)"></path>
        </svg>
    </button>
</div>
