


{if isset($categories) && $categories}

    <div class="block_subcategories">
        <div class="title_subcategories_block">
            <div class="title">
                {l s='Categories' mod='mpm_subcategories'}
            </div>
            <div class="arrows_slider_subcategory">
                <div class="arrow_prev disabled"></div>
                <div class="arrow_next"></div>
            </div>
        </div>
        <ul data-count="{count($categories)|escape:'htmlall':'UTF-8'}" id="categories_slider" class="categories_slider">
            {foreach from=$categories item="category"}

                <li class="subcategories_item">
                    <a class="subcategory-image" href="{$category['link']|escape:'htmlall':'UTF-8'}">
                        <img src="{$category['images']['bySize']['category_default']['url']|escape:'htmlall':'UTF-8'}" alt="{$category['name']|escape:'htmlall':'UTF-8'}">
                    </a>
                    <a class="subcategory-name" href="{$category['link']|escape:'htmlall':'UTF-8'}">
                        <span>{$category['name']|escape:'htmlall':'UTF-8'}</span>
                    </a>
                </li>

            {/foreach}
        </ul>
    </div>

{/if}