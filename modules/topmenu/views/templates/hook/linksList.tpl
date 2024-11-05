<div class="allLinks">
    <table class="table_link_list">
        <thead>
            <tr>
                <th class="linkId">{l s='ID' mod='topmenu'}</th>
                <th class="linkTitle">{l s='Title' mod='topmenu'}</th>
                <th class="linkUrl">{l s='Url' mod='topmenu'}</th>
                <th class="linkEdit">{l s='Edit' mod='topmenu'}</th>
                <th class="linkRemove">{l s='Delete' mod='topmenu'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$allLinks key=key item=item}
                <tr class="{if $key % 2 == 0} odd{/if}">
                    <td class="linkId">{$item['id_top_menu_link']|escape:'htmlall':'UTF-8'}</td>
                    <td class="linkTitle">{$item['title']|escape:'htmlall':'UTF-8'}</td>
                    <td class="linkUrl">{$item['link']|escape:'htmlall':'UTF-8'}</td>
                    <td class="linkEdit">
                        <a data-id-link="{$item['id_top_menu_link']|escape:'htmlall':'UTF-8'}" title="{l s='Edit' mod='topmenu'}" class="edit btn btn-default"> <i class="icon-pencil"></i> {l s='Edit' mod='topmenu'}</a>
                    </td>
                    <td class="linkRemove">
                        <a class="btn btn-default" data-id-link="{$item['id_top_menu_link']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>