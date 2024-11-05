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

<!-- Block manufacturers module -->
<div id="manufacturers_block_left" class="block blockmanufacturer">

	<h4 onclick="" class="title_block">{if $display_link_manufacturer}<a href="{$link->getPageLink('manufacturer')|escape:'htmlall':'UTF-8'}" title="{l s='Brands' mod='manufacturerhomepage'}">{/if}{l s='Brands' mod='manufacturerhomepage'}{if $display_link_manufacturer}</a>{/if}
		<i class="material-icons material-icons-add">add</i>
		<i class="material-icons material-icons-remove">remove</i>
	</h4>

	<div class="block_content list-block">

{if $manufacturers}
	{if $text_list}
		<ul class="bullet">
			{foreach from=$manufacturers item=manufacturer name=manufacturer_list}
				{if $smarty.foreach.manufacturer_list.iteration <= $text_list_nb}
				<li class="{if $smarty.foreach.manufacturer_list.last}last_item{elseif $smarty.foreach.manufacturer_list.first}first_item{else}item{/if}"><a href="{$manufacturer['link']|escape:'htmlall':'UTF-8'}" title="{l s='More about %s' sprintf=[$manufacturer.name|escape:'htmlall':'UTF-8'] mod='manufacturerhomepage'}"><i class="material-icons">keyboard_arrow_right</i>{$manufacturer.name|escape:'html':'UTF-8'}</a></li>
				{/if}
			{/foreach}
		</ul>
	{/if}
	{if $form_list}
		<form action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">

				<select id="manufacturer_list" onchange="autoUrl('manufacturer_list', '');">
					<option value="0">{l s='All brands' mod='manufacturerhomepage'}</option>
				{foreach from=$manufacturers item=manufacturer}
					<option value="{$manufacturer['link']|escape:'htmlall':'UTF-8'}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
				{/foreach}
				</select>

		</form>
	{/if}
{else}
	<p>{l s='No manufacturer' mod='manufacturerhomepage'}</p>
{/if}
	</div>
</div>
<!-- /Block manufacturers module -->
