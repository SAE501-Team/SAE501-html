<div class="links_left_menu_bottom " data-count="{count($links)|escape:'htmlall':'UTF-8'}">
    {foreach from=$links key=key item=item}
        <div class="one_link">
            <a href="{$item['link']|escape:'htmlall':'UTF-8'}">
                <img src="{$item['image']|escape:'htmlall':'UTF-8'}" alt="{$item['title']|escape:'htmlall':'UTF-8'}">
                <span>{$item['title']|escape:'htmlall':'UTF-8'}</span>
            </a>
        </div>
    {/foreach}
    <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
