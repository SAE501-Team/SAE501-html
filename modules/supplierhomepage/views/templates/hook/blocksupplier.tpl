
<div id="suppliers_block_left" class="block blocksupplier">
	<h4 onclick="" class="title_block">{if $display_link_supplier}<a href="{$link->getPageLink('supplier')|escape:'htmlall':'UTF-8'}" title="{l s='Suppliers' mod='supplierhomepage'}">{/if}{l s='Suppliers' mod='supplierhomepage'}{if $display_link_supplier}</a>{/if}
		<i class="material-icons material-icons-add">add</i>
		<i class="material-icons material-icons-remove">remove</i>
	</h4>
	<div class="block_content list-block">
	{if $suppliers}
		{if $text_list}
			<ul class="bullet">
				{foreach from=$suppliers item=supplier name=supplier_list}
					{if $smarty.foreach.supplier_list.iteration <= $text_list_nb}
					<li class="{if $smarty.foreach.supplier_list.last}last_item{elseif $smarty.foreach.supplier_list.first}first_item{else}item{/if}">
						{if $display_link_supplier}<a href="{$supplier['link']|escape:'htmlall':'UTF-8'}" title="{l s='More about' mod='supplierhomepage'} {$supplier.name|escape:'htmlall':'UTF-8'}">{/if}<i class="material-icons">keyboard_arrow_right</i>{$supplier.name|escape:'html':'UTF-8'}{if $display_link_supplier}</a>{/if}
					</li>
					{/if}
				{/foreach}
			</ul>
		{/if}
		{if $form_list}
			<form action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">

					<select id="supplier_list" onchange="autoUrl('supplier_list', '');">
						<option value="0">{l s='All suppliers' mod='supplierhomepage'}</option>
					{foreach from=$suppliers item=supplier}
						<option value="{$supplier['link']|escape:'html'}">{$supplier.name|escape:'html':'UTF-8'}</option>
					{/foreach}
					</select>

			</form>
		{/if}
	{else}
		<p>{l s='No supplier' mod='supplierhomepage'}</p>
	{/if}
	</div>
</div>

