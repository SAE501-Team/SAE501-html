{if $manufacturers}
    <div class="container_manufacturer">
        <div class="title_manufacturer_block">
            <div class="title">{l s='Brands' mod='manufacturerhomepage'}</div>
            <div class="arrows_slider_manufacturer ">
                <div class="arrow_prev disabled"></div>
                <div class="arrow_next"></div>
            </div>
        </div>
        <div class="manufacturer-block  {if $title} manufacturer-block-big {else} manufacturer-block-small {/if}">
            <ul class="manufacturer-list-homepage manufacturer-slider" data-count="{count($manufacturers)|escape:'html':'UTF-8'}">
                {foreach from=$manufacturers key=key item=manufacturer}
                    <li class="manufacturer-item {if count($manufacturers) == 6 && $key == 5}last-slide{/if}">
                        <a href="{$manufacturer['link']|escape:'htmlall':'UTF-8'}" title=" ">
                            <span class="img_block_manufacturer">
                                <img src="{$manufacturer['image']|escape:'htmlall':'UTF-8'}" alt="{$manufacturer.name|truncate:40:'...':true|escape:'html':'UTF-8'}">
                            </span>
                            {if $title}
                                <span class="title">{$manufacturer.name|truncate:40:'...':true|escape:'html':'UTF-8'}</span>
                            {/if}
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}
