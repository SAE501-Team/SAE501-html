<div class="product-recommendations">
    <h2 class="h2">Produits recommandés pour vous</h2>
    {if $products && $products|@count > 0}
        <div class="products row">
            {foreach from=$products item="product" key="position"}
                {include file="catalog/_partials/miniatures/product.tpl" 
                    product=$product 
                    position=$position 
                    productClasses="col-xs-12 col-sm-6 col-lg-4"}
            {/foreach}
        </div>
    {else}
        <p>Aucun produit recommandé pour l'instant.</p>
    {/if}
</div>

