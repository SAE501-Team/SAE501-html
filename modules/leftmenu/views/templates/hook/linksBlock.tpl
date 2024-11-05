<div class="links_left_menu">
    {if isset($links) && $links}
        {foreach from=$links key=key item=item}
            <div>
                <a href="{$item['link']|escape:'htmlall':'UTF-8'}">{$item['title']|escape:'htmlall':'UTF-8'}</a>
            </div>
        {/foreach}
    {/if}
    <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
