<div class="productsBlock">
    <div class="products_list">
        {foreach from=$products item=product}
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
        {/foreach}
    </div>
</div>
