<div class="allLinksLeftMenu">
    <table class="link_list_left_menu">
        <thead>
        <tr>
            <th class="linkIdL">{l s='ID' mod='leftmenu'}</th>
            <th class="linkTitleL">{l s='Title' mod='leftmenu'}</th>
            <th class="linkUrlL">{l s='Url' mod='leftmenu'}</th>
            <th class="linkEditL">{l s='Edit' mod='leftmenu'}</th>
            <th class="linkRemoveL">{l s='Delete' mod='leftmenu'}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$allLinks key=key item=item}
            <tr class="{if $key % 2 == 0} odd{/if}">
                <td class="linkIdL">{$item['id_left_menu_link']|escape:'htmlall':'UTF-8'}</td>
                <td class="linkTitleL">{$item['title']|escape:'htmlall':'UTF-8'}</td>
                <td class="linkUrlL">{$item['link']|escape:'htmlall':'UTF-8'}</td>
                <td class="linkEditL">
                    <a data-id-link="{$item['id_left_menu_link']|escape:'htmlall':'UTF-8'}" title="{l s='Edit' mod='leftmenu'}" class="edit btn btn-default"> <i class="icon-pencil"></i> {l s='Edit' mod='leftmenu'}</a>
                </td>
                <td class="linkRemoveL">
                    <a class="btn btn-default" data-id-link="{$item['id_left_menu_link']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
