{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" {if Context::getContext()->controller->php_self !== 'product'}itemscope itemtype="http://schema.org/Product"{/if}>
  <div class="thumbnail-container">
    {block name='product_thumbnail'}
      <a href="{$product.url}" {if Context::getContext()->controller->php_self !== 'product'}itemprop="url"{/if} class="thumbnail product-thumbnail">
        <img
          src = "{$product.cover.bySize.home_default.url}"
          alt = "{$product.cover.legend}"
          data-full-size-image-url = "{$product.cover.large.url}"
          {if Context::getContext()->controller->php_self !== 'product'}
          itemprop="image"
          {/if}
        >
      </a>
    {/block}

    <div class="product-description">
        {hook h='displayRatingProduct' id_product={$product.id_product} type='category'}
        {block name='product_name'}
        <span class="h3 product-title" {if Context::getContext()->controller->php_self !== 'product'}itemprop="name"{/if}><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></span>
          <span class="hidden-sku" {if Context::getContext()->controller->php_self !== 'product'} itemprop="sku" {/if}>{$product.reference}</span>
          <div {if Context::getContext()->controller->php_self !== 'product'}itemprop="description"{/if} class="product-description-list">{$product.description_short|truncate:200:'...' nofilter}</div>
      {/block}

      {block name='product_price_and_shipping'}
        {if $product.show_price}
          <div class="product-price-and-shipping" {if Context::getContext()->controller->php_self !== 'product'}itemprop="offers" itemscope itemtype="https://schema.org/Offer"{/if}>
            {if $product.has_discount}
              {hook h='displayProductPriceBlock' product=$product type="old_price"}

              <span class="regular-price">{$product.regular_price}</span>
              {if $product.discount_type === 'percentage'}
                <span class="discount-percentage">{$product.discount_percentage}</span>
              {/if}
            {/if}

            {hook h='displayProductPriceBlock' product=$product type="before_price"}

            <span  class="price">{$product.price}</span>

              {if Context::getContext()->controller->php_self !== 'product'}
                  <meta itemprop="price" content="{preg_replace("/[^0-9\.\-]/","",$product.price)}" />
                  <meta itemprop="priceCurrency" content="{Context::getContext()->currency->iso_code}" />

                  {if $product.availability == 'available' || $product.availability == 'last_remaining_items'}
                      <link itemprop="availability" href="https://schema.org/InStock"/>
                  {else}
                      <link itemprop="availability" href="https://schema.org/OutOfStock"/>
                  {/if}
              {/if}



            {hook h='displayProductPriceBlock' product=$product type='unit_price'}

            {hook h='displayProductPriceBlock' product=$product type='weight'}
          </div>
        {/if}
      {/block}
    </div>
    {block name='product_flags'}
      <ul class="product-flags">
        {foreach from=$product.flags item=flag}
            <li class="{$flag.type}">{$flag.label}</li>
        {/foreach}
      </ul>
    {/block}
    <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
      <span

        class="quick-view"
        data-link-action="quickview"
      > <i class="material-icons search">search</i> </span>
        <span class="h3 product-title product-title-hidden"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></span>
        <div class="product-add-to-cart">
            <form action="{Context::getContext()->link->getPageLink('cart',true)}" method="post" class="add-to-cart-or-refresh">
                <input type="hidden" name="token" value="{Tools::getToken(false)}">
                <input type="hidden" name="id_product" value="{$product.id}" class="product_page_product_id">
                <input type="hidden" name="id_customization" value="0" class="product_customization_id">
                <button class="btn btn-primary add-to-cart " data-button-action="add-to-cart" type="submit" {if $product.availability == 'available' || $product.availability == 'last_remaining_items'}{else}disabled{/if} >
                    <span class="icon-add-to-cart"></span>
                    {l s='Add to cart' mod='featuredproducts'}
                </button>
            </form>
        </div>
    </div>


    <div style="clear: both"></div>
  </div>
</article>
