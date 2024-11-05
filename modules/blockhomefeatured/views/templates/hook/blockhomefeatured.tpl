
<div class="container_featured">
    {if isset($tabs) && $tabs}
        <div class="homepage_tabs">
            <div class="hidden_block">
                <input type="hidden" class="id_shop" value="{$id_shop|escape:'htmlall':'UTF-8'}">
                <input type="hidden" class="id_lang" value="{$id_lang|escape:'htmlall':'UTF-8'}">
                <input type="hidden" value="{$base_url|escape:'htmlall':'UTF-8'}" name="basePath">
            </div>
            <ul class="homepage_tabs_list">
                {foreach $tabs as $key => $tab}
                    <li data-id-tab="{$tab['id_homefeatured']|escape:'htmlall':'UTF-8'}" class="tab_featured {if $key == 0 }active first_tab_featured{/if}">
                        <a>{$tab['title']|escape:'htmlall':'UTF-8'}</a>
                    </li>
                {/foreach}
                <li style="clear: both"></li>
            </ul>
            <div style="clear: both"></div>
            <div class="arrow_featured_slider">
                <div class="arrow_featured_prev disabled"></div>
                <div class="arrow_featured_next"></div>
            </div>
        </div>
    {/if}


    <div class="homepage_tab_content">
        <div class="homepage_tab_content_no_products">
            <div class="featured_overlay_loading"></div> <div class="featured_overlay"></div>
            {l s='There are no products in this category.' mod='blockhomefeatured'}
        </div>
    </div>
</div>