<div class="block_homefeatured_content">
    <div class="featured_overlay_loading"></div>
    <div class="featured_overlay"></div>
    {if $categories}
        <div class="column_featured">
            <div class="homefeatured_categories">
                {if count($categories) > 5} <div class="arrow_top" ><i class="material-icons">arrow_upward</i></div> {/if}
                <ul {if count($categories) > 5} class="add_slider" {/if}>
                    {foreach  from=$categories key=key item=item}
                    <li class="category_products tab_{$item['id_category']|escape:'htmlall':'UTF-8'}" data-id-cat="{$item['id_category']|escape:'htmlall':'UTF-8'}" data-type="{$type|escape:'htmlall':'UTF-8'}">
                        <span class="homefeatured_categories_title">
                            <span class="images_cat">
                                <img src="{$item['images']|escape:'htmlall':'UTF-8'}" alt="{$item['name']|escape:'htmlall':'UTF-8'}">
                            </span>
                            <span class="title_cat">
                                <span class="name_cat"> {$item['name']|escape:'htmlall':'UTF-8'}</span>
                                <span class="count_cat">{$item['count_products']|escape:'htmlall':'UTF-8'} {l s='Products' mod='blockhomefeatured'}</span>
                            </span>
                        </span>
                    </li>
                    {/foreach}
                </ul>
                {if count($categories) > 5} <div class="arrow_bottom"><i class="material-icons">arrow_downward</i></div> {/if}
            </div>
        </div>
    {/if}

    <div class="homefeatured_products">
        <div class="featured_products_overlay_loading"></div> <div class="featured_products_overlay"></div>
        <div class="content">
            {include file="$tpl_path"}
        </div>
    </div>

</div>



