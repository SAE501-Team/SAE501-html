<div class="links_menu">
    {foreach from=$links key=key item=item}
        <div>
            <a href="{$item['link']|escape:'htmlall':'UTF-8'}">{$item['title']|escape:'htmlall':'UTF-8'}</a>
        </div>
    {/foreach}
    <span style="clear: both"></span>
</div>
<div style="clear: both"></div>
