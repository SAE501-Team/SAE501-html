<div 
  class="product-recommendations"
  data-aos="fade-up"
  data-aos-duration="1000"
>
  <h1 
    class="text-center text-2xl sm:text-3xl md:text-4xl font-bold text-pretty"
    data-aos="fade-down"
    data-aos-duration="1000"
  >
    Céréales recommandées pour vous
  </h1>
  
  {if $products && $products|@count > 0}
    <div 
      class="products row mt-1"
      data-aos="fade-up"
      data-aos-delay="200"
    >
      {foreach from=$products item="product" key="position"}
        <div 
          class="product-container"
          data-aos="zoom-in"
          data-aos-delay="{$position * 100}"
        >
          {include file="catalog/_partials/miniatures/product.tpl" 
            product=$product 
            position=$position 
            productClasses="col-xs-12 col-sm-6 col-lg-4"}
        </div>
      {/foreach}
    </div>
  {else}
    <p data-aos="fade-in" data-aos-duration="800">Aucun produit recommandé pour l'instant.</p>
  {/if}
</div>
