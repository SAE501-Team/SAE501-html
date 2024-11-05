<div class="form-group form-group-products-add-left">
    {if $items}
        <table class="table_product_list_left">
            <thead>
                <tr>
                    <th class="table_list_id">{l s='ID' mod='leftmenu'}</th>
                    <th class="table_list_img">{l s='Image' mod='leftmenu'}</th>
                    <th class="table_prod_name">{l s='Name' mod='leftmenu'}</th>
                    <th class="table_list_delete">{l s='Delete' mod='leftmenu'}</th>
                </tr>
            </thead>
            <tbody>
            {foreach  from=$items key=key item=item}
                <tr class="{if $key % 2 == 0} odd{/if}" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}">
                    <td class="table_list_id">{$item['id_product']|escape:'htmlall':'UTF-8'}</td>
                    <td class="table_list_img"><img class="img_product_mativator" src="{$item['image']|escape:'htmlall':'UTF-8'}"></td>
                    <td class="table_list_name">{$item['name']|escape:'htmlall':'UTF-8'}</td>
                    <td class="table_list_delete"><a class="btn btn-default" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        <div style="clear: both"></div>
    {/if}
</div>