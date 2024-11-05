{if $blockCategTree && $blockCategTree.children|@count}

    {if $page_name == 'product'}
        <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
    {/if}

    <div id="block_left_menu" class="block desktop_device {if $page_name == 'product'} block_left_menu_product{/if}">
        <h4 onclick="" class="title_block {if $page_name == 'product'} title_block_prod{/if}">{l s='Categories' mod='leftmenu'}</h4>

         <div class="block_content_l  {if $page_name == 'product'} block_left_menu_prod{/if}">
            <ul class="tree">
                {foreach from=$blockCategTree.children item=child name=blockCategTree}
                    {if $smarty.foreach.blockCategTree.last}
                        {include file="$tpl_path" node=$child last='true'}
                    {else}
                        {include file="$tpl_path" node=$child}
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>

    {if $page_name == 'product'}
        </div>
    {/if}

{/if}
