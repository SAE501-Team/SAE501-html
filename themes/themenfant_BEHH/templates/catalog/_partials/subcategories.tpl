{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{if !empty($subcategories)}
  {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
    <div id="subcategories" class="card card-block">
      <h2 class="subcategory-heading">{l s='Subcategories' d='Shop.Theme.Category'}</h2>

      <ul class="subcategories-list justify-center font-adlam">
        {foreach from=$subcategories item=subcategory}
          {if $subcategory.name != "Magic Blend" && $subcategory.name != "Mélange Magique"}
            <li >
                  {if !empty($subcategory.image.large.url)}
                    <div class="subcategory-image">
                      <a href="{$subcategory.url}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                        <picture>
                          {if !empty($subcategory.image.large.sources.avif)}<source srcset="{$subcategory.image.large.sources.avif}" type="image/avif">{/if}
                          {if !empty($subcategory.image.large.sources.webp)}<source srcset="{$subcategory.image.large.sources.webp}" type="image/webp">{/if}
                          <img
                            class="img-fluid"
                            src="{$subcategory.image.large.url}"
                            alt="{$subcategory.name|escape:'html':'UTF-8'}"
                            loading="lazy"
                            width="{$subcategory.image.large.width}"
                            height="{$subcategory.image.large.height}"/>
                        </picture>
                      </a>
                    </div>
                  {/if}

              <a class="subcategory-name col-span-2 sm:col-span-1 border-black border-3 transition-all ease-out duration-300 hover:bg-black flex items-center justify-center" href="{$subcategory.url}">
                <h5>
                    {$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}
                </h5>
              </a>
              {if $subcategory.description}
                <div class="cat_desc">{$subcategory.description|unescape:'html' nofilter}</div>
              {/if}
            </li>
          {/if}
        {/foreach}
      </ul>
    </div>
  {/if}
{/if}
