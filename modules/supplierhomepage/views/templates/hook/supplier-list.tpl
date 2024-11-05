{if $suppliers}
    <div class="container_supplier">
        <div class="title_supplier_block">
            <div class="title">{l s='Suppliers' mod='supplierhomepage'}</div>
            <div class="arrows_slider_supplier ">
                <div class="arrow_prev disabled"></div>
                <div class="arrow_next"></div>
            </div>
        </div>
        <div class="supplier-block  {if $title} supplier-block-big {else} supplier-block-small {/if}">
            <ul class="supplier-list-homepage supplier-slider" data-count="{count($suppliers)|escape:'html':'UTF-8'}">
                {foreach from=$suppliers key=key item=supplier}
                    <li class="supplier-item {if count($suppliers) == 6 && $key == 5}last-slide{/if}">
                        <a href="{$supplier['link']|escape:'htmlall':'UTF-8'}" title=" ">
                            <span class="img_block_supplier">
                                <img src="{$supplier['image']|escape:'htmlall':'UTF-8'}" alt="{$supplier.name|truncate:40:'...':true|escape:'html':'UTF-8'}">
                            </span>
                            {if $title}
                                <span class="title">{$supplier.name|truncate:40:'...':true|escape:'html':'UTF-8'}</span>
                            {/if}
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}
