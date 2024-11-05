
{if $products}
    <div data-count="{count($products)|escape:'htmlall':'UTF-8'}" id="products_list_featured" class="featured-products {if count($products)>3} products_featured_slider {else} products_featured_noslider{/if}">
        {foreach from=$products item="product"}
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
        {/foreach}
    </div>
{/if}