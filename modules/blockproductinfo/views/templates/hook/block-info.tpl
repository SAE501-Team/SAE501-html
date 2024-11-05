<div class="product-block-info">
    <ul class="block-del">
        {foreach from=$settings key=key item=value}
            <li class="product-block-item  {if $value['image']} isset_images {/if}">
                {if $value['image']}
                    <img class="block_img" src="{$value['image']|escape:'htmlall':'UTF-8'}" alt="{$value['title']|escape:'htmlall':'UTF-8'}">
                 {/if}
                <span class="block_title">{$value['title']|escape:'htmlall':'UTF-8'}</span>
                <span style="clear: both"></span>
                <span class="block_description">{$value['description']|escape:'htmlall':'UTF-8' nofilter}</span>
            </li>
        {/foreach}
    </ul>
</div>