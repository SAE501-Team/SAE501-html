{** * Copyright since 2007 PrestaShop SA and Contributors * PrestaShop is an
International Registered Trademark & Property of PrestaShop SA * * NOTICE OF
LICENSE * * This source file is subject to the Academic Free License 3.0
(AFL-3.0) * that is bundled with this package in the file LICENSE.md. * It is
also available through the world-wide-web at this URL: *
https://opensource.org/licenses/AFL-3.0 * If you did not receive a copy of the
license and are unable to * obtain it through the world-wide-web, please send an
email * to license@prestashop.com so we can send you a copy immediately. * *
DISCLAIMER * * Do not edit or add to this file if you wish to upgrade PrestaShop
to newer * versions in the future. If you wish to customize PrestaShop for your
* needs please refer to https://devdocs.prestashop.com/ for more information. *
* @author PrestaShop SA and Contributors
<contact@prestashop.com>
  * @copyright Since 2007 PrestaShop SA and Contributors * @license
  https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0) *}
  {block name='product_miniature_item'}
  <div
    class="js-product product{if !empty($productClasses)} {$productClasses}{/if}"
  >
    <article
      class="product-miniature js-product-miniature"
      data-id-product="{$product.id_product}"
      data-id-product-attribute="{$product.id_product_attribute}"
    >
      <div class="thumbnail-container">
        <div class="thumbnail-top border-3 border-black mb-4">
          <!-- une vigniette produit-->
          {block name='product_thumbnail'} {if $product.cover}
          <a
            href="{$product.url}"
            class="thumbnail product-thumbnail relative w-auto aspect-square overflow-hidden group"
          >
            <picture>
              {if !empty($product.cover.bySize.home_default.sources.avif)}
              <source
                srcset="{$product.cover.bySize.home_default.sources.avif}"
                type="image/avif"
              />
              {/if} {if !empty($product.cover.bySize.home_default.sources.webp)}
              <source
                srcset="{$product.cover.bySize.home_default.sources.webp}"
                type="image/webp"
              />
              {/if}
              <img
                src="{$product.cover.bySize.home_default.url}"
                alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
                loading="lazy"
                data-full-size-image-url="{$product.cover.large.url}"
                width="{$product.cover.bySize.home_default.width}"
                height="{$product.cover.bySize.home_default.height}"
                class="w-full h-full object-cover {if $product.images|@count > 1} transition ease-out duration-500 group-hover:blur{/if}"
              />
            </picture>
            {if $product.images|@count > 1}
            <!-- Vérifie qu'il y a plus d'une image -->
            {assign var="second_image" value=$product.images[1]}
            <!-- Récupère la deuxième image (index 1) -->

            <div
              class="absolute inset-0 flex justify-center items-center opacity-0 transition-opacity ease-out duration-500 group-hover:opacity-100"
            >
              <div
                class="w-3/4 h-3/4 border-2 border-black shadow-[inset_-4px_5px_25.3px_14px_rgba(0,0,0,0.45)] bg-cover bg-center"
                style="
                  background-image: url('{$second_image.bySize.home_default.url}');
                "
              ></div>
            </div>
            {/if}
          </a>
          {else}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <picture>
              {if
              !empty($urls.no_picture_image.bySize.home_default.sources.avif)}
              <source
                srcset="
                  {$urls.no_picture_image.bySize.home_default.sources.avif}
                "
                type="image/avif"
              />
              {/if} {if
              !empty($urls.no_picture_image.bySize.home_default.sources.webp)}
              <source
                srcset="
                  {$urls.no_picture_image.bySize.home_default.sources.webp}
                "
                type="image/webp"
              />
              {/if}
              <img
                src="{$urls.no_picture_image.bySize.home_default.url}"
                loading="lazy"
                width="{$urls.no_picture_image.bySize.home_default.width}"
                height="{$urls.no_picture_image.bySize.home_default.height}"
              />
            </picture>
          </a>
          {/if} {/block}


          <div
            class="highlighted-informations{if !$product.main_variants} no-variants{/if} border-t-3 border-black"
          >
            {block name='quick_view'}
            <a
              class="quick-view js-quick-view !text-black group transition ease-out duration-500 hover:!text-slate-500"
              href="#"
              data-link-action="quickview"
            >
              <i
                class="material-icons search transition ease-out duration-500 group-hover:rotate-90"
                >&#xE8B6;</i
              >{l s='Quick view' d='Shop.Theme.Actions'}
            </a>
            {/block} {block name='product_variants'} {if $product.main_variants}
            {include file='catalog/_partials/variant-links.tpl'
            variants=$product.main_variants} {/if} {/block}
          </div>
        </div>

        {block name='product_flags'}
          {assign var="positions" value=["right-0", "right-[63px]", "right-[125px]"]}

          <div class="font-adlam h-[32px] text-white">
            {foreach from=$product_categories item=categorie} {if $categorie.name
            == "Mélange Magique" || $categorie.name == "Magic Blend"}
              {assign var="currentPosition" value=$positions|@array_shift}
              <div
                class="{$currentPosition} mt-[-27px] bg-gradient-to-b from-be-jaune to-be-vert border-3 border-white absolute z-10 h-[56px] w-[56px] sm:h-[71px] sm:w-[71px] flex items-center justify-center text-[10px] sm:text-[11px] rounded-full rotate-[-25deg] pl-[6px] sm:pl-[12px] leading-3"
              >
                {$categorie.name}
              </div>
            {/if} {/foreach} {foreach from=$product.flags item=flag} {if
            $flag.label == "Nouveau" || $flag.label == "New"}
              {assign var="currentPosition" value=$positions|@array_shift}
              <div
                class="{$currentPosition} mt-[-27px] majuscule bg-gradient-to-b from-be-bleu to-be-rose border-3 border-white absolute z-10 h-[56px] w-[56px] sm:h-[71px] sm:w-[71px] flex items-center justify-center text-[9px] sm:text-[10px] rounded-full rotate-[-25deg] right-0"
              >
                {$flag.label}
              </div>
            {/if} 
            {if
              $flag.label == "Nouveau" || $flag.label == "New"}
                {assign var="currentPosition" value=$positions|@array_shift}
                <div
                  class="{$currentPosition} mt-[-27px] majuscule bg-gradient-to-b from-be-jaune to-be-rose border-3 border-white absolute z-10 h-[56px] w-[56px] sm:h-[71px] sm:w-[71px] flex items-center justify-center text-[9px] sm:text-[10px] rounded-full rotate-[-25deg] right-0"
                >
                  {$flag.label}
                </div>
              {/if}
            {/foreach}
          </div>
        {/block}
        {block name='product_reviews'} {var_dump($product.productComments)} {/block}

        <div
          class="product-description font-adlam !text-black flex justify-between flex-col"
        >
          <div class="flex gap-2 items-center">
            <!-- Étoile/avis -->
            <div class="rating">les étoiles</div>
            <!-- Nombre d'avis -->
            <div>23 avis</div>
          </div>
          {block name='product_name'} {if $page.page_name == 'index'}
          <h3 class="h3 product-title text-lg !text-left">
            {$product.name|truncate:30:'...'}
          </h3>
          {else}
          <h2 class="h3 product-title text-lg !text-left">
            {$product.name|truncate:30:'...'}
          </h2>
          {/if} {/block}

          <div>
            <div class="flex gap-1 items-end justify-between">
              <!-- Infos -->
              <div>
                {if isset($product.features)} {assign var="compositions"
                value=[]} {foreach from=$product.features item=feature} {if
                $feature.name == "Composition"} {assign var="compositions"
                value=$compositions|@array_merge:[$feature.value]} {/if}
                {/foreach} {if $compositions|@count > 0}
                <div>
                  Goûts : {foreach from=$compositions item=value key=index} {if
                  $index == $compositions|@count - 1 && $index > 0} et {/if}
                  {$value}{if $index < $compositions|@count - 2}, {/if}
                  {/foreach}
                </div>
                {/if}

                <!-- Gestion des Propriétés -->
                {assign var="proprietes" value=[]} {foreach
                from=$product.features item=feature} {if $feature.name ==
                "Propriété"} {assign var="proprietes"
                value=$proprietes|@array_merge:[$feature.value]} {/if}
                {/foreach} {if $proprietes|@count > 0}
                <div>
                  Forme : {foreach from=$proprietes item=value key=index} {if
                  $index == $proprietes|@count - 1 && $index > 0} et {/if}
                  {$value}{if $index < $proprietes|@count - 2}, {/if} {/foreach}
                </div>
                {/if} {/if}
              </div>
              {block name='product_price_and_shipping'} {if $product.show_price}
              <div class="product-price-and-shipping !text-be-rose text-lg">
                {if $product.has_discount} {hook h='displayProductPriceBlock'
                product=$product type="old_price"}

                <span
                  class="regular-price !text-be-rose/45"
                  aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}"
                  >{$product.regular_price}</span
                >
                {if $product.discount_type === 'percentage'}
                <span class="discount-percentage discount-product"
                  >{$product.discount_percentage}</span
                >
                {elseif $product.discount_type === 'amount'}
                <span class="discount-amount discount-product"
                  >{$product.discount_amount_to_display}</span
                >
                {/if} {/if} {hook h='displayProductPriceBlock' product=$product
                type="before_price"}

                <span
                  class="price"
                  aria-label="{l s='Price' d='Shop.Theme.Catalog'}"
                >
                  {capture name='custom_price'}{hook
                  h='displayProductPriceBlock' product=$product
                  type='custom_price' hook_origin='products_list'}{/capture} {if
                  '' !== $smarty.capture.custom_price}
                  {$smarty.capture.custom_price nofilter} {else}
                  {$product.price} {/if}
                </span>

                {hook h='displayProductPriceBlock' product=$product
                type='unit_price'} {hook h='displayProductPriceBlock'
                product=$product type='weight'}
              </div>
              {/if} {/block}
            </div>
            <div class="flex items-end gap-4 mt-2 justify-around">
              <a
                href="{$product.url}"
                content="{$product.url}"
                class="bg-be-jaune text-white !py-1 !px-4 !rounded border-3 border-be-jaune transition-all ease-out duration-500 hover:bg-transparent hover:!text-be-jaune"
              >
                Voir
              </a>
              {block name='quick_view'} {assign var="incart" value=""}
              <!-- Initialisation de la variable Smarty -->
              {if $cart.products} {foreach from=$cart.products
              item=cart_product} {if $cart_product.id_product == $product.id}
              {assign var="incart" value="incart"} {/if} {/foreach} {/if}
              <a
                class="quick-view js-quick-view {$incart}"
                href="#"
                data-link-action="quickview"
              >
                <div>
                  <div class="pops"></div>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 29 29"
                    height="29"
                    width="29"
                    class="chart"
                  >
                    <path
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="3.33333"
                      stroke="black"
                      d="M12.0833 25.9792C12.7507 25.9792 13.2917 25.4382 13.2917 24.7708C13.2917 24.1035 12.7507 23.5625 12.0833 23.5625C11.416 23.5625 10.875 24.1035 10.875 24.7708C10.875 25.4382 11.416 25.9792 12.0833 25.9792Z"
                    ></path>
                    <path
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="3.33333"
                      stroke="black"
                      d="M21.7498 25.9792C22.4172 25.9792 22.9582 25.4382 22.9582 24.7708C22.9582 24.1035 22.4172 23.5625 21.7498 23.5625C21.0825 23.5625 20.5415 24.1035 20.5415 24.7708C20.5415 25.4382 21.0825 25.9792 21.7498 25.9792Z"
                    ></path>
                    <path
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="3.33333"
                      stroke="black"
                      d="M3.021 3.02075H6.646L9.9085 18.0041C10.0215 18.5582 10.3252 19.0551 10.7668 19.4083C11.2083 19.7616 11.7598 19.9488 12.3252 19.9374H21.6293C22.1947 19.9488 22.7462 19.7616 23.1877 19.4083C23.6293 19.0551 23.933 18.5582 24.046 18.0041L25.9793 7.85408H8.57933"
                    ></path>
                  </svg>
                </div>
              </a>
              {/block}
            </div>
          </div>

          {block name='product_reviews'} {hook h='displayProductListReviews'
          product=$product} {/block}
        </div>

        {include file='catalog/_partials/product-flags.tpl'}
      </div>
    </article>
  </div>
  {/block}
</contact@prestashop.com>
