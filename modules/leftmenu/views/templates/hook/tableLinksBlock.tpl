<div class="tableLinksBlock">
    <div class="content_menu">
        <div class="panel form-horizontal">
            <div class="panel-heading">
                <i class="icon-plus-sign-alt"></i> {$title|escape:'htmlall':'UTF-8'}
            </div>
            <div class="form-wrapper">
                <table class="table {$type|escape:'htmlall':'UTF-8'}">
                    <thead>
                    <tr>
                        <th class="fixed-width-xs"><span class="title_box">{l s='Select' mod='leftmenu'}</span></th>
                        <th class="fixed-width-xs"><span >{l s='ID' mod='leftmenu'}</span></th>
                        <th><span class="title_box_link">{l s='Name' mod='leftmenu'}</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$value item=item}
                        <tr>
                            <td><input type="checkbox" id="item_{$item['id']|escape:'htmlall':'UTF-8'}" class="itemCheckBox" name="check_item_{$item['id']|escape:'htmlall':'UTF-8'}" {if $item['is_selected'] == true}checked="checked"{/if} value="{$item['id']|escape:'htmlall':'UTF-8'}" /></td>
                            <td>{$item['id']|escape:'htmlall':'UTF-8'}</td>
                            <td><label for="item_{$item['id']|escape:'htmlall':'UTF-8'}">{$item['name']|escape:'htmlall':'UTF-8'}</label></td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>