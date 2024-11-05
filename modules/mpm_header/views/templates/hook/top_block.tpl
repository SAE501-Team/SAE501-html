<div class="_desktop_logo">
    <a href="{$urls.base_url|escape:'htmlall':'UTF-8'}">
        <img class="logo img-responsive" src="{$shop.logo|escape:'htmlall':'UTF-8'}" alt="{$shop.name|escape:'htmlall':'UTF-8'}">
    </a>
</div>

<div id="_desktop_header_right" class="{if !{widget name="freecall"}}freecall_disable{/if}">
    {widget name="freecall"}
    {widget name="ps_shoppingcart"}
    {hook h='displayTop'}
</div>

<div class="block_after_top">
    {widget name="topmenu"}
</div>

