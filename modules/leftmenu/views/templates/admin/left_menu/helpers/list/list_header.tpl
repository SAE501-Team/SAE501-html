{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file="helpers/list/list_header.tpl"}
{block name=leadin}

  {if isset($max_depth)}
    <div class="panel col-lg-12">
        <div class="panel-heading"><i class="icon-cogs icon-cogs-max-depth"></i>{l s='Maximum depth'  mod='leftmenu'}</div>
        <div class="form-wrapper">
            <div class="form-group">
                <input type="text" name="left_menu_max_depth" id="left_menu_max_depth" value="{if isset($max_depth_val) && $max_depth_val}{$max_depth_val|escape:'htmlall':'UTF-8'}{/if}"  class="left_menu_max_depth">
                <p class="help-block">{l s='Set the maximum depth of category sublevels displayed in this block (0 = infinite).'  mod='leftmenu'}</p>
            </div>
        </div>
        <div class="panel-footer">
            <button data-token="{$token_cont|escape:'htmlall':'UTF-8'}" type="submit" class="save_max_depth btn btn-default pull-right"><i class="process-icon-save"></i>{l s='Save and stay'  mod='leftmenu'}</button>
        </div>
    </div>

  {/if}
{/block}
