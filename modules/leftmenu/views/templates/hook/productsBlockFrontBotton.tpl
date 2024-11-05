<div class="productsBlockLeftMenuBottom" data-count="{count($products)|escape:'htmlall':'UTF-8'}">
    <div id="products_list_bottom">
        {foreach from=$products item=product}
            <div class="one_product one_product_bottom" >
                {include file="catalog/_partials/miniatures/product.tpl" product=$product}
            </div>
        {/foreach}
    </div>
</div>
