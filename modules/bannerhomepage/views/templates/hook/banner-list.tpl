{if $banners}
    <div class="container_banner">
        <div class="title_banner_block">
            <div class="title">{l s='Our banners' mod='bannerhomepage'}</div>
            <div class="arrows_slider_banner ">
                <div class="arrow_prev disabled"></div>
                <div class="arrow_next"></div>
            </div>
        </div>
        <div class="banner-block  {if $description} banner-block-big {else} banner-block-small {/if}">
            <ul class="banner-list-homepage banner-slider" data-count="{count($banners)|escape:'html':'UTF-8'}">
                {foreach from=$banners key=key item=banner}
                    <li class="banner-item {if count($banners) == 6 && $key == 5}last-slide{/if}">
                        <a href="{$banner['url']|escape:'htmlall':'UTF-8'}" title=" ">
                            <span class="banner-item-img">
							    <img src="{$banner['img']|escape:'htmlall':'UTF-8'}" alt="{$banner.title|truncate:40:'...':true|escape:'html':'UTF-8'}">
                            </span>
                            {if $description}
                                <span class="title">{$banner.title|truncate:40:'...':true|escape:'html':'UTF-8'}</span>
                                <span class="description">{$banner.description|escape:'html':'UTF-8' nofilter}</span>
                            {/if}
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}