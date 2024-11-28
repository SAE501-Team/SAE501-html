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
{block name='product_flags'}

<ul class="product-flags js-product-flags">
      {foreach from=$product_categories item=categorie}
        {if $categorie.name == "Mélange Magique" || $categorie.name == "Magic Blend"}
          <div
            class="text-white font-adlam bg-gradient-to-b from-be-jaune to-be-vert border-3 border-white absolute z-10 h-[56px] w-[56px] sm:h-[71px] sm:w-[71px] flex items-center justify-center text-[10px] sm:text-[11px] rounded-full rotate-[-25deg] bottom-[-22px] sm:bottom-[-30px] pl-[6px] sm:pl-[12px] leading-3 right-[4px]"
          >
            {$categorie.name}
          </div>
        {/if}
      {/foreach}
      {foreach from=$product.flags item=flag}
        {if $flag.label == "Nouveau" || $flag.label == "New"}
        <div
          class="text-white majuscule !bg-gradient-to-b from-be-bleu to-be-rose border-3 border-white absolute z-10 h-[56px] w-[56px] sm:h-[71px] sm:w-[71px] flex items-center justify-center text-[9px] sm:text-[10px] rounded-full rotate-[-25deg] bottom-[-22px] sm:bottom-[-30px] right-[52px] sm:right-[64px]"
        >
        {$flag.label}
        </div>
        {else}
            <li class="product-flag{if $flag.type == 'discount'} !bg-be-rose {else} !bg-be-bleu{/if}">{$flag.label}</li>
        {/if}
      {/foreach}
    </ul>
{/block}
