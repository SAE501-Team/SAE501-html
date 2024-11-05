<div class="productsBlockLeftMenu" data-count="{count($products)|escape:'htmlall':'UTF-8'}">
    <div class="products_list">
        {foreach from=$products item=product}
            <div class="one_product" >
                {include file="catalog/_partials/miniatures/product.tpl" product=$product}
            </div>
        {/foreach}
    </div>
</div>
